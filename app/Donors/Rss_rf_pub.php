<?php

namespace App\Donors;

use App\Http\Controllers\LoggerController;
use App\Models\ProxyList;
use ParseIt\_String;
use ParseIt\nokogiri;
use App\Donors\ParseIt\simpleParser;
use ParseIt\ParseItHelpers;

Class Rss_rf_pub extends simpleParser {

    public $data = [];
    public $reload = [];
    public $project = 'rss_rf_pub';
    public $project_link = 'http://188.254.71.82';
    public $source = 'http://public.fsa.gov.ru/table_rss_pub_rf/';
    public $cache = false;
    public $proxy = false;
    public $cookieFile = '';
    public $version_id = 1;
    public $donor = 'Rss_rf_pub';

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
                'ajaxId' => '8372942386',
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
                            'ISSUE_DATE' => trim(@$row['td'][2]['__ref']->nodeValue),
                            'END_DATE' => trim(@$row['td'][3]['__ref']->nodeValue),
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
//                $href = "http://188.254.71.82/rss_rf_pub/?show=view&id_object=278E43513D80496081872F2865ACBF88";
        $href = $url;
        $content = $this->loadUrl($href, $source);
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
        $CERF_RF = $nokogiri->get("#cgroup-cert_type-cert_rf")->toArray();

        $ORG_TYPE = $nokogiri->get("#a_applicant_org_type-ul .form-left-col .text-label")->toArray();
        $MANUFACTURER_TYPE = $nokogiri->get("#a_manufacturer_type-ul .form-left-col .text-label")->toArray();

        $O_APPLICANT_TYPE = $nokogiri->get("#a_applicant_info-rss-app_legal_person-applicant_type .form-right-col")->toArray();
        $DECLARANT = $nokogiri->get("#a_applicant_info-rss-app_legal_person-name .form-right-col")->toArray();
        $O_DIRECTOR_NAME = $nokogiri->get("#a_applicant_info-rss-app_legal_person-director_name .form-right-col")->toArray();
        $O_ADDRESS = $nokogiri->get("#a_applicant_info-rss-app_legal_person-address .form-right-col")->toArray();
        $O_PHONE = $nokogiri->get("#a_applicant_info-rss-app_legal_person-phone .form-right-col")->toArray();
        $O_FAX = $nokogiri->get("#a_applicant_info-rss-app_legal_person-fax .form-right-col")->toArray();
        $O_EMAIL = $nokogiri->get("#a_applicant_info-rss-app_legal_person-email .form-right-col")->toArray();
        $O_OGRN = $nokogiri->get("#a_applicant_info-rss-app_legal_person-ogrn .form-right-col")->toArray();

        $MANUFACTURER = $nokogiri->get("#a_manufacturer_info-rss-man_legal_person-name .form-right-col")->toArray();
        $M_ADDRESS = $nokogiri->get("#a_manufacturer_info-rss-man_legal_person-address .form-right-col")->toArray();
        $M_PHONE = $nokogiri->get("#a_manufacturer_info-rss-man_legal_person-phone .form-right-col")->toArray();
        $M_FAX = $nokogiri->get("#a_manufacturer_info-rss-man_legal_person-fax .form-right-col")->toArray();
        $M_EMAIL = $nokogiri->get("#a_manufacturer_info-rss-man_legal_person-email .form-right-col")->toArray();
        $M_OGRN = $nokogiri->get("#a_manufacturer_info-rss-man_legal_person-ogrn .form-right-col")->toArray();

        $DOCUMENT_INFO = $nokogiri->get("#a_cert_doc_issued-rss-cert_doc_issued-document_info .form-right-col")->toArray();
        $BASIC_FOR_CERTIFICATE = $nokogiri->get("#a_cert_doc_issued-rss-cert_doc_issued-testing_lab-0-basis_for_certificate .form-right-col")->toArray();

        $REG_NUMBER = $nokogiri->get("#a_cert_doc_issued-rss-cert_doc_issued-testing_lab-0-reg_number .form-right-col")->toArray();
        $TESTING_LAB_0_NAME = $nokogiri->get("#a_cert_doc_issued-rss-cert_doc_issued-testing_lab-0-name .form-right-col")->toArray();
        $DATE_REG = $nokogiri->get("#a_cert_doc_issued-rss-cert_doc_issued-testing_lab-0-date_reg .form-right-col")->toArray();
        $OTHER_DOCUMENT_INFO = $nokogiri->get("#a_cert_doc_issued-rss-cert_doc_issued-other_document_info .form-right-col")->toArray();

        $OBJECT_TYPE_CERT = $nokogiri->get("#a_product_info-rss-product_rf-object_type_cert .form-right-col")->toArray();
        $PRODUCT_TYPE = $nokogiri->get("#a_product_info-rss-product_rf-product_type .form-right-col")->toArray();
        $PRODUCTION = $nokogiri->get("#a_product_info-rss-product_rf-product_name .form-right-col")->toArray();
        $PRODUCTION_INFO = $nokogiri->get("#a_product_info-rss-product_rf-product_info .form-right-col")->toArray();
        $OKPD2 = $nokogiri->get("#a_product_info-rss-product_rf-okpd2 .form-right-col")->toArray();
        $OKPD2_TEXT = $nokogiri->get("#a_product_info-rss-product_rf-okpd2_text .form-right-col")->toArray();
        $TN_VED = $nokogiri->get("#a_product_info-rss-product_rf-tn_ved .form-right-col")->toArray();
        $TN_VED_TEXT = $nokogiri->get("#a_product_info-rss-product_rf-tn_ved_text .form-right-col")->toArray();
        $PRODUCT_INFO_EXT = $nokogiri->get("#a_product_info-rss-product_rf-tn_ved_text .form-right-col")->toArray();

        $REGLAMENT = $nokogiri->get("#a_product_info-rss-product_rf-prod_doc_issued-0-tech_reg .form-right-col")->toArray();
        $PROD_DOC_ISSUED = $nokogiri->get("#a_product_info-rss-product_rf-prod_doc_issued-0-tech_reg_ext .form-right-col")->toArray();

        $NOTATION_NATIONAL_STANDART = $nokogiri->get("#a_product_info-rss-product_rf-standart_requlations-0-notation_national_standart .form-right-col")->toArray();

        $a_expert_0_last_name = $nokogiri->get("#a_expert-0-last_name .form-right-col")->toArray();
        $a_expert_0_first_name = $nokogiri->get("#a_expert-0-first_name .form-right-col")->toArray();
        $a_expert_0_patr_name = $nokogiri->get("#a_expert-0-patr_name .form-right-col")->toArray();

        $a_organ_to_certification_rss_organ_to_certification_name = $nokogiri->get("#a_organ_to_certification-rss-organ_to_certification-name .form-right-col")->toArray();
        $a_organ_to_certification_rss_organ_to_certification_reg_number = $nokogiri->get("#a_organ_to_certification-rss-organ_to_certification-reg_number .form-right-col")->toArray();
        $a_organ_to_certification_rss_organ_to_certification_reg_date = $nokogiri->get("#a_organ_to_certification-rss-organ_to_certification-reg_date .form-right-col")->toArray();
        $a_organ_to_certification_rss_organ_to_certification_head_name = $nokogiri->get("#a_organ_to_certification-rss-organ_to_certification-head_name .form-right-col")->toArray();
        $a_organ_to_certification_rss_organ_to_certification_address = $nokogiri->get("#a_organ_to_certification-rss-organ_to_certification-address .form-right-col")->toArray();
        $a_organ_to_certification_rss_organ_to_certification_address_actual = $nokogiri->get("#a_organ_to_certification-rss-organ_to_certification-address_actual .form-right-col")->toArray();
        $a_organ_to_certification_rss_organ_to_certification_phone = $nokogiri->get("#a_organ_to_certification-rss-organ_to_certification-phone .form-right-col")->toArray();
        $a_organ_to_certification_rss_organ_to_certification_fax = $nokogiri->get("#a_organ_to_certification-rss-organ_to_certification-fax .form-right-col")->toArray();
        $a_organ_to_certification_rss_organ_to_certification_email = $nokogiri->get("#a_organ_to_certification-rss-organ_to_certification-email .form-right-col")->toArray();

        $cgroup_apps_table_standart = $nokogiri->get("#cgroup-apps-table_standart")->toArray();
        $a_table_standart_number = $nokogiri->get("#a_table_standart_number .form-right-col")->toArray();
        if ( empty($a_table_standart_number) )
        {
            $a_table_standart_number = $nokogiri->get("#a_free_form_number .form-right-col")->toArray();
        }
        $designation = $nokogiri->get("div[fsa-class=rss-table_standart div[fsa-id=designation] .form-right-col")->toArray();
        $name = $nokogiri->get("div[fsa-class=rss-table_standart div[fsa-id=name] .form-right-col")->toArray();
        $confirmation_requirements = $nokogiri->get("div[fsa-class=rss-table_standart div[fsa-id=confirmation_requirements] .form-right-col")->toArray();
        $rss_table_standart_designation = '';
        $rss_table_standart_name = '';
        $rss_table_standart_confirmation_requirements = '';
        foreach ( $designation as $k => $v )
        {
            $rss_table_standart_designation .= @$designation[$k]['__ref']->nodeValue.'|';
            $rss_table_standart_name .= @$name[$k]['__ref']->nodeValue.'|';
            $rss_table_standart_confirmation_requirements .= @$confirmation_requirements[$k]['__ref']->nodeValue.'|';
        }

        $a_reg_number = $nokogiri->get("#a_reg_number .form-right-col")->toArray();
        $a_blank_number = $nokogiri->get("#a_blank_number .form-right-col")->toArray();
        $cis_date_finish = $nokogiri->get("#cis_date_finish")->toArray();

        $data[] = [
            'STATUS' => @$source['param']['STATUS'],
            'CERT_NUM' => @$source['param']['CERT_NUM'],
            'CERF_RF' => trim(@$CERF_RF[0]['__ref']->nodeValue),

            'ORG_TYPE' => trim(@$ORG_TYPE[0]['__ref']->nodeValue),
            'MANUFACTURER_TYPE' => trim(@$MANUFACTURER_TYPE[0]['__ref']->nodeValue),

            'O_APPLICANT_TYPE' => trim(@$O_APPLICANT_TYPE[0]['__ref']->nodeValue),
            'DECLARANT' => trim(@$DECLARANT[0]['__ref']->nodeValue),
            'O_DIRECTOR_NAME' => trim(@$O_DIRECTOR_NAME[0]['__ref']->nodeValue),
            'O_ADDRESS' => trim(@$O_ADDRESS[0]['__ref']->nodeValue),
            'O_PHONE' => trim(@$O_PHONE[0]['__ref']->nodeValue),
            'O_FAX' => trim(@$O_FAX[0]['__ref']->nodeValue),
            'O_EMAIL' => trim(@$O_EMAIL[0]['__ref']->nodeValue),
            'O_OGRN' => trim(@$O_OGRN[0]['__ref']->nodeValue),

            'MANUFACTURER' => trim(@$MANUFACTURER[0]['__ref']->nodeValue),
            'M_ADDRESS' => trim(@$M_ADDRESS[0]['__ref']->nodeValue),
            'M_PHONE' => trim(@$M_PHONE[0]['__ref']->nodeValue),
            'M_FAX' => trim(@$M_FAX[0]['__ref']->nodeValue),
            'M_EMAIL' => trim(@$M_EMAIL[0]['__ref']->nodeValue),
            'M_OGRN' => trim(@$M_OGRN[0]['__ref']->nodeValue),

            'DOCUMENT_INFO' => trim(@$DOCUMENT_INFO[0]['__ref']->nodeValue),
            'BASIC_FOR_CERTIFICATE' => trim(@$BASIC_FOR_CERTIFICATE[0]['__ref']->nodeValue),

            'REG_NUMBER' => trim(@$REG_NUMBER[0]['__ref']->nodeValue),
            'TESTING_LAB_0_NAME' => trim(@$TESTING_LAB_0_NAME[0]['__ref']->nodeValue),
            'DATE_REG' => trim(@$DATE_REG[0]['__ref']->nodeValue),
            'OTHER_DOCUMENT_INFO' => trim(@$OTHER_DOCUMENT_INFO[0]['__ref']->nodeValue),

            'OBJECT_TYPE_CERT' => trim(@$OBJECT_TYPE_CERT[0]['__ref']->nodeValue),
            'PRODUCT_TYPE' => trim(@$PRODUCT_TYPE[0]['__ref']->nodeValue),
            'PRODUCTION' => trim(@$PRODUCTION[0]['__ref']->nodeValue),
            'PRODUCTION_INFO' => trim(@$PRODUCTION_INFO[0]['__ref']->nodeValue),
            'OKPD2' => trim(@$OKPD2[0]['__ref']->nodeValue)."|".trim(@$OKPD2_TEXT[0]['__ref']->nodeValue),
            'TN_VED' => trim(@$TN_VED[0]['__ref']->nodeValue)."|".trim(@$TN_VED_TEXT[0]['__ref']->nodeValue),
            'PRODUCT_INFO_EXT' => trim(@$PRODUCT_INFO_EXT[0]['__ref']->nodeValue),

            'REGLAMENT' => trim(@$REGLAMENT[0]['__ref']->nodeValue),
            'PROD_DOC_ISSUED' => trim(@$PROD_DOC_ISSUED[0]['__ref']->nodeValue),

            'NOTATION_NATIONAL_STANDART' => trim(@$NOTATION_NATIONAL_STANDART[0]['__ref']->nodeValue),

            'a_expert-0-last_name' => trim(@$a_expert_0_last_name[0]['__ref']->nodeValue),
            'a_expert-0-first_name' => trim(@$a_expert_0_first_name[0]['__ref']->nodeValue),
            'a_expert-0-patr_name' => trim(@$a_expert_0_patr_name[0]['__ref']->nodeValue),

            'organ_to_certification-name' => trim(@$a_organ_to_certification_rss_organ_to_certification_name[0]['__ref']->nodeValue),
            'organ_to_certification-reg_number' => trim(@$a_organ_to_certification_rss_organ_to_certification_reg_number[0]['__ref']->nodeValue),
            'organ_to_certification-reg_date' => trim(@$a_organ_to_certification_rss_organ_to_certification_reg_date[0]['__ref']->nodeValue),
            'organ_to_certification-head_name' => trim(@$a_organ_to_certification_rss_organ_to_certification_head_name[0]['__ref']->nodeValue),
            'organ_to_certification-address' => trim(@$a_organ_to_certification_rss_organ_to_certification_address[0]['__ref']->nodeValue),
            'organ_to_certification-address_actual' => trim(@$a_organ_to_certification_rss_organ_to_certification_address_actual[0]['__ref']->nodeValue),
            'organ_to_certification-phone' => trim(@$a_organ_to_certification_rss_organ_to_certification_phone[0]['__ref']->nodeValue),
            'organ_to_certification-fax' => trim(@$a_organ_to_certification_rss_organ_to_certification_fax[0]['__ref']->nodeValue),
            'organ_to_certification-email' => trim(@$a_organ_to_certification_rss_organ_to_certification_email[0]['__ref']->nodeValue),

            'cgroup-apps-table_standart' => empty($cgroup_apps_table_standart) ? 0 : 1,
            'a_table_standart_number' => trim(@$a_table_standart_number[0]['__ref']->nodeValue),
            'rss-table_standart-designation' => trim(@$rss_table_standart_designation, '|'),
            'rss-table_standart-name' => trim(@$rss_table_standart_name, '|'),
            'rss-table_standart-confirmation_requirements' => trim(@$rss_table_standart_confirmation_requirements, '|'),

            'a_reg_number' => trim(@$a_reg_number[0]['__ref']->nodeValue),
            'a_blank_number' => trim(@$a_blank_number[0]['__ref']->nodeValue),
            'ISSUE_DATE' => @$source['param']['ISSUE_DATE'],
            'END_DATE' => @$source['param']['END_DATE'],
            'cis_date_finish' => empty($cis_date_finish) ? 0 : 1,
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
