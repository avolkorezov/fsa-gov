<?php

namespace App\Donors;

use App\Http\Controllers\LoggerController;
use App\Models\ProxyList;
use ParseIt\_String;
use ParseIt\nokogiri;
use App\Donors\ParseIt\simpleParser;
use ParseIt\ParseItHelpers;

Class Rao_rf_pub_new extends simpleParser {

    public $data = [];
    public $reload = [];
    public $project = 'rao_rf_pub';
    public $project_link = 'https://pub.fsa.gov.ru/ral';
    public $api = 'https://pub.fsa.gov.ru/api/v1/ral/common/showcases/get';
    public $cache = false;
    public $proxy = false;
    public $cookieFile = '';
    public $version_id = 1;
    public $donor = 'Rao_rf_pub_new';
    protected $token = '';
    protected $session = '';
    private $decl_status = [
        6 => 'Действует',
        14 => 'Прекращен',
        15 => 'Приостановлен',
        3 => 'Возобновлен',
        1 => 'Архивный',
        10 => 'Направлено уведомление о прекрощении',
        5 => 'Выдано предписание',
    ];

    function __construct()
    {
        $this->cookieFile = __DIR__.'/cookie/'.class_basename(get_class($this)).'/'.class_basename(get_class($this)).'.txt';
        $this->login();
    }

    public function login($opt = [])
    {
        $opt['post'] = '{"username":"anonymous","password":"hrgesf7HDR67Bd"}';
        $opt['returnHeader'] = true;
        $opt['refer'] = 'https://pub.fsa.gov.ru/ral';
        $opt['ssl'] = 1;
        $opt['headers'] = [
//            "Accept: application/json, text/plain, */*",
            "Authorization: Bearer null",
            "Content-Type: application/json",
        ];
        $content = $this->loadUrl('https://pub.fsa.gov.ru/login', $opt);

        if (preg_match('%Authorization\: Bearer ([^\n]+)\n%uis', $content['data'], $match))
        {
            $token = trim($match[1]);
            $opt['headers'] = [
                "Authorization: Bearer {$token}",
            ];
            unset($opt['post']);
            $content = $this->loadUrl("https://pub.fsa.gov.ru/lk/api/account", $opt);
            $content = $this->loadUrl("https://pub.fsa.gov.ru/token/is/actual/{$token}", $opt);
            $content = $this->loadUrl("https://pub.fsa.gov.ru/api/v1/ral/common/account", $opt);
            if (preg_match('%Set-Cookie\: ([^\n]+)\n%uis', $content['data'], $match))
            {
                $session = trim($match[1]);
                $opt['headers'] = [
                    "Authorization: Bearer {$token}",
                    "Cookie: JSESSIONID={$session}",
                    'Content-Type: application/json'
                ];
                $opt['post'] = '{"sort":"id","attrs":[],"columns":[{"names":["name"],"search":"Российская"}],"offset":0,"limit":50}';
//                $content = $this->loadUrl("https://pub.fsa.gov.ru/nsi/api/oksm/get", $opt);
//                print_r($content);die();
                $this->setSession($session);
                $this->setToken($token);
            }
        }
    }

    public function getSources($opt = [])
    {
        $sources = [];
        $opt['refer'] = $this->project_link;
        $opt['origin'] = 'https://pub.fsa.gov.ru';
        $opt['host'] = 'pub.fsa.gov.ru';
//        $opt['ajax'] = true;
        $opt['json'] = true;
//        $opt['returnHeader'] = true;
        $opt['ssl'] = 1;
        $opt['headers'] = [
            "Authorization: Bearer {$this->getToken()}",
//            "Cookie: JSESSIONID={$this->getSession()}",
            'Content-Type: application/json'
        ];

        $perPage = 10000;
//        $perPage = 10;
        $currPage = 0;
        do
        {
            $nextPage = $currPage + 1;
            $opt['post'] = "{\"columns\":[],\"sort\":[\"-id\"],\"limit\":{$perPage},\"offset\":{$currPage}}";
            $api = $this->loadUrl($this->api, $opt);
            if ( isset($api->total) )
            {
                $countPage = ceil($api->total / $perPage);
                if ( $countPage-1 > $currPage )
                {
                    $currPage++;
                }
            }
            else
            {
                return $sources;
            }
            if (isset($api->items) && is_array($api->items) && !empty($api->items))
            {
                foreach ($api->items as $k => $item)
                {
                    $href = "https://pub.fsa.gov.ru/api/v1/ral/common/companies/".$item->id;
                    $hash = md5($href);
                    $sources[$hash]= [
                        'hash' => $hash,
                        'name' => '',
                        'source' => $href,
                        'donor_class_name' => $this->donor,
                        'version' => 2,
                        'param' => [
                            'id' => @$item->id,
                            'STATUS' => $item->nameStatus,
                            'DECL_NUM' => @$item->regNumber,
                        ]
                    ];
                }
            }
//            if ($currPage === 1)
//            {
//                break;
//            }
        }
        while( $nextPage === $currPage );
        return $sources;
    }

    public function getData($url, $source = [])
    {
        $data = false;
//        $url = preg_replace('%\d+$%uis', 1084, $url);
        $source['refer'] = $this->project_link;
        $source['origin'] = 'https://pub.fsa.gov.ru';
        $source['host'] = 'pub.fsa.gov.ru';
        $source['ajax'] = true;
        $source['json'] = true;
        $source['ssl'] = 1;
//        $source['returnHeader'] = true;
        $source['headers'] = [
            "Authorization: Bearer {$this->getToken()}",
            "Cookie: JSESSIONID={$this->getSession()}",
            'Content-Type: application/json'
        ];
        $api_common = $this->loadUrl($url, $source);

        $applicantType = [];
        if (isset($api_common->applicant->idType))
        {
            $applicantType[$api_common->applicant->idType] = $api_common->applicant->idType;
        }
        if (isset($api_common->manufacturer->idType))
        {
            $applicantType[$api_common->manufacturer->idType] = $api_common->manufacturer->idType;
        }

        $addressType = [];

        if (isset($api_common->applicant->addresses))
        {
            foreach ( $api_common->applicant->addresses as $address )
            {
                $addressType[$address->idType] = $address->idType;
            }
        }
        if (isset($api_common->manufacturer->addresses))
        {
            foreach ( $api_common->manufacturer->addresses as $address )
            {
                $addressType[$address->idType] = $address->idType;
            }
        }

        $oksm = [];
        $fiasAddrobj = [];
        $fullAddress = [];
        if (isset($api_common->addresses))
        {
            foreach ( $api_common->addresses as $address )
            {
                $addressType[$address->idType] = $address->idType;
                $oksm[$address->idCodeOksm] = $address->idCodeOksm;
                $fiasAddrobj[$address->idSubject] = $address->idSubject;
                $fullAddress[] = $address->fullAddress;
            }
        }

        $guApActivityType = [];
        if (isset($api_common->accreditation->accredScopeUnstructList->accredScopeUnstruct[0]->businessLine))
        {
            foreach ( $api_common->accreditation->accredScopeUnstructList->accredScopeUnstruct[0]->businessLine as $businessLine )
            {
                $guApActivityType[] = $businessLine;
            }
        }

        $dicNormDoc = [];
        if (isset($api_common->accreditation->accredScopeUnstructList->accredScopeUnstruct[0]->regulationsTs))
        {
            foreach ( $api_common->accreditation->accredScopeUnstructList->accredScopeUnstruct[0]->regulationsTs as $regulationsTs )
            {
                $dicNormDoc[] = $regulationsTs;
            }
        }

        $items = [];
        isset($applicantType) && !empty($applicantType) ? $items[] = "\"applicantType\":[{\"id\":[".implode(',', $applicantType)."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($api_common->applicant->idLegalForm) ? $items[] = "\"legalForm\":[{\"id\":[".$api_common->applicant->idLegalForm."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($addressType) && !empty($addressType) ? $items[] = "\"addressType\":[{\"id\":[".implode(',', $addressType)."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($api_common->idType) ? $items[] = "\"guApType\":[{\"id\":[".$api_common->idType."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($api_common->idStatus) ? $items[] = "\"status\":[{\"id\":[".$api_common->idStatus."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($oksm) && !empty($oksm) ? $items[] = "\"oksm\":[{\"id\":[".implode(',', $oksm)."],\"fields\":[\"id\",\"masterId\",\"shortName\"]}]" : '';
        isset($fiasAddrobj) && !empty($fiasAddrobj) ? $items[] = "\"fiasAddrobj\":[{\"id\":[\"".implode('","', $fiasAddrobj)."\"],\"fields\":[\"id\",\"masterId\",\"name\",\"offname\",\"shortname\",\"aolevel\"]}]" : '';
        isset($guApActivityType) && !empty($guApActivityType) ? $items[] = "\"guApActivityType\":[{\"id\":[".implode(',', $guApActivityType)."],\"fields\":[\"id\",\"masterId\",\"shortName\", \"name\"]}]" : '';
        isset($dicNormDoc) && !empty($dicNormDoc) ? $items[] = "\"dicNormDoc\":[{\"id\":[".implode(',', $dicNormDoc)."],\"fields\":[\"id\",\"masterId\",\"name\",\"docDesignation\"]}]" : '';

        $source['post'] = '{"items":{'.implode(',', $items).'}}';
        $api_multi = $this->loadUrl('https://pub.fsa.gov.ru/nsi/api/multi', $source);
//        print_r($api_multi);die();

        $source['post'] = '{"sort":"id","attrs":[],"columns":[{"names":["name"],"search":"Российская"}],"offset":0,"limit":50}';
        $api_oksm = $this->loadUrl('https://pub.fsa.gov.ru/nsi/api/oksm/get', $source);
//        print_r($api_oksm);die();

        $applicant_contacts_phones = [];
        $applicant_contacts_emails = [];
        $applicant_contacts_faxes = [];
        if ( isset($api_common->contacts) )
        {
            foreach ( $api_common->contacts as $contact)
            {
                switch ($contact->idType){
                    case 1 :
                        $applicant_contacts_phones[] = $contact->value;
                        break;
                    case 4 :
                        $applicant_contacts_emails[] = $contact->value;
                        break;
                    case 3 :
                        $applicant_contacts_faxes[] = $contact->value;
                        break;
                }

            }
        }

        $z_contacts_phones = [];
        $z_contacts_emails = [];
        $z_contacts_faxes = [];
        if ( isset($api_common->applicant->contacts) )
        {
            foreach ( $api_common->contacts as $contact)
            {
                switch ($contact->idType){
                    case 1 :
                        $z_contacts_phones[] = $contact->value;
                        break;
                    case 4 :
                        $z_contacts_emails[] = $contact->value;
                        break;
                    case 3 :
                        $z_contacts_faxes[] = $contact->value;
                        break;
                }

            }
        }

        if ( isset($api_multi->applicantType) )
        {
            foreach (@$api_multi->applicantType as $applicant)
            {
                if ( $applicant->id === $api_common->applicant->idType )
                {
                    $applicantType = $applicant->name;
                }
            }
        }

        $TYPE_NAPRAVLENIYA = [];
        if ( isset($api_multi->guApActivityType) )
        {
            foreach (@$api_multi->guApActivityType as $guApActivityType)
            {
                $TYPE_NAPRAVLENIYA[] = "{$guApActivityType->shortName} ({$guApActivityType->name})";
            }
        }

        $tr = [];
        if ( isset($api_multi->dicNormDoc) )
        {
            foreach (@$api_multi->dicNormDoc as $dicNormDoc)
            {
                $tr[] = "{$dicNormDoc->docDesignation} {$dicNormDoc->name}";
            }
        }

        $data[] = [
            'IN_REESTR' => isset($api_common->insertNationalPart) && $api_common->insertNationalPart == 1 ? 'Да' : 'Нет',
            'SHORT_NAME' => @$api_common->shortName,
            'DOL_RUC_ACC_LICA' => @$api_common->headPost,
            'TYPE_NAPRAVLENIYA' => implode(';', $TYPE_NAPRAVLENIYA),
            'NUM_RESHENIYA' => @$api_common->accreditation->decisionNumber,
            'EXPERT_FIO' => @$api_common->accreditation->expertGroup->expertFio,
            'REESTR_NUM' => @$api_common->accreditation->expertGroup->expertRegNumber,
            'EXPERT_ORG' => @$api_common->accreditation->expertGroup->expertOrganizationName,
            'EXPERT_TEH' => @$api_common->accreditation->expertGroup->technicalExperts[0]->fio,

            'Z_TYPE' => @$applicantType,
            'Z_FORM' => @$api_common->applicant->nameLegalForm,
            'Z_FULL_NAME' => @$api_common->applicant->fullName,
            'Z_NAME' => @$api_common->applicant->shortName,
            'Z_INN' => @$api_common->applicant->inn,
            'Z_KPP' => @$api_common->applicant->kpp,
            'Z_OGRN' => @$api_common->applicant->ogrn,
            'Z_ADRES' => @$api_common->applicant->addresses[0]->fullAddress,
            'Z_ORGAN' => @$api_common->applicant->taxAuthorityName,
            'Z_DATA_UCHET' => @$api_common->applicant->taxAuthorityRegDate,
            'Z_FIO_RUK' => @$api_common->applicant->person->surname." ".@$api_common->applicant->person->name." ".@$api_common->applicant->person->patronymic,
            'Z_DOLJNOST_RUK' => @$api_common->applicant->headPost,
            'Z_PHONE' => implode(';', $z_contacts_phones),
            'Z_FAX' => implode(';', $z_contacts_faxes),
            'Z_EMAIL' => implode(';', $z_contacts_emails),

            'STATUS' => @$api_multi->status[0]->name,
            'CERT_NUM' => @$api_common->regNumbers[0]->regNumber,
            'REG_DATE' => @$api_common->regDate,
            'END_DATE' => @$api_common->endDate,
            'STATUS_DATE' => "",

            'NUM_DECISION' => "",
            'DATE_DECISION' => "",
            'TYPE_DECLARANT' => @$api_multi->guApType[0]->name,

            'FULL_NAME' => @$api_common->fullName,
            'FIO' => "",
            'ADDRESS' => "",

            'PHONE' => implode(';', $applicant_contacts_phones),
            'FAX' => implode(';', $applicant_contacts_faxes),
            'EMAIL' => implode(';', $applicant_contacts_emails),
            'GOS_REG_YR' => "",
            'INN' => "",

            'FIO_RUC_ACC_LICA' => @$api_common->headPerson->surname." ".@$api_common->headPerson->name." ".@$api_common->headPerson->patronymic,
            'ADRESS_ACC_AREA' => implode(";", $fullAddress),

            'ACC_AREA' => "",
            'TR' => implode(';' , $tr),
            'OKPD' => @$api_common->accreditation->accredScopeUnstructList->accredScopeUnstruct[0]->codeOkpd2,
            'OKP' => @$api_common->accreditation->accredScopeUnstructList->accredScopeUnstruct[0]->codeOkp,
            'OKUN' => @$api_common->accreditation->accredScopeUnstructList->accredScopeUnstruct[0]->codeOkpn,
            'TN_VAD' => @$api_common->accreditation->accredScopeUnstructList->accredScopeUnstruct[0]->codeTnVed,
            'SKAN' => "",

            'NC_TN_VAD_EAS' => "",
            'NC_TR_EAS' => "",
            'NC_ACC_AREA' => "",

            'NUM_DECISION_INCR_ACC_AREA' => "",
            'DATE_DECISION_INCR_ACC_AREA' => "",
            'TR_INCR_ACC_AREA' => "",
            'TN_VAD_INCR_ACC_AREA' => "",
            'DESC_ACC_AREA' => @$api_common->accreditation->accredScopeUnstructList->accredScopeUnstruct[0]->oaDescription,
        ];
//        print_r($data);die();

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

    public function getToken()
    {
        return $this->token;
    }

    private function setToken($token)
    {
        $this->token = $token;
    }

    public function getSession()
    {
        return $this->session;
    }

    private function setSession($session)
    {
        $this->session = $session;
    }
}
