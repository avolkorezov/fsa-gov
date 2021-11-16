<?php

namespace App\Donors;

use ParseIt\nokogiri;
use App\Donors\ParseIt\simpleParser;

Class Rds_ts_pub_new extends simpleParser {

    public $data = [];
    public $reload = [];
    public $project = 'rds_ts_pub';
    public $project_link = 'https://pub.fsa.gov.ru/rds/declaration';
    public $source = 'https://pub.fsa.gov.ru/api/v1/rds/common/declarations/get';
    public $cache = false;
    public $proxy = false;
    public $cookieFile = '';
    public $version_id = 1;
    public $donor = 'Rds_ts_pub_new';
    protected $token = '';
    protected $session = '';
    protected $sessionNode = '';
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
        $opt['post'] = "{\"username\":\"anonymous\",\"password\":\"hrgesf7HDR67Bd\"}";
        $opt['returnHeader'] = true;
        $opt['refer'] = 'https://pub.fsa.gov.ru/rds/declaration';
        $opt['origin'] = 'https://pub.fsa.gov.ru';
        $opt['host'] = 'pub.fsa.gov.ru';
        $opt['ajax'] = true;
        $opt['headers'] = [
            'Content-Type: application/json',
            'Authorization: Bearer null',
        ];
        $content = $this->loadUrl('https://pub.fsa.gov.ru/login', $opt);
        sleep(1);

        if (preg_match('%Authorization\: Bearer ([^\n]+)\n%uis', $content['data'], $match))
        {
            $token = trim($match[1]);
            $opt['headers'] = [
                "Authorization: Bearer {$token}",
            ];
            unset($opt['post']);
            $content = $this->loadUrl("https://pub.fsa.gov.ru/lk/api/account", $opt);
            sleep(1);
            if (preg_match('%Set-Cookie\:.*?JSESSIONID\=([^\;]+)\;%uis', $content['data'], $match))
            {
                $session = trim($match[1]);
                $this->setSessionNode($session);
            }

            $content = $this->loadUrl("https://pub.fsa.gov.ru/api/v1/rds/common/account", $opt);
            sleep(1);
            if (preg_match('%Set-Cookie\:.*?JSESSIONID\=([^\;]+)\;%uis', $content['data'], $match))
            {
                $session = trim($match[1]);
                $this->setSession($session);
                $this->setToken($token);
            }

            $opt['headers'] = [
                "Authorization: Bearer {$token}",
                "Cookie: JSESSIONID={$this->getSessionNode()}",
                'Content-Type: application/json'
            ];

            $opt['post'] = "{\"sort\":\"id\",\"attrs\":[],\"offset\":null,\"limit\":350}";
            $api_oksm = $this->loadUrl('https://pub.fsa.gov.ru/nsi/api/oksm/get', $opt);
            sleep(1);

            $opt['post'] = "{\"sort\":\"id\",\"attrs\":[],\"columns\":[{\"names\":[\"name\"],\"search\":\"Российская\"}],\"offset\":0,\"limit\":50}";
            $api_oksm = $this->loadUrl('https://pub.fsa.gov.ru/nsi/api/oksm/get', $opt);
            sleep(1);

            unset($opt['post']);

            $api_oksm = $this->loadUrl('https://pub.fsa.gov.ru/api/v1/rds/common/identifiers', $opt);
            sleep(1);

            $api_oksm = $this->loadUrl('https://pub.fsa.gov.ru/lk/api/account/card', $opt);
            sleep(1);
        }
    }

    public function getSources($opt = [])
    {
        $sources = [];
//        $regDate = preg_match("%\d{1,2}\.\d{1,2}\.\d{4}%uis", @$opt['begin']) ? date('Y-m-d',strtotime(@$opt['begin'])) : date('Y-m-d', time()-(31*24*60*60));
        $regDate = preg_match("%\d{1,2}\.\d{1,2}\.\d{4}%uis", @$opt['begin']) ? date('Y-m-d',strtotime(@$opt['begin'])) : null;
        $endDate = preg_match("%\d{1,2}\.\d{1,2}\.\d{4}%uis", @$opt['end']) ? date('Y-m-d',strtotime(@$opt['end'])) : null;
        $type = '';
        if (isset($opt['type'])) {
            $type = $opt['type'];
        }
        $opt['refer'] = 'https://pub.fsa.gov.ru/rds/declaration';
        $opt['origin'] = 'https://pub.fsa.gov.ru';
        $opt['host'] = 'pub.fsa.gov.ru';
        $opt['ajax'] = true;
        $opt['json'] = true;
//        $opt['returnHeader'] = true;
        $opt['headers'] = [
            "Authorization: Bearer {$this->getToken()}",
            "Cookie: JSESSIONID={$this->getSession()}",
            'Content-Type: application/json',
            'sec-ch-ua: "Chromium";v="92", " Not A;Brand";v="99", "Google Chrome";v="92"',
            'sec-ch-ua-mobile: ?0',
            'Sec-Fetch-Dest: empty',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Site: same-origin',
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36'
        ];

        $perPage = 100;
//        $perPage = 10;
        $currPage = $opt['currPage'] ?? 0;
        $i = 0;
        do {
            $i++;
            $nextPage = $currPage + 1;
            $opt['post'] = "{\"size\":{$perPage},\"page\":{$currPage},\"filter\":{\"status\":[],\"idDeclType\":[{$type}],\"idCertObjectType\":[],\"idProductType\":[],\"idGroupRU\":[],\"idGroupEEU\":[],\"idTechReg\":[],\"idApplicantType\":[],\"regDate\":{\"minDate\":\"{$regDate}\",\"maxDate\":\"{$endDate}\"},\"endDate\":{\"minDate\":null,\"maxDate\":null},\"columnsSearch\":[],\"idProductEEU\":[],\"idProductRU\":[],\"idDeclScheme\":[],\"awaitForApprove\":null,\"editApp\":null,\"violationSendDate\":null},\"columnsSort\":[{\"column\":\"declDate\",\"sort\":\"DESC\"}]}";
//            $opt['post'] = "{\"size\":100,\"page\":0,\"filter\":{\"status\":[],\"idDeclType\":[],\"idCertObjectType\":[],\"idProductType\":[],\"idGroupRU\":[],\"idGroupEEU\":[],\"idTechReg\":[],\"idApplicantType\":[],\"regDate\":{\"minDate\":\"2000-10-20\",\"maxDate\":null},\"endDate\":{\"minDate\":null,\"maxDate\":\"2018-10-28\"},\"columnsSearch\":[],\"idProductEEU\":[],\"idProductRU\":[],\"idDeclScheme\":[],\"awaitForApprove\":null,\"editApp\":null,\"violationSendDate\":null},\"columnsSort\":[{\"column\":\"declDate\",\"sort\":\"DESC\"}]}";
            $api_decl = $this->loadUrl('https://pub.fsa.gov.ru/api/v1/rds/common/declarations/get', $opt);
//            print_r($api_decl);die();
            if (isset($api_decl->total)) {
                $countPage = ceil($api_decl->total / $perPage);
                print_r(['countPage' => $countPage]);
                if ($countPage-1 > $currPage) {
                    $currPage++;
                }
            } else {
                return $sources;
            }
            if (isset($api_decl->items) && is_array($api_decl->items) && !empty($api_decl->items)) {
                foreach ($api_decl->items as $k => $item) {
//                    print_r($item);die();
                    $href = "https://pub.fsa.gov.ru/api/v1/rds/common/declarations/".$item->id;
                    $hash = md5($href);
                    $sources[$hash] = [
                        'hash' => $hash,
                        'name' => '',
                        'source' => $href,
                        'donor_class_name' => $this->donor,
                        'version' => 2,
                        'param' => [
                            'id' => @$item->id,
                            'STATUS' => isset($item->idStatus) && isset($this->decl_status[$item->idStatus]) ? $this->decl_status[$item->idStatus] : '',
                            'DECL_NUM' => @$item->number,
                        ]
                    ];
                }
            }
            if ($i >= 10) {
                break;
            }
            sleep(8);
        } while(true);

        return $sources;
    }

    public function getData($url, $source = [])
    {
        $data = false;
//        $url = preg_replace('%\d+$%uis', 16253062, $url);
//        print_r($url);die();
        $source['refer'] = 'https://pub.fsa.gov.ru/rds/declaration';
//        $source['refer'] = preg_replace('%\d+$%uis', 13547646, $source['refer']);
        $source['origin'] = 'https://pub.fsa.gov.ru';
        $source['host'] = 'pub.fsa.gov.ru';
        $source['ajax'] = true;
        $source['json'] = true;
        $source['headers'] = [
            "Authorization: Bearer {$this->getToken()}",
            "Cookie: JSESSIONID={$this->getSession()}",
            'lkId: ',
            'orgId: ',
        ];
//        print_r($url);die();
        $api_common = $this->loadUrl($url, $source);
        sleep(1);
//        print_r($api_common);die();
        $addressType = [];
        if (isset($api_common->applicant->addresses))
        {
            foreach ( $api_common->applicant->addresses as $address )
            {
                $addressType[$address->idAddrType] = $address->idAddrType;
            }
        }
        if (isset($api_common->manufacturer->addresses))
        {
            foreach ( $api_common->manufacturer->addresses as $address )
            {
                $addressType[$address->idAddrType] = $address->idAddrType;
            }
        }
        $fiasAddrobj = [];
        if (isset($api_common->certificationAuthority->addresses))
        {
            foreach ( $api_common->certificationAuthority->addresses as $address )
            {
                $addressType[$address->idAddrType] = $address->idAddrType;
                $fiasAddrobj[$address->idSubject] = $address->idSubject;
            }
        }
        if (isset($api_common->testingLabs))
        {
            foreach ($api_common->testingLabs as $testingLab) {
                if (isset($testingLab->addresses))
                {
                    foreach ( $testingLab->addresses as $address )
                    {
                        $addressType[$address->idAddrType] = $address->idAddrType;
                    }
                }
                if (isset($testingLab->protocols) && is_array($testingLab->protocols)) {
                    foreach ($testingLab->protocols as $protocol) {
                        if (isset($testingLabsProtocol)) {
                            break;
                        }
                        $testingLabsProtocol = $protocol;
                        break;
                    }
                }
            }
        }

        $applicantType = [];
        if (isset($api_common->applicant->idLegalSubjectType))
        {
            $applicantType[$api_common->applicant->idLegalSubjectType] = $api_common->applicant->idLegalSubjectType;
        }
        if (isset($api_common->manufacturer->idLegalSubjectType))
        {
            $applicantType[$api_common->manufacturer->idLegalSubjectType] = $api_common->manufacturer->idLegalSubjectType;
        }

        $contactType = [];
        if (isset($api_common->applicant->contacts))
        {
            foreach ( $api_common->applicant->contacts as $contact )
            {
                $contactType[$contact->idContactType] = $contact->idContactType;
            }
        }
        if (isset($api_common->manufacturer->contacts))
        {
            foreach ( $api_common->manufacturer->contacts as $contact )
            {
                $contactType[$contact->idContactType] = $contact->idContactType;
            }
        }
        if (isset($api_common->certificationAuthority->contacts))
        {
            foreach ( $api_common->certificationAuthority->contacts as $contact )
            {
                $contactType[$contact->idContactType] = $contact->idContactType;
            }
        }
        $tnved = [];
        if (isset($api_common->product->identifications))
        {
            foreach ($api_common->product->identifications as $identification) {
                if (isset($identification->idTnveds))
                {
                    foreach ($identification->idTnveds as $idTnved) {
                        $tnved[$idTnved] = $idTnved;
                    }
                }
            }
        }
        $okpd2 = [];
        if (isset($api_common->product->identifications))
        {
            foreach ($api_common->product->identifications as $identification) {
                if (isset($identification->idOkpds))
                {
                    foreach ($identification->idOkpds as $idOkpds) {
                        $okpd2[$idOkpds] = $idOkpds;
                    }
                }
            }
        }
        $items = [];
        isset($api_common->idTechnicalReglaments) ? $items[] = "\"dicNormDoc\":[{\"id\":[".implode(',', $api_common->idTechnicalReglaments)."],\"fields\":[\"id\",\"masterId\",\"name\",\"docDesignation\"]}]" : '';
        isset($api_common->idDeclScheme) ? $items[] = "\"validationScheme2\":[{\"id\":[".$api_common->idDeclScheme."],\"fields\":[\"id\",\"masterId\",\"name\",\"validityTerm\",\"isSeriesProduction\",\"isBatchProduction\",\"isOneOffProduction\",\"isProductSampleTesting\",\"isBatchProductTesting\",\"isOneOffProductTesting\",\"isAccreditationLab\",\"isApplicantManufacturer\",\"isApplicantProvider\",\"isPresenceOfProxy\",\"isApplicantForeign\",\"isApplicantEeuMember\"]}]" : '';
        isset($api_common->idGroups) && isset($api_common->idTechnicalReglaments[0]) && $api_common->idTechnicalReglaments[0] === 11 ? $items[] = "\"techregProductListEEU\":[{\"id\":[\"".implode('","', $api_common->idGroups)."\"],\"fields\":[\"id\",\"masterId\",\"name\",\"techRegId\"]}]" : '';
        isset($api_common->idGroups) && isset($api_common->idTechnicalReglaments[0]) && $api_common->idTechnicalReglaments[0] === 56 ? $items[] = "\"techregProductListRU\":[{\"id\":[\"".implode('","', $api_common->idGroups)."\"],\"fields\":[\"id\",\"masterId\",\"name\",\"techRegId\"]}]" : '';
        isset($api_common->idObjectDeclType) ? $items[] = "\"validationObjectType\":[{\"id\":[".$api_common->idObjectDeclType."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($api_common->idDeclType) ? $items[] = "\"conformityDocType\":[{\"id\":[".$api_common->idDeclType."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($api_common->idStatus) ? $items[] = "\"status\":[{\"id\":[".$api_common->idStatus."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($addressType) && !empty($addressType) ? $items[] = "\"addressType\":[{\"id\":[".implode(',', $addressType)."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($api_common->applicant->idApplicantType) ? $items[] = "\"declarantType\":[{\"id\":[".$api_common->applicant->idApplicantType."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($applicantType) && !empty($applicantType) ? $items[] = "\"applicantType\":[{\"id\":[".implode(',', $applicantType)."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($contactType) && !empty($contactType) ? $items[] = "\"contactType\":[{\"id\":[".implode(',', $contactType)."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($fiasAddrobj) && !empty($fiasAddrobj) ? $items[] = "\"fiasAddrobj\":[{\"id\":[\"".implode('","', $fiasAddrobj)."\"],\"fields\":[\"id\",\"masterId\",\"name\",\"offname\",\"shortname\",\"aolevel\"]}]" : '';
        isset($tnved) && !empty($tnved) ? $items[] = "\"tnved\":[{\"id\":[".implode(',', $tnved)."],\"fields\":[\"id\",\"masterId\",\"name\",\"code\"]}]" : '';
        isset($okpd2) && !empty($okpd2) ? $items[] = "\"okpd2\":[{\"id\":[".implode(',', $okpd2)."],\"fields\":[\"id\",\"masterId\",\"name\",\"code\"]}]" : '';
//        print_r($api_common);die();
//        print_r($items);die();

        $source['headers'] = [
            "Authorization: Bearer {$this->getToken()}",
            "Cookie: JSESSIONID={$this->getSessionNode()}",
            'lkId: ',
            'orgId: ',
            'Content-Type: application/json',
        ];

//        $source['returnHeader'] = true;
        $source['json'] = true;
        $source['post'] = "{\"sort\":\"id\",\"attrs\":[],\"columns\":[{\"names\":[\"name\"],\"search\":\"Российская\"}],\"offset\":0,\"limit\":50}";
        unset($source['cookieFile']);
        sleep(1);
        $api_oksm = $this->loadUrl('https://pub.fsa.gov.ru/nsi/api/oksm/get', $source);

        $source['post'] = '{"items":{'.implode(',', $items).'}}';
        sleep(1);
        $api_multi = $this->loadUrl('https://pub.fsa.gov.ru/nsi/api/multi', $source);
//        print_r($api_multi);die();

        foreach ( $api_oksm->items as $item )
        {
            if ($api_common->product->idProductOrigin === $item->id)
            {
                $ProductOrigin = $item->shortName;
            }
        }
//        print_r($api_oksm);die();
//        if ($captcha = $nokogiri->get("input[name=captcha]")->toArray())
//        {
//            if ( !$this->entry_captcha($content, $href) )
//            {
//                return false;
//            }
//            unset($content, $nokogiri);
//            $content = $this->loadUrl($href, $source);
//            $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
//            $content = iconv('windows-1251', 'UTF-8', $content);
//            $content = ParseItHelpers::fixEncoding($content);
//            $content = ParseItHelpers::fixHeader($content);
//            $nokogiri = new nokogiri($content);
//            if ($captcha = $nokogiri->get("input[name=captcha]")->toArray())
//            {
//                return false;
//            }
//        }

        if ( isset($api_multi->validationObjectType) )
        {
            foreach ( $api_multi->validationObjectType as $objectType)
            {
                if ($objectType->id === $api_common->idObjectDeclType)
                {
                    $object_type_decl = $objectType->name;
                }
            }
        }
        if (isset($api_multi->okpd2))
        {
            foreach ( $api_multi->okpd2 as $okpd2)
            {
                if (@$api_common->product->identifications[0]->idOkpds[0] === $okpd2->id)
                {
                    $product_okpd2 = $okpd2;
                }
            }
        }
        if (isset($api_multi->conformityDocType))
        {
            foreach ( $api_multi->conformityDocType as $docType)
            {
                if ($api_common->idDeclType === $docType->id)
                {
                    $conformityDocType = $docType->name;
                }
            }
        }
        if ( isset($api_common->applicant->contacts) )
        {
            foreach ( $api_common->applicant->contacts as $contact)
            {
                switch ($contact->idContactType){
                    case 1 :
                        $applicant_contacts_phone = $contact->value;
                        break;
                    case 4 :
                        $applicant_contacts_email = $contact->value;
                        break;
                    case 3 :
                        $applicant_contacts_fax = $contact->value;
                        break;
                }

            }
        }
        if ( isset($api_multi->declarantType) )
        {
            foreach (@$api_multi->declarantType as $declarant)
            {
                if ( $declarant->id === $api_common->applicant->idLegalSubjectType )
                {
                    $declarantType = $declarant->name;
                }
            }
        }
        if ( isset($api_common->manufacturer->contacts) )
        {
            foreach ( $api_common->manufacturer->contacts as $contact)
            {
                switch ($contact->idContactType){
                    case 1 :
                        $manufacturer_contacts_phone = $contact->value;
                        break;
                    case 4 :
                        $manufacturer_contacts_email = $contact->value;
                        break;
                    case 3 :
                        $manufacturer_contacts_fax = $contact->value;
                        break;
                }

            }
        }
        if ( isset($api_common->certificationAuthority->addresses) )
        {
            foreach (@$api_common->certificationAuthority->addresses as $address)
            {
                switch ($address->idAddrType){
                    case 1 :
                        $certification_address_actual = $address->fullAddress;
                        break;
                    case 3 :
                        $certification_address = $address->fullAddress;
                        break;
                }
            }
        }
        if ( isset($api_common->certificationAuthority->contacts) )
        {
            foreach ( $api_common->certificationAuthority->contacts as $contact)
            {
                switch ($contact->idContactType){
                    case 1 :
                        $certification_contacts_phone = $contact->value;
                        break;
                    case 4 :
                        $certification_contacts_email = $contact->value;
                        break;
                    case 3 :
                        $certification_contacts_fax = $contact->value;
                        break;
                }

            }
        }
        if (isset($api_multi->techregProductListRU))
        {
            $techregProductList = $api_multi->techregProductListRU[0]->name;
        }
        elseif (isset($api_multi->techregProductListEEU))
        {
            $techregProductList = $api_multi->techregProductListEEU[0]->name;
        }
        $tech_reg = '';
        if (isset($api_multi->dicNormDoc) && (is_array($api_multi->dicNormDoc) || is_object($api_multi->dicNormDoc))) {
            foreach ($api_multi->dicNormDoc as $dicNormDoc)
            {
                $tech_reg .= $dicNormDoc->docDesignation . ' ' . $dicNormDoc->name. '|';
            }
        }
        $standards_des = '';
        $standards_name = '';
        if (isset($api_common->product->identifications)) {
            foreach ($api_common->product->identifications as $identification) {
                foreach ($identification->standards as $standard) {
                    $standards_des .= $standard->designation.'|';
                    $standards_name .= $standard->name.'|';
                }
            }
        }
        $standards_des = trim($standards_des, '|');
        $standards_name = trim($standards_name, '|');

        $product_ts_tn_ved = '';
        if (isset($api_multi->tnved)) {
            foreach ($api_multi->tnved as $tnved) {
                $product_ts_tn_ved .= trim(@$tnved->code . ' '. @$tnved->name).'|';
            }
        }
        $product_ts_tn_ved = trim($product_ts_tn_ved, '|');

        $data[] = [
            'STATUS' => @$api_multi->status[0]->name,
            'DECL_NUM' => @$api_common->number,

            'a_cert_type-cert_ts' => trim(@$a_cert_type_cert_ts[0]['__ref']->nodeValue),
            'a_cert_ts_type-ts' => trim(@$a_cert_ts_type_ts[0]['__ref']->nodeValue),

            'a_applicant_org_type-ul' => @$api_multi->applicantType[0]->name,
            'a_manufacturer_type-ul' => trim(@$a_manufacturer_type_iul[0]['__ref']->nodeValue),

            'a_applicant_info-rds-app_legal_person-applicant_type' => isset($declarantType) ? $declarantType : '',
            'a_applicant_info-rds-app_legal_person-name' => @$api_common->applicant->fullName,
            'a_applicant_info-rds-app_legal_person-director_name' => @$api_common->applicant->surname." ".@$api_common->applicant->firstName." ".@$api_common->applicant->patronymic,
            'a_applicant_info-rds-app_legal_person-address' => @$api_common->applicant->addresses[0]->fullAddress,
            'a_applicant_info-rds-app_legal_person-phone' => @$applicant_contacts_phone,
            'a_applicant_info-rds-app_legal_person-fax' => @$applicant_contacts_fax,
            'a_applicant_info-rds-app_legal_person-email' => @$applicant_contacts_email,
            'a_applicant_info-rds-app_legal_person-ogrn' => trim(@$api_common->applicant->ogrn),

            'a_manufacturer_info-rds-man_foreign_legal_person-name' => trim(@$a_manufacturer_info_rss_man_foreign_legal_person_name[0]['__ref']->nodeValue),
            'a_manufacturer_info-rds-man_foreign_legal_person-address' => trim(@$a_manufacturer_info_rss_man_foreign_legal_person_address[0]['__ref']->nodeValue),
            'a_manufacturer_info-rds-man_foreign_legal_person-phone' => trim(@$a_manufacturer_info_rss_man_foreign_legal_person_phone[0]['__ref']->nodeValue),
            'a_manufacturer_info-rds-man_foreign_legal_person-fax' => trim(@$a_manufacturer_info_rss_man_foreign_legal_person_fax[0]['__ref']->nodeValue),
            'a_manufacturer_info-rds-man_foreign_legal_person-email' => trim(@$a_manufacturer_info_rss_man_foreign_legal_person_email[0]['__ref']->nodeValue),

            'a_manufacturer_info-rds-man_legal_person-name' => @$api_common->manufacturer->fullName,
            'a_manufacturer_info-rds-man_legal_person-address' => @$api_common->manufacturer->addresses[0]->fullAddress,
            'a_manufacturer_info-rds-man_legal_person-address_actual' => trim(@$a_manufacturer_info_rss_man_legal_person_address_actual[0]['__ref']->nodeValue),
            'a_manufacturer_info-rds-man_legal_person-phone' => @$manufacturer_contacts_phone,
            'a_manufacturer_info-rds-man_legal_person-fax' => @$manufacturer_contacts_fax,
            'a_manufacturer_info-rds-man_legal_person-email' => @$manufacturer_contacts_email,
            'a_manufacturer_info-rds-man_legal_person-ogrn' => trim(@$api_common->manufacturer->ogrn),

            'cert_doc_issued-document_info' => trim(@$a_cert_doc_issued_rss_cert_doc_issued_document_info[0]['__ref']->nodeValue),
            'cert_doc_issued-certification_scheme' => @$api_multi->validationScheme2[0]->name,
            'cert_doc_issued-testing_lab-1-basis_for_certificate' => trim(@$a_cert_doc_issued_cert_doc_issued_testing_lab_basis_for_certificate[0]['__ref']->nodeValue),

            'cert_doc_issued-testing_lab-0-reg_number' => @$api_common->testingLabs[0]->regNumber,
            'cert_doc_issued-testing_lab-0-name' => @$api_common->testingLabs[0]->fullName,
            'cert_doc_issued-testing_lab-0-date_reg' => @$api_common->testingLabs[0]->beginDate,
            'cert_doc_issued-testing_lab-0-basis_for_certificate' => @$api_common->testingLabs[0]->basis,
            'a_cert_doc_issued-rds-cert_doc_issued-additional_info' => trim(@$a_cert_doc_issued_rss_cert_doc_issued_additional_info[0]['__ref']->nodeValue),

            'a_product_info-rds-product_ts-object_type_cert' => isset($object_type_decl) ? $object_type_decl : '',
            'a_product_info-rds-product_ts-product_type' => isset($ProductOrigin) ? $ProductOrigin : '',
            'a_product_info-rds-product_ts-product_name' => trim(@$api_common->product->fullName),
            'a_product_info-rds-product_ts-product_info' => trim(@$a_product_info_rss_product_ts_product_info[0]['__ref']->nodeValue),
            'a_product_info-rds-product_ts-okpd2' => trim(@$product_okpd2->code . ' ' . @$product_okpd2->name),
            'a_product_info-rds-product_ts-okpd2_text' => '',
            'a_product_info-rds-product_ts-tn_ved' => $product_ts_tn_ved,
            'a_product_info-rds-product_ts-tn_ved_text' => trim(@$a_product_info_rss_product_ts_tn_ved_text[0]['__ref']->nodeValue),
            'a_product_info-rds-product_ts-name_doc_made_product' => trim(@$a_product_info_rss_product_ts_name_doc_made_product[0]['__ref']->nodeValue),
            'a_product_info-rds-product_ts-product_info_ext' => trim(@$a_product_info_rss_product_ts_product_info_ext[0]['__ref']->nodeValue),
            'a_product_info-rds-product_ts-serial_number_product' => trim(@$a_product_info_rss_product_ts_serial_number_product[0]['__ref']->nodeValue),
            'a_product_info-rds-product_ts-requisites_doc' => trim(@$a_product_info_rss_product_ts_requisites_doc[0]['__ref']->nodeValue),

            'tech_reg' => trim($tech_reg, '|'),
            'tech_reg_ext' => trim(@$tech_reg_ext, '|'),

            'a_expert-0-last_name' => trim(@$a_expert_0_last_name[0]['__ref']->nodeValue),
            'a_expert-0-first_name' => trim(@$a_expert_0_first_name[0]['__ref']->nodeValue),
            'a_expert-0-patr_name' => trim(@$a_expert_0_patr_name[0]['__ref']->nodeValue),

            'organ_to_certification-name' => @$api_common->certificationAuthority->fullName,
            'organ_to_certification-reg_number' => @$api_common->certificationAuthority->attestatRegNumber,
            'organ_to_certification-reg_date' => @$api_common->certificationAuthority->attestatRegDate,
            'organ_to_certification-head_name' => @$api_common->certificationAuthority->surname." ".@$api_common->certificationAuthority->firstName." ".@$api_common->certificationAuthority->patronymic,
            'organ_to_certification-address' => @$certification_address,
            'organ_to_certification-address_actual' => @$certification_address_actual,
            'organ_to_certification-phone' => @$certification_contacts_phone,
            'organ_to_certification-fax' => @$certification_contacts_fax,
            'organ_to_certification-email' => @$certification_contacts_email,

            'a_info_pril' => trim(@$a_info_pril[0]['__ref']->nodeValue),
            'a_apps-free_form' => empty($a_apps_free_form) ? 0 : 1,
            'a_apps-table_standart' => empty($a_apps_table_standart) ? 0 : 1,
            'a_table_standart_number' => trim(@$a_table_standart_number[0]['__ref']->nodeValue),
            'rds-table_standart_designation' => trim(@$designation, '|'),
            'rds-table_standart_name' => trim(@$name, '|'),
            'rds-table_standart_confirmation_requirements' => trim(@$confirmation_requirements, '|'),
            'a_free_form' => trim(@$a_free_form, '|'),

            'a_reg_number' => @$api_common->applicant->addlRegInfo,
            'a_blank_number' => trim(@$a_blank_number[0]['__ref']->nodeValue),
            'a_date_begin' => @$api_common->declRegDate,
            'a_date_finish' => @$api_common->declEndDate,
            'a_is_date_finish' => empty($a_is_date_finish) ? 0 : 1,

            'conformityDocType' => isset($conformityDocType) ? $conformityDocType : '',
            'techregProductList' => isset($techregProductList) ? $techregProductList : '',

            'a_product_info-standard_designation' => $standards_des,
            'a_product_info-name_of_the_standard' => $standards_name,

            'testingLabs-protocol_number' => @$testingLabsProtocol->number,
            'testingLabs-protocol_date' => isset($testingLabsProtocol) ? date('Y-m-d', strtotime($testingLabsProtocol->date)) : null,
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

    public function getSessionNode()
    {
        return $this->sessionNode;
    }

    private function setSessionNode($session)
    {
        $this->sessionNode = $session;
    }
}
