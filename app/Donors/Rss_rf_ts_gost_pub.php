<?php

namespace App\Donors;

use App\Http\Controllers\LoggerController;
use App\Models\ProxyList;
use ParseIt\_String;
use ParseIt\nokogiri;
use App\Donors\ParseIt\simpleParser;
use ParseIt\ParseItHelpers;

Class Rss_rf_ts_gost_pub extends simpleParser {

    public $data = [];
    public $reload = [];
    public $project = 'rss_rf_ts_gost_pub';
    public $project_link = 'https://pub.fsa.gov.ru/rss/certificate';
    public $source = 'https://pub.fsa.gov.ru/api/v1/rss/common/certificates/get';
    public $cache = false;
    public $proxy = false;
    public $cookieFile = '';
    public $version_id = 1;
    public $donor = 'Rss_rf_ts_gost_pub';
    protected $token = '';
    protected $session = '';
    private $status = [
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
        $opt['refer'] = 'https://pub.fsa.gov.ru/rss/certificate';
        $opt['origin'] = 'https://pub.fsa.gov.ru';
        $opt['host'] = 'pub.fsa.gov.ru';
        $opt['ajax'] = true;
//        $opt['cookieFile'] = $this->cookieFile;
        $opt['headers'] = [
            'Content-Type: application/json',
            'Authorization: Bearer null',
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
            $content = $this->loadUrl("https://pub.fsa.gov.ru/api/v1/rss/common/account", $opt);
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
        $regDate = preg_match("%\d{1,2}\.\d{1,2}\.\d{4}%uis", @$opt['begin']) ? date('Y-m-d',strtotime(@$opt['begin'])).'T00:00:00.000Z' : date('Y-m-d', time()-(31*24*60*60)).'T00:00:00.000Z';
        $endDate = preg_match("%\d{1,2}\.\d{1,2}\.\d{4}%uis", @$opt['end']) ? date('Y-m-d',strtotime(@$opt['end'])).'T00:00:00.000Z' : null;
        $type = '';
        if (isset($opt['type'])) {
            $type = ",\"idCertType\":[{$opt['type']}]";
        }
//        print_r([$regDate, $endDate]);die();
        $opt['refer'] = $this->project_link;
        $opt['origin'] = 'https://pub.fsa.gov.ru';
        $opt['host'] = 'pub.fsa.gov.ru';
        $opt['ajax'] = true;
        $opt['json'] = true;
//        $opt['returnHeader'] = true;
        $opt['headers'] = [
            "Authorization: Bearer {$this->getToken()}",
            "Cookie: JSESSIONID={$this->getSession()}",
            'Content-Type: application/json'
        ];

        $perPage = 10000;
//        $perPage = 10;
        $currPage = 0;
        do
        {
            $nextPage = $currPage + 1;
//            print_r($regDate);die();
            $opt['post'] = "{\"size\":{$perPage},\"page\":{$currPage},\"filter\":{\"regDate\":{\"minDate\":\"{$regDate}\",\"maxDate\":\"{$endDate}\"},\"endDate\":{\"minDate\":\"\",\"maxDate\":\"\"},\"columnsSearch\":[]},\"columnsSort\":[{\"column\":\"date\",\"sort\":\"DESC\"}]{$type}}";
            $api_decl = $this->loadUrl($this->source, $opt);
//            print_r($api_decl);die();
            if ( isset($api_decl->total) )
            {
                $countPage = ceil($api_decl->total / $perPage);
                if ( $countPage-1 > $currPage )
                {
                    $currPage++;
                }
            }
            else
            {
                return $sources;
            }
            if (isset($api_decl->items) && is_array($api_decl->items) && !empty($api_decl->items))
            {
                foreach ($api_decl->items as $k => $item)
                {
//                    print_r($item);die();
                    $href = "https://pub.fsa.gov.ru/api/v1/rss/common/certificates/".$item->id;
                    $hash = md5($href);
                    $sources[$hash]= [
                        'hash' => $hash,
                        'name' => '',
                        'source' => $href,
                        'donor_class_name' => $this->donor,
                        'version' => 2,
                        'param' => [
                            'id' => @$item->id,
                            'STATUS' => isset($item->idStatus) && isset($this->status[$item->idStatus]) ? $this->status[$item->idStatus] : '',
                            'CERT_NUM' => @$item->number,
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
//        $url = preg_replace('%\d+$%uis', 2042613, $url);
        $source['refer'] = 'https://pub.fsa.gov.ru/rss/certificate';
        $source['origin'] = 'https://pub.fsa.gov.ru';
        $source['host'] = 'pub.fsa.gov.ru';
        $source['ajax'] = true;
        $source['json'] = true;
//        $source['returnHeader'] = true;
        $source['headers'] = [
            "Authorization: Bearer {$this->getToken()}",
            "Cookie: JSESSIONID={$this->getSession()}",
            'Content-Type: application/json'
        ];
        $api_common = $this->loadUrl($url, $source);
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
        isset($api_common->idCertScheme) ? $items[] = "\"validationScheme2\":[{\"id\":[".$api_common->idCertScheme."],\"fields\":[\"id\",\"masterId\",\"name\",\"validityTerm\",\"isSeriesProduction\",\"isBatchProduction\",\"isOneOffProduction\",\"isProductSampleTesting\",\"isBatchProductTesting\",\"isOneOffProductTesting\",\"isAccreditationLab\",\"isApplicantManufacturer\",\"isApplicantProvider\",\"isPresenceOfProxy\",\"isApplicantForeign\",\"isApplicantEeuMember\"]}]" : '';
        isset($api_common->idObjectCertType) ? $items[] = "\"validationObjectType\":[{\"id\":[".$api_common->idObjectCertType."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($api_common->idCertType) ? $items[] = "\"conformityDocType\":[{\"id\":[".$api_common->idCertType."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($api_common->idStatus) ? $items[] = "\"status\":[{\"id\":[".$api_common->idStatus."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($addressType) && !empty($addressType) ? $items[] = "\"addressType\":[{\"id\":[".implode(',', $addressType)."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($api_common->applicant->idApplicantType) ? $items[] = "\"declarantType\":[{\"id\":[".$api_common->applicant->idApplicantType."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($applicantType) && !empty($applicantType) ? $items[] = "\"applicantType\":[{\"id\":[".implode(',', $applicantType)."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($contactType) && !empty($contactType) ? $items[] = "\"contactType\":[{\"id\":[".implode(',', $contactType)."],\"fields\":[\"id\",\"masterId\",\"name\"]}]" : '';
        isset($fiasAddrobj) && !empty($fiasAddrobj) ? $items[] = "\"fiasAddrobj\":[{\"id\":[\"".implode('","', $fiasAddrobj)."\"],\"fields\":[\"id\",\"masterId\",\"name\",\"offname\",\"shortname\",\"aolevel\"]}]" : '';
        isset($tnved) && !empty($tnved) ? $items[] = "\"tnved\":[{\"id\":[".implode('', $tnved)."],\"fields\":[\"id\",\"masterId\",\"name\",\"code\"]}]" : '';
        isset($okpd2) && !empty($okpd2) ? $items[] = "\"okpd2\":[{\"id\":[".implode('', $okpd2)."],\"fields\":[\"id\",\"masterId\",\"name\",\"code\"]}]" : '';
//        print_r($api_common);die();
//        print_r($items);die();
//        print_r($api_multi);die();
        $source['post'] = '{"items":{'.implode(',', $items).'}}';
        $api_multi = $this->loadUrl('https://pub.fsa.gov.ru/nsi/api/multi', $source);
//        print_r($api_multi);die();
        $source['post'] = '{"sort":"id","attrs":[],"columns":[{"names":["name"],"search":"Российская"}],"offset":0,"limit":50}';
        $api_oksm = $this->loadUrl('https://pub.fsa.gov.ru/nsi/api/oksm/get', $source);
        if (isset($api_oksm->items))
        {
            foreach ( $api_oksm->items as $item )
            {
                if ($api_common->product->idProductOrigin === $item->id)
                {
                    $ProductOrigin = $item->shortName;
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
                if ($api_common->idCertType === $docType->id)
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
        if ( isset($api_multi->validationObjectType) )
        {
            foreach ( $api_multi->validationObjectType as $objectType)
            {
                if ($objectType->id === $api_common->idObjectCertType)
                {
                    $object_type_cert = $objectType->name;
                }
            }
        }
        if ( isset($api_multi->applicantType) )
        {
            foreach ( $api_multi->applicantType as $type)
            {
                if ($type->id === $api_common->manufacturer->idLegalSubjectType)
                {
                    $manufacturer_type = $type->name;
                }
            }
        }
//        print_r($api_common);die();
//        print_r($api_common->testingLabs);die();
//        print_r($api_multi);die();
        $data[] = [
            'STATUS' => @$api_multi->status[0]->name,
            'CERT_NUM' => @$api_common->number,

            'a_cert_type-cert_ts' => trim(@$a_cert_type_cert_ts[0]['__ref']->nodeValue),
            'a_cert_ts_type-ts' => trim(@$a_cert_ts_type_ts[0]['__ref']->nodeValue),

            'a_applicant_org_type-ul' => @$api_multi->applicantType[0]->name,
            'a_manufacturer_type-iul' => @$manufacturer_type,

            'a_applicant_info-rss-app_legal_person-applicant_type' => isset($declarantType) ? $declarantType : '',
            'a_applicant_info-rss-app_legal_person-name' => @$api_common->applicant->fullName,
            'a_applicant_info-rss-app_legal_person-director_name' => @$api_common->applicant->surname." ".@$api_common->applicant->firstName." ".@$api_common->applicant->patronymic,
            'a_applicant_info-rss-app_legal_person-address' => @$api_common->applicant->addresses[0]->fullAddress,
            'a_applicant_info-rss-app_legal_person-phone' => @$applicant_contacts_phone,
            'a_applicant_info-rss-app_legal_person-fax' => @$applicant_contacts_fax,
            'a_applicant_info-rss-app_legal_person-email' => @$applicant_contacts_email,
            'a_applicant_info-rss-app_legal_person-ogrn' => trim(@$api_common->applicant->ogrn),

            'a_manufacturer_info-rss-man_foreign_legal_person-name' => @$api_common->manufacturer->idLegalSubjectType === 3 ? @$api_common->manufacturer->fullName : '',
            'a_manufacturer_info-rss-man_foreign_legal_person-address' => @$api_common->manufacturer->idLegalSubjectType === 3 ? @$api_common->manufacturer->addresses[0]->fullAddress : '',

            'a_manufacturer_info-rss-man_legal_person-name' => @$api_common->manufacturer->idLegalSubjectType != 3 ? @$api_common->manufacturer->fullName : '',
            'a_manufacturer_info-rss-man_legal_person-address' => @$api_common->manufacturer->idLegalSubjectType != 3 ? @$api_common->manufacturer->addresses[0]->fullAddress : '',
            'a_manufacturer_info-rss-man_legal_person-phone' => @$api_common->manufacturer->idLegalSubjectType != 3 ? @$manufacturer_contacts_phone : '',
            'a_manufacturer_info-rss-man_legal_person-fax' => @$api_common->manufacturer->idLegalSubjectType != 3 ? @$manufacturer_contacts_fax : '',
            'a_manufacturer_info-rss-man_legal_person-email' => @$api_common->manufacturer->idLegalSubjectType != 3 ? @$manufacturer_contacts_email : '',
            'a_manufacturer_info-rss-man_legal_person-ogrn' => @$api_common->manufacturer->idLegalSubjectType != 3 ? @$api_common->manufacturer->ogrn : '',

            'cert_doc_issued-document_info' => trim(@$a_cert_doc_issued_rss_cert_doc_issued_document_info[0]['__ref']->nodeValue),
            'cert_doc_issued-testing_lab-0-basis_for_certificate' => trim(@$a_cert_doc_issued_rss_cert_doc_issued_testing_lab_basis_for_certificate[0]['__ref']->nodeValue),

            'cert_doc_issued-testing_lab-1-basis_for_certificate' => @$api_common->testingLabs[0]->basis,
            'cert_doc_issued-testing_lab-0-reg_number' => @$api_common->testingLabs[0]->regNumber,
            'a_cert_doc_issued-rss-cert_doc_issued-additional_info' => trim(@$a_cert_doc_issued_rss_cert_doc_issued_additional_info[0]['__ref']->nodeValue),

            'a_product_info-rss-product_ts-object_type_cert' => isset($object_type_cert) ? $object_type_cert : '',
            'a_product_info-rss-product_ts-product_type' => trim(@$a_product_info_rss_product_ts_product_type[0]['__ref']->nodeValue),
            'a_product_info-rss-product_ts-product_name' => trim(@$api_common->product->fullName),
            'a_product_info-rss-product_ts-product_info' => @$api_common->product->identification,
            'a_product_info-rss-product_ts-okpd2' => trim(@$product_okpd2->code . ' ' . @$product_okpd2->name),
            'a_product_info-rss-product_ts-okpd2_text' => trim(@$a_product_info_rss_product_ts_okpd2_text[0]['__ref']->nodeValue),
            'a_product_info-rss-product_ts-tn_ved' => trim(@$api_multi->tnved[0]->code . ' '. @$api_multi->tnved[0]->name),
            'a_product_info-rss-product_ts-tn_ved_text' => trim(@$a_product_info_rss_product_ts_tn_ved_text[0]['__ref']->nodeValue),
            'a_product_info-rss-product_ts-name_doc_made_product' => trim(@$a_product_info_rss_product_ts_name_doc_made_product[0]['__ref']->nodeValue),
            'a_product_info-rss-product_ts-product_info_ext' => trim(@$a_product_info_rss_product_ts_product_info_ext[0]['__ref']->nodeValue),
            'a_product_info-rss-product_ts-serial_number_product' => trim(@$a_product_info_rss_product_ts_serial_number_product[0]['__ref']->nodeValue),
            'a_product_info-rss-product_ts-requisites_doc' => trim(@$a_product_info_rss_product_ts_requisites_doc[0]['__ref']->nodeValue),

            'tech_reg' => @$api_multi->dicNormDoc[0]->docDesignation . ' ' . @$api_multi->dicNormDoc[0]->name,

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
            'rss-table_standart_designation' => trim(@$designation, '|'),
            'rss-table_standart_name' => trim(@$name, '|'),
            'rss-table_standart_confirmation_requirements' => trim(@$confirmation_requirements, '|'),
            'a_free_form' => trim(@$a_free_form, '|'),

            'a_reg_number' => trim(@$a_reg_number[0]['__ref']->nodeValue),
            'a_blank_number' => @$api_common->blankNumber,
            'a_date_begin' => @$api_common->certRegDate,
            'a_date_finish' => @$api_common->certEndDate,
            'a_is_date_finish' => empty($a_is_date_finish) ? 0 : 1,

            'conformityDocType' => isset($conformityDocType) ? $conformityDocType : '',
        ];
//         print_r($data); die();

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