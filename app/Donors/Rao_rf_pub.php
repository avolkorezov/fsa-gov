<?php

namespace App\Donors;

use App\Http\Controllers\LoggerController;
use App\Models\ProxyList;
use ParseIt\_String;
use ParseIt\nokogiri;
use App\Donors\ParseIt\simpleParser;
use ParseIt\ParseItHelpers;

Class Rao_rf_pub extends simpleParser {

    public $data = [];
    public $reload = [];
    public $project = 'rao_rf_pub';
    public $project_link = 'http://188.254.71.82';
    public $source = 'http://public.fsa.gov.ru/table_rao_pub_rf/index.php';
    public $cache = false;
    public $proxy = false;
    public $cookieFile = '';
    public $version_id = 1;
    public $donor = 'Rao_rf_pub';
    public $model = 'RaoRfPub';

    function __construct()
    {
        $this->cookieFile = __DIR__.'/cookie/'.class_basename(get_class($this)).'/'.class_basename(get_class($this)).'.txt';
    }

    public function getSources($opt = [])
    {
        $sources = [];
        $currPage = 0;
        $begin = preg_match("%\d{1,2}\.\d{1,2}\.\d{4}%uis", @$opt['begin']) ? date('d.m.Y',strtotime(@$opt['begin'])) : date('d.m.Y', time()-(31*24*60*60));
        $end = preg_match("%\d{1,2}\.\d{1,2}\.\d{4}%uis", @$opt['end']) ? date('d.m.Y',strtotime(@$opt['end'])) : date('d.m.Y');
        do
        {
            $nextPage = $currPage + 1;
            $opt['post'] = [
                'ajax' => 'main',
                'action' => 'search',
                'input_2_begin' => $begin,
                'input_2_end' => $end,
                'html_id' => 'innertube',
                'ajaxId' => '2194787380',
                'page_noid_' => $currPage,
                'pageGoid_' => $nextPage,
                'sortid_' => 'DESC',
                'page_byid_' => 50
            ];
            $opt['referer'] = $this->source;
            $opt['origin'] = 'http://188.254.71.82';
            $opt['host'] = '188.254.71.82';
            $content = $this->loadUrl($this->source, $opt);
            $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
            $content = iconv('windows-1251', 'UTF-8', $content);
            if ( preg_match('%checkedVal\((\d+)\)\;%uis', $content, $match) )
            {
                $countPage = $match[1];
                if ( $countPage-1 > $currPage )
                {
                    $currPage++;
                }
            }
            $content = ParseItHelpers::fixEncoding($content);
            $content = ParseItHelpers::fixHeader($content);
            $nokogiri = new nokogiri($content);
            $rows = $nokogiri->get("#bodyTableData tbody tr")->toArray();
            foreach ($rows as $key => $row)
            {
                if (isset($row['td'][1]['a']['href']))
                {
                    $href = $row['td'][1]['a']['href'];
                    $hash = md5($href);
                    $sources[$hash]= [
                        'hash' => $hash,
                        'name' => '',
                        'source' => $href,
                        'donor_class_name' => $this->donor,
                        'version' => 2,
                        'param' => [
                            'STATUS' => trim(@$row['td'][0]['span']['title']),
                            'CERT_NUM' => trim(@$row['td'][1]['__ref']->nodeValue),
                            'REG_DATE' => trim(@$row['td'][2]['__ref']->nodeValue),
                            'END_DATE' => trim(@$row['td'][3]['__ref']->nodeValue),
                            'STATUS_DATE' => trim(@$row['td'][4]['__ref']->nodeValue),
                            'FULL_NAME' => trim(@$row['td'][5]['__ref']->nodeValue),
                            'FIO' => trim(@$row['td'][6]['__ref']->nodeValue),
                            'ADDRESS' => trim(@$row['td'][7]['__ref']->nodeValue),
                            'ACC_AREA' => trim(@$row['td'][8]['__ref']->nodeValue),
                        ]
                    ];
                }
            }
        }
        while( $nextPage === $currPage );

        return $sources;
    }

    public function getData($url, $source = [])
    {
        $data = false;
        $source['referer'] = $url;
//                $href = "http://188.254.71.82/rao_rf_pub/?show=view&id_object=348AB4BCFC01402EA5974EBFB82D4D3D";
        $href = $url;
        $content = $this->loadUrl($href, $source);
//                if ( !$content )
//                {
////                    sleep(2);
////                    continue;
//                }
//                print_r($content);die();
        $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
        $content = iconv('windows-1251', 'UTF-8', $content);
        $content = ParseItHelpers::fixEncoding($content);
        $content = ParseItHelpers::fixHeader($content);
        $nokogiri = new nokogiri($content);

        if ($captcha = $nokogiri->get("input[name=captcha]")->toArray())
        {
            if ( !$this->entry_captcha($content, $href) )
            {
                return $data;
            }
            unset($content, $nokogiri);
            $content = $this->loadUrl($href, $source);
            $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
            $content = iconv('windows-1251', 'UTF-8', $content);
            $content = ParseItHelpers::fixEncoding($content);
            $content = ParseItHelpers::fixHeader($content);
            $nokogiri = new nokogiri($content);
            if ($captcha = $nokogiri->get("input[name=captcha]")->toArray())
            {
                return $data;
            }
        }

        $NUM_DECISION = $nokogiri->get("#a_init_accred-ral-init_accred-order_number .form-right-col")->toArray();
        $DATE_DECISION = $nokogiri->get("#a_init_accred-ral-init_accred-order_date .form-right-col")->toArray();
        $TYPE_DECLARANT = $nokogiri->get(".type-group_element .form-left-col .text-label")->toArray();
        $type = [];
        foreach ( $TYPE_DECLARANT as $value )
        {
            if ( preg_match('%(Юридическое лицо|Орган по сертификации|Испытательная лаборатория)%uis', @$value['__ref']->nodeValue) )
            {
                $type[] = $value['__ref']->nodeValue;
            }
        }
        $PHONE = $nokogiri->get("#a_applicant-ral-applicant_j_data-phone .form-right-col")->toArray();
        $FAX = $nokogiri->get("#a_applicant-ral-applicant_j_data-fax .form-right-col")->toArray();
        $EMAIL = $nokogiri->get("#a_applicant-ral-applicant_j_data-email .form-right-col")->toArray();
        $GOS_REG_YR = $nokogiri->get("#a_applicant-ral-applicant_j_data-ogrn .form-right-col")->toArray();
        $INN = $nokogiri->get("#a_applicant-ral-applicant_j_data-inn .form-right-col")->toArray();

        $FIO_RUC_ACC_LICA = $nokogiri->get("#a_accred_person-ral-cert_org_data-director_name .form-right-col")->toArray();
        $ADRESS_ACC_AREA = $nokogiri->get("#a_accred_person-ral-cert_org_data-address .form-right-col")->toArray();

        $OKPD = $nokogiri->get("#a_accred_scope-0-ral-accred_scope-okpd .form-right-col")->toArray();
        $OKPD_TEXT = $nokogiri->get("#a_accred_scope-0-ral-accred_scope-okpd_text .form-right-col")->toArray();
        $OKP = $nokogiri->get("#a_accred_scope-0-ral-accred_scope-okp .form-right-col")->toArray();
        $OKP_TEXT = $nokogiri->get("#a_accred_scope-0-ral-accred_scope-okp_text .form-right-col")->toArray();
        $OKUN = $nokogiri->get("#a_accred_scope-0-ral-accred_scope-okun .form-right-col")->toArray();
        $OKUN_TEXT = $nokogiri->get("#a_accred_scope-0-ral-accred_scope-okun_text .form-right-col")->toArray();
        $TN_VAD = $nokogiri->get("#a_accred_scope-0-ral-accred_scope-tnved .form-right-col")->toArray();
        $TN_VAD_TEXT = $nokogiri->get("#a_accred_scope-0-ral-accred_scope-tnved_text .form-right-col")->toArray();
        $SKAN = $nokogiri->get("#a_accred_scope_file .form-right-col .file_name")->toArray();

        $NC_TN_VAD_EAS = $nokogiri->get("#a_national_part-0-tnved .form-right-col")->toArray();
        $NC_TN_VAD_EAS_TEXT = $nokogiri->get("#a_national_part-0-tnved_text .form-right-col")->toArray();
        $NC_TR_EAS = $nokogiri->get("#a_national_part-0-tech_regul .form-right-col")->toArray();
        $NC_ACC_AREA = $nokogiri->get("#a_national_part-0-accred_scope .form-right-col")->toArray();

        $NUM_DECISION_INCR_ACC_AREA = $nokogiri->get("#a_cert_changes-0-ral-expansion_accred_scope-decision_number .form-right-col")->toArray();
        $DATE_DECISION_INCR_ACC_AREA = $nokogiri->get("#a_cert_changes-0-ral-expansion_accred_scope-decision_date .form-right-col")->toArray();
        $TR_INCR_ACC_AREA = $nokogiri->get("#a_cert_changes-0-ral-expansion_accred_scope-accred_scope-0-ral-accred_scope-tech_reg_ts .form-right-col")->toArray();
        $TN_VAD_INCR_ACC_AREA = $nokogiri->get("#a_cert_changes-0-ral-expansion_accred_scope-accred_scope-0-ral-accred_scope-tnved .form-right-col")->toArray();
        $TN_VAD_INCR_ACC_AREA_TEXT = $nokogiri->get("#a_cert_changes-0-ral-expansion_accred_scope-accred_scope-0-ral-accred_scope-tnved_text .form-right-col")->toArray();
        $DESC_ACC_AREA = $nokogiri->get("#a_cert_changes-0-ral-expansion_accred_scope-accred_scope-0-ral-accred_scope-accred_scope .form-right-col")->toArray();
        $DESC_ACC_AREA_TEXT = $nokogiri->get("#a_cert_changes-0-ral-expansion_accred_scope-accred_scope-0-ral-accred_scope-accred_scope_text .form-right-col")->toArray();

        $TR = $nokogiri->get("#a_accred_scope-0-ral-accred_scope-tech_reg_ts .form-right-col")->toArray();

        $data[] = [
            'STATUS' => @$source['param']['STATUS'],
            'CERT_NUM' => @$source['param']['CERT_NUM'],
            'REG_DATE' => @$source['param']['REG_DATE'],
            'END_DATE' => @$source['param']['END_DATE'],
            'STATUS_DATE' => @$source['param']['STATUS_DATE'],

            'NUM_DECISION' => trim(@$NUM_DECISION[0]['__ref']->nodeValue),
            'DATE_DECISION' => trim(@$DATE_DECISION[0]['__ref']->nodeValue),
            'TYPE_DECLARANT' => implode('|', $type),

            'FULL_NAME' => @$source['param']['FULL_NAME'],
            'FIO' => @$source['param']['FIO'],
            'ADDRESS' => @$source['param']['ADDRESS'],

            'PHONE' => trim(@$PHONE[0]['__ref']->nodeValue),
            'FAX' => trim(@$FAX[0]['__ref']->nodeValue),
            'EMAIL' => trim(@$EMAIL[0]['__ref']->nodeValue),
            'GOS_REG_YR' => trim(@$GOS_REG_YR[0]['__ref']->nodeValue),
            'INN' => trim(@$INN[0]['__ref']->nodeValue),

            'FIO_RUC_ACC_LICA' => trim(@$FIO_RUC_ACC_LICA[0]['__ref']->nodeValue),
            'ADRESS_ACC_AREA' => trim(@$ADRESS_ACC_AREA[0]['__ref']->nodeValue),

            'ACC_AREA' => @$source['param']['ACC_AREA'],
            'TR' => trim(@$TR[0]['__ref']->nodeValue),
            'OKPD' => trim(@$OKPD[0]['__ref']->nodeValue)."|".trim(@$OKPD_TEXT[0]['__ref']->nodeValue),
            'OKP' => trim(@$OKP[0]['__ref']->nodeValue)."|".trim(@$OKP_TEXT[0]['__ref']->nodeValue),
            'OKUN' => trim(@$OKUN[0]['__ref']->nodeValue)."|".trim(@$OKUN_TEXT[0]['__ref']->nodeValue),
            'TN_VAD' => trim(@$TN_VAD[0]['__ref']->nodeValue)."|".trim(@$TN_VAD_TEXT[0]['__ref']->nodeValue),
            'SKAN' => isset($SKAN[0]['data-id']) && !empty($SKAN[0]['data-id']) ? "http://188.254.71.82/rao_rf_pub/?ajax=files&action=get_file&file_id=".trim(@$SKAN[0]['data-id'])."&getFile=1" : '',

            'NC_TN_VAD_EAS' => trim(@$NC_TN_VAD_EAS[0]['__ref']->nodeValue)."|".trim(@$NC_TN_VAD_EAS_TEXT[0]['__ref']->nodeValue),
            'NC_TR_EAS' => trim(@$NC_TR_EAS[0]['__ref']->nodeValue),
            'NC_ACC_AREA' => trim(@$NC_ACC_AREA[0]['__ref']->nodeValue),

            'NUM_DECISION_INCR_ACC_AREA' => trim(@$NUM_DECISION_INCR_ACC_AREA[0]['__ref']->nodeValue),
            'DATE_DECISION_INCR_ACC_AREA' => trim(@$DATE_DECISION_INCR_ACC_AREA[0]['__ref']->nodeValue),
            'TR_INCR_ACC_AREA' => trim(@$TR_INCR_ACC_AREA[0]['__ref']->nodeValue),
            'TN_VAD_INCR_ACC_AREA' => trim(@$TN_VAD_INCR_ACC_AREA[0]['__ref']->nodeValue)."|".trim(@$TN_VAD_INCR_ACC_AREA_TEXT[0]['__ref']->nodeValue),
            'DESC_ACC_AREA' => trim(@$DESC_ACC_AREA[0]['__ref']->nodeValue)."|".trim(@$DESC_ACC_AREA_TEXT[0]['__ref']->nodeValue),
        ];

        return $data;
    }

    public function entry_captcha($content, $url)
    {
        $nokogiri = new nokogiri($content);
        $img = "http://188.254.71.82/".$this->project."/".$nokogiri->get("form img")->toArray()[0]['src'];
        $api = new \ImageToText();
        $api->setVerboseMode(true);
        $api->setKey(env('ANTI_CAPTCHA_KEY', ''));
        $hash = md5($img);
        $filename = \App::basePath()."/storage/".$hash.".png";
        $opt['cookieFile'] = $this->cookieFile;
        $opt['referer'] = $img;
        $opt['origin'] = 'http://188.254.71.82';
        $opt['host'] = '188.254.71.82';
        $file = $this->loadUrl($img, $opt);
        file_put_contents($filename, $file);
        $api->setFile($filename);

        if (!$api->createTask()) {
            $api->debout("API v2 send failed - ".$api->getErrorMessage(), "red");
            return false;
        }
        $taskId = $api->getTaskId();
        if (!$api->waitForResult()) {
            $api->debout("could not solve captcha", "red");
            $api->debout($api->getErrorMessage());
        } else {
            $captcha = $api->getTaskSolution();
            print_r($captcha);
            $opt['post'] = [
                'captcha' => trim($captcha),
            ];
            $opt['referer'] = $url;
            $content = $this->loadUrl("http://188.254.71.82/".$this->project."/reg.php", $opt);
            print_r($content);
            return true;
        }
        return false;
    }
}
