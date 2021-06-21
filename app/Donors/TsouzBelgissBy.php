<?php

namespace App\Donors;

use App\Http\Controllers\LoggerController;
use App\Models\ProxyList;
use ParseIt\_String;
use ParseIt\nokogiri;
use App\Donors\ParseIt\simpleParser;
use ParseIt\ParseItHelpers;

Class TsouzBelgissBy extends simpleParser {

    public $data = [];
    public $reload = [];
    public $project = 'tsouz.belgiss.by';
    public $project_link = 'https://tsouz.belgiss.by/';
    public $source = 'https://tsouz.belgiss.by/#!/tsouz/certifs';
    public $cache = false;
    public $proxy = false;
    public $cookieFile = '';
    public $version_id = 1;
    public $donor = 'TsouzBelgissBy';
    protected $token = '';
    protected $session = '';

    function __construct()
    {
        $this->cookieFile = __DIR__.'/cookie/'.class_basename(get_class($this)).'/'.class_basename(get_class($this)).'.txt';
    }

    public function getSources($opt = [])
    {
        $sources = [];

        $opt['cookieFile'] = $this->cookieFile;

        $opt['headers'] = [
            "Host: api.belgiss.by",
            "Origin: https://tsouz.belgiss.by",
        ];
        $opt['host'] = 'api.belgiss.by';
        $opt['origin'] = $this->project_link;
        $opt['referer'] = "https://tsouz.belgiss.by/";
        $opt['ajax'] = true;
        $opt['json'] = true;

        if (!isset($opt['page']))
        {
            $opt['page'] = 1;
            if (isset($opt['downloadAdditionalInformation']))
            {
                $this->downloadAdditionalInformation();
            }
        }
        if (!isset($opt['startDate']))
        {
            $before10day = time() - (60*60*24*2);
            $opt['startDate'] = date('d.m.Y', $before10day);
            $opt['endDate'] = date('d.m.Y');
        }

        $url = "https://api.belgiss.by/tsouz/tsouz-certifs?page={$opt['page']}&per-page=100&sort=-certdecltr_id&filter%5BDocStartDate%5D%5Bgte%5D={$opt['startDate']}&filter%5BDocStartDate%5D%5Blte%5D={$opt['endDate']}";

        $content = $this->loadUrl($url, $opt);

        if (!isset($content->items))
        {
            return [];
        }

        foreach ($content->items as $k => $item)
        {
            $href = "https://api.belgiss.by/tsouz/tsouz-certifs/{$item->certdecltr_id}";
            $hash = md5($href);
            $sources[$hash]= [
                'hash' => $hash,
                'name' => '',
                'source' => $href,
                'donor_class_name' => $this->donor,
                'version' => 2,
                'param' => [
                    'certdecltr_id' => $item->certdecltr_id,
                ]
            ];
        }

        if ($content->_meta->pageCount > $content->_meta->currentPage)
        {
            $opt['page'] = $opt['page'] + 1;
            $sourcesNextPage = $this->getSources($opt);

            foreach ($sourcesNextPage as $source)
            {
                $sources[$source['hash']] = $source;
            }
        }

        return $sources;
    }


    public function getData($url, $source = [])
    {
        $data = false;

        $number = $source['param']['certdecltr_id'];
//        $number = 677173;

        $source['cookieFile'] = $this->cookieFile;

        $source['host'] = 'api.belgiss.by';
        $source['origin'] = 'https://tsouz.belgiss.by';
        $source['referer'] = "https://tsouz.belgiss.by/";
        $source['ajax'] = true;
        $source['json'] = true;

        $content = $this->loadUrl("https://api.belgiss.by/tsouz/tsouz-certifs/{$number}", $source);

//        $addressKindCode = $this->loadAdditionalInformationFromFile('address-kind-code.json');
        $technicalRegulationIdCode = $this->loadAdditionalInformationFromFile('technical-regulation-id.json');
        $conformityDocKindCode = $this->loadAdditionalInformationFromFile('conformity-doc-kind-code.json');
        $docStatusCode = $this->loadAdditionalInformationFromFile('doc-status-code.json');
//        $communicationChannelCode = $this->loadAdditionalInformationFromFile('communication-channel.json');
//        $technicalRegulationObjectKindName = $this->loadAdditionalInformationFromFile('technical-regulation-object-kind-name.json');
        $certificationSchemeCode = $this->loadAdditionalInformationFromFile('certification-scheme-code.json');
        $certificationObjectCode = $this->loadAdditionalInformationFromFile('certification-object-code.json');
//        $unifiedCountryCode = $this->loadAdditionalInformationFromFile('unified-country-code.json');
//        $measure = $this->loadAdditionalInformationFromFile('unified-commodity-measure.json');
//        $conformityAuthority = $this->loadAdditionalInformationFromFile('conformity-authority-public.json');

        if (!isset($conformityDocKindCode->items))
        {
            return $data;
        }
        $ConformityDocKindCode_SHORTNAME = '';
        foreach ($conformityDocKindCode->items as $item)
        {
            if ($item->ConformityDocKindCode_CODE == $content->certdecltr_ConformityDocDetails->ConformityDocKindCode)
            {
                $ConformityDocKindCode_SHORTNAME = $item->ConformityDocKindCode_SHORTNAME;
                break;
            }
        }

        if (!isset($certificationSchemeCode->items))
        {
            return $data;
        }
        $CertificationSchemeCode_Name = '';
        foreach ($certificationSchemeCode->items as $item)
        {
            if ($item->CertificationSchemeCode_Code == $content->certdecltr_ConformityDocDetails->CertificationSchemeCode)
            {
                $CertificationSchemeCode_Name = $item->CertificationSchemeCode_Name;
                break;
            }
        }

        if (!isset($certificationObjectCode->items))
        {
            return $data;
        }
        $CertificationObjectCode_Name = '';
        foreach ($certificationObjectCode->items as $item)
        {
            if ($item->CertificationObjectCode_Code == $content->certdecltr_ConformityDocDetails->CertificationObjectCode)
            {
                $CertificationObjectCode_Name = $item->CertificationObjectCode_Name;
                break;
            }
        }

        if (!isset($technicalRegulationIdCode->items))
        {
            return $data;
        }
        $TechnicalRegulationId = '';
        foreach ($content->certdecltr_ConformityDocDetails->TechnicalRegulationId as $techRegId)
        {
            foreach ($technicalRegulationIdCode->items as $item)
            {
                if ($item->TechnicalRegulationIdCode == $techRegId)
                {
                    $TechnicalRegulationId .= $item->TechnicalRegulationIdCode.' '.$item->TechnicalRegulationIdName."\n";
                    break;
                }
            }
        }
        $TechnicalRegulationId = trim($TechnicalRegulationId);

        if (!isset($docStatusCode->items))
        {
            return $data;
        }
        $DocStatusCode_NAME = '';
        foreach ($docStatusCode->items as $item)
        {
            if ($item->DocStatusCode_CODE == $content->certdecltr_ConformityDocDetails->DocStatusDetails->DocStatusCode)
            {
                $DocStatusCode_NAME = "({$content->certdecltr_ConformityDocDetails->DocStatusDetails->DocStatusCode}) {$item->DocStatusCode_NAME}";
                break;
            }
        }

        $StartDate = trim($content->certdecltr_ConformityDocDetails->DocStatusDetails->StartDate);
        $StartDate = !empty($StartDate) ? date('Y-m-d', strtotime($StartDate)) : null;

        $DocStartDate = trim($content->certdecltr_ConformityDocDetails->DocStartDate);
        $DocStartDate = !empty($DocStartDate) ? date('Y-m-d', strtotime($DocStartDate)) : null;

        $EndDate = trim(@$content->certdecltr_ConformityDocDetails->DocStatusDetails->EndDate);
        $EndDate = !empty($EndDate) ? date('Y-m-d', strtotime($EndDate)) : null;

        $DocValidityDate = trim($content->certdecltr_ConformityDocDetails->DocValidityDate);
        $DocValidityDate = !empty($DocValidityDate) ? date('Y-m-d', strtotime($DocValidityDate)) : null;


        $FullNameDetails = $this->getLineFioFromArray($content->certdecltr_ConformityDocDetails->FullNameDetails);


        $AdditionalInfoText = '';
        foreach ($content->certdecltr_ConformityDocDetails->AdditionalInfoText as $info)
        {
            $AdditionalInfoText .= trim($info).";\n";
        }
        $AdditionalInfoText = trim($AdditionalInfoText);
        $AdditionalInfoText = trim($AdditionalInfoText, ';');

        $DocCreationDate = trim($content->certdecltr_ConformityDocDetails->ConformityAuthorityV2Details->DocCreationDate);
        $DocCreationDate = !empty($DocCreationDate) ? date('Y-m-d', strtotime($DocCreationDate)) : null;


        $OfficerDetails_FullNameDetails = $this->implodeFIOLineFromArray($content->certdecltr_ConformityDocDetails->ConformityAuthorityV2Details->OfficerDetails->FullNameDetails);


        $OfficerDetails_CommunicationDetails = $this->getCommunicationDetailsLineFromArray($content->certdecltr_ConformityDocDetails->ConformityAuthorityV2Details->OfficerDetails->CommunicationDetails);


        $ConformityAuthorityV2Details_CommunicationDetails = $this->getCommunicationDetailsLineFromArray($content->certdecltr_ConformityDocDetails->ConformityAuthorityV2Details->CommunicationDetails);


        $code = $content->certdecltr_ConformityDocDetails->ApplicantDetails->UnifiedCountryCode;
        $App_UnifiedCountryCode = $this->getUnifiedCountryCode_NAME($code);


        $App_Declaring_DocInformationDetails_DocCreationDate = trim($content->certdecltr_ConformityDocDetails->ApplicantDetails->DeclaringOfficerDetails->DocInformationDetails->DocCreationDate);
        $App_Declaring_DocInformationDetails_DocCreationDate = !empty($App_Declaring_DocInformationDetails_DocCreationDate) ? date('Y-m-d', strtotime($App_Declaring_DocInformationDetails_DocCreationDate)) : null;

        $code = $content->certdecltr_ConformityDocDetails->TechnicalRegulationObjectDetails->ManufacturerDetails[0]->UnifiedCountryCode;
        $Manuf_UnifiedCountryCode = $this->getUnifiedCountryCode_NAME($code);

        $ProductDetails = $content->certdecltr_ConformityDocDetails->TechnicalRegulationObjectDetails->ProductDetails;
        foreach ($ProductDetails as $productDetail)
        {
            foreach ($productDetail->ProductInstanceDetails as $productInstanceDetail)
            {
                $productInstanceDetail->UnifiedCommodityMeasure_attr = $this->getUnifiedCommodityMeasure($productInstanceDetail->UnifiedCommodityMeasure_attr);
            }
        }

        if (!isset($content->certdecltr_id))
        {
            return $data;
        }

        $data[] = [
            'certdecltr_id' => $content->certdecltr_id,

            'DocId' => $content->certdecltr_ConformityDocDetails->DocId,
            'DocStartDate' => $DocStartDate,
            'DocValidityDate' => $DocValidityDate,
            'ConformityDocKindCode' => $ConformityDocKindCode_SHORTNAME,
            'SingleListProductIndicator' => $content->certdecltr_ConformityDocDetails->SingleListProductIndicator != 'false' ? 'Да' : 'Нет',
            'CertificationSchemeCode' => $CertificationSchemeCode_Name,
            'CertificationObjectCode' => $CertificationObjectCode_Name,
            'TechnicalRegulationId' => $TechnicalRegulationId,
            'FormNumberId' => $content->certdecltr_ConformityDocDetails->FormNumberId,
            'DocStatusCode' => $DocStatusCode_NAME,
            'StartDate' => $StartDate,
            'EndDate' => $EndDate,
            'NoteText' => $content->certdecltr_ConformityDocDetails->DocStatusDetails->NoteText,
            'FullNameDetails' => $FullNameDetails,
            'AdditionalInfoText' => $AdditionalInfoText,
            'ConformityAuthorityId' => $content->certdecltr_ConformityDocDetails->ConformityAuthorityV2Details->ConformityAuthorityId,
            'BusinessEntityName' => $content->certdecltr_ConformityDocDetails->ConformityAuthorityV2Details->BusinessEntityName,
            'ConformityAuthorityV2Details_DocId' => $content->certdecltr_ConformityDocDetails->ConformityAuthorityV2Details->DocId,
            'ConformityAuthorityV2Details_DocCreationDate' => $DocCreationDate,
            'OfficerDetails_PositionName' => $content->certdecltr_ConformityDocDetails->ConformityAuthorityV2Details->OfficerDetails->PositionName,
            'OfficerDetails_FullNameDetails' => $OfficerDetails_FullNameDetails,
            'OfficerDetails_CommunicationDetails' => $OfficerDetails_CommunicationDetails,
            'ConformityAuthorityV2Details_AddressV4Details' => $this->getAddressLineFromArray($content->certdecltr_ConformityDocDetails->ConformityAuthorityV2Details->AddressV4Details),
            'ConformityAuthorityV2Details_CommunicationDetails' => $ConformityAuthorityV2Details_CommunicationDetails,
            'DocAnnexDetails_ObjectOrdinal' => $content->certdecltr_ConformityDocDetails->DocAnnexDetails[0]->ObjectOrdinal,
            'DocAnnexDetails_PageQuantity' => $content->certdecltr_ConformityDocDetails->DocAnnexDetails[0]->PageQuantity,
            'DocAnnexDetails_FormNumberId' => implode($content->certdecltr_ConformityDocDetails->DocAnnexDetails[0]->FormNumberId, ', '),
            'App_UnifiedCountryCode' => $App_UnifiedCountryCode,
            'App_BusinessEntityBriefName' => $content->certdecltr_ConformityDocDetails->ApplicantDetails->BusinessEntityBriefName,
            'App_BusinessEntityId' => $content->certdecltr_ConformityDocDetails->ApplicantDetails->BusinessEntityId,
            'App_BusinessEntityName' => $content->certdecltr_ConformityDocDetails->ApplicantDetails->BusinessEntityName,
            'App_SubjectAddressDetails' => $this->getAddressLineFromArray($content->certdecltr_ConformityDocDetails->ApplicantDetails->SubjectAddressDetails),
            'App_CommunicationDetails' => $this->getCommunicationDetailsLineFromArray($content->certdecltr_ConformityDocDetails->ApplicantDetails->CommunicationDetails),
            'App_DeclaringOfficerDetails_Position' => $content->certdecltr_ConformityDocDetails->ApplicantDetails->DeclaringOfficerDetails->PositionName,
            'App_DeclaringOfficerDetails_FIO' => $this->implodeFIOLineFromArray($content->certdecltr_ConformityDocDetails->ApplicantDetails->DeclaringOfficerDetails->FullNameDetails),
            'App_DeclaringOfficerDetails_CommunicationDetails' => $this->getCommunicationDetailsLineFromArray($content->certdecltr_ConformityDocDetails->ApplicantDetails->DeclaringOfficerDetails->CommunicationDetails),
            'App_Declaring_DocInformationDetails_DocId' => $content->certdecltr_ConformityDocDetails->ApplicantDetails->DeclaringOfficerDetails->DocInformationDetails->DocId,
            'App_Declaring_DocInformationDetails_DocName' => $content->certdecltr_ConformityDocDetails->ApplicantDetails->DeclaringOfficerDetails->DocInformationDetails->DocName,
            'App_Declaring_DocInformationDetails_DocCreationDate' => $App_Declaring_DocInformationDetails_DocCreationDate,

            'Manuf_UnifiedCountryCode' => $Manuf_UnifiedCountryCode,
            'Manuf_BusinessEntityBriefName' => $content->certdecltr_ConformityDocDetails->TechnicalRegulationObjectDetails->ManufacturerDetails[0]->BusinessEntityBriefName,
            'Manuf_BusinessEntityName' => $content->certdecltr_ConformityDocDetails->TechnicalRegulationObjectDetails->ManufacturerDetails[0]->BusinessEntityName,
            'Manuf_AddressV4Details' => $this->getAddressLineFromArray($content->certdecltr_ConformityDocDetails->TechnicalRegulationObjectDetails->ManufacturerDetails[0]->AddressV4Details),
            'Manuf_CommunicationDetails' => $this->getCommunicationDetailsLineFromArray($content->certdecltr_ConformityDocDetails->TechnicalRegulationObjectDetails->ManufacturerDetails[0]->CommunicationDetails),

            'TechnicalRegulationObjectKindName' => $content->certdecltr_ConformityDocDetails->TechnicalRegulationObjectDetails->TechnicalRegulationObjectKindName,
            'ProductDetails' => serialize($ProductDetails),
            'DocInformationDetails' => serialize($content->certdecltr_ConformityDocDetails->TechnicalRegulationObjectDetails->DocInformationDetails),

            'ComplianceDocDetails' => serialize($content->certdecltr_ConformityDocDetails->ComplianceDocDetails),

            'ComplianceProvidingDocDetails' => serialize($content->certdecltr_ConformityDocDetails->ComplianceProvidingDocDetails),
        ];
//        print_r($data);die();

        return $data;
    }

    private function getUnifiedCommodityMeasure($Code)
    {
        $measure = $this->loadAdditionalInformationFromFile('unified-commodity-measure.json');

        if (!isset($measure->items))
        {
            return false;
        }
        foreach ($measure->items as $item)
        {
            if ($item->codeEn == $Code)
            {
                $UnifiedCommodityMeasure = "({$item->codeEn}) {$item->code}, {$item->abbrRu}";
                return $UnifiedCommodityMeasure;
            }
        }
        return '';
    }

    private function getLineFioFromArray($persons)
    {
        $FullNameDetails = '';
        foreach ($persons as $person)
        {
            $FullNameDetails .= $this->implodeFIOLineFromArray($person).",\n";
        }
        $FullNameDetails = trim($FullNameDetails);
        $FullNameDetails = trim($FullNameDetails, ',');

        return $FullNameDetails;
    }

    private function implodeFIOLineFromArray($person)
    {
        return trim($person->LastName).' '.trim($person->FirstName).' '.trim($person->MiddleName);
    }

    private function getCommunicationDetailsLineFromArray($listCommunications)
    {
        $line = '';
        foreach ($listCommunications as $communicationDetail)
        {
            $CommunicationChannelId = implode(", ", $communicationDetail->CommunicationChannelId);
            if (!empty($communicationDetail->CommunicationChannelCode))
            {
                $line .= "({$communicationDetail->CommunicationChannelCode}) {$CommunicationChannelId}".";\n";
            }
        }
        $line = trim($line);
        $line = trim($line, ';');

        return $line;
    }

    private function getAddressLineFromArray($listAddresses)
    {
        $line = '';
        foreach ($listAddresses as $item)
        {
            $address = $this->implodeAddress($item);
            $line .= "{$address}".";\n";
        }
        $line = trim($line);
        $line = trim($line, ';');
        return $line;
    }

    private function implodeAddress($address)
    {
        $addressLine = '';

        $addressKindCode = $this->loadAdditionalInformationFromFile('address-kind-code.json');

        $addressLine .= $this->getUnifiedCountryCode_NAME($address->UnifiedCountryCode).", ";

        if (!isset($addressKindCode->items))
        {
            return false;
        }
        $AddressKindCode_NAME = '';
        foreach ($addressKindCode->items as $item)
        {
            if ($item->AddressKindCode_CODE == $address->AddressKindCode)
            {
                $AddressKindCode_NAME = $item->AddressKindCode_NAME;
                $addressLine .= "({$item->AddressKindCode_CODE}) ".$AddressKindCode_NAME.", ";
                break;
            }
        }

        if (isset($address->RegionName) && !empty(trim($address->RegionName)))
        {
            $addressLine .= "Область: ".$address->RegionName;
        }

        if (isset($address->CityName) && !empty(trim($address->CityName)))
        {
            $addressLine .= ", Город: ".$address->CityName;
        }

        if (isset($address->DistrictName) && !empty(trim($address->DistrictName)))
        {
            $addressLine .= ", Район: ".$address->DistrictName;
        }

        if (isset($address->SettlementName) && !empty(trim($address->SettlementName)))
        {
            $addressLine .= ", Населенный пункт: ".$address->SettlementName;
        }

        if (isset($address->AddressText) && !empty(trim($address->AddressText)))
        {
            $addressLine .= ", Адрес в текстовой форме: ".$address->AddressText;
        }

        if (isset($address->StreetName) && !empty(trim($address->StreetName)))
        {
            $addressLine .= ", Улица: ".$address->StreetName;
        }

        if (isset($address->BuildingNumberId) && !empty(trim($address->BuildingNumberId)))
        {
            $addressLine .= ", Номер дома: ".$address->BuildingNumberId;
        }

        if (isset($address->RoomNumberId) && !empty(trim($address->RoomNumberId)))
        {
            $addressLine .= ", Номер помещения: ".$address->RoomNumberId;
        }

        if (isset($address->PostCode) && !empty(trim($address->PostCode)))
        {
            $addressLine .= ", Почтовый индекс: ".$address->PostCode;
        }

        return $addressLine;
    }

    private function getUnifiedCountryCode_NAME($Code)
    {
        $unifiedCountryCode = $this->loadAdditionalInformationFromFile('unified-country-code.json');

        if (!isset($unifiedCountryCode->items))
        {
            return false;
        }
        foreach ($unifiedCountryCode->items as $item)
        {
            if ($item->UnifiedCountryCode_CODE == $Code)
            {
                $UnifiedCountryCode_NAME = $item->UnifiedCountryCode_NAME;
                $UnifiedCountryCode_NAME = "({$item->UnifiedCountryCode_CODE}) ".$UnifiedCountryCode_NAME;
                return $UnifiedCountryCode_NAME;
            }
        }
        return '';
    }

    private function downloadAdditionalInformation()
    {
        $source['cookieFile'] = $this->cookieFile;

        $source['host'] = 'api.belgiss.by';
        $source['origin'] = 'https://tsouz.belgiss.by';
        $source['referer'] = "https://tsouz.belgiss.by/";
        $source['ajax'] = true;
        $source['json'] = true;

        $addressKindCode = $this->loadUrl("https://api.belgiss.by/tsouz/address-kind-code", $source);
        $this->saveAdditionalInformationToFile('address-kind-code.json', $addressKindCode);

        $technicalRegulationIdCode = $this->loadUrl("https://api.belgiss.by/tsouz/technical-regulation-id", $source);
        $this->saveAdditionalInformationToFile('technical-regulation-id.json', $technicalRegulationIdCode);

        $conformityDocKindCode = $this->loadUrl("https://api.belgiss.by/tsouz/conformity-doc-kind-code", $source);
        $this->saveAdditionalInformationToFile('conformity-doc-kind-code.json', $conformityDocKindCode);

        $docStatusCode = $this->loadUrl("https://api.belgiss.by/tsouz/doc-status-code", $source);
        $this->saveAdditionalInformationToFile('doc-status-code.json', $docStatusCode);

        $communicationChannelCode = $this->loadUrl("https://api.belgiss.by/tsouz/communication-channel", $source);
        $this->saveAdditionalInformationToFile('communication-channel.json', $communicationChannelCode);

        $technicalRegulationObjectKindName = $this->loadUrl("https://api.belgiss.by/tsouz/technical-regulation-object-kind-name", $source);
        $this->saveAdditionalInformationToFile('technical-regulation-object-kind-name.json', $technicalRegulationObjectKindName);

        $certificationSchemeCode = $this->loadUrl("https://api.belgiss.by/tsouz/certification-scheme-code", $source);
        $this->saveAdditionalInformationToFile('certification-scheme-code.json', $certificationSchemeCode);

        $certificationObjectCode = $this->loadUrl("https://api.belgiss.by/tsouz/certification-object-code", $source);
        $this->saveAdditionalInformationToFile('certification-object-code.json', $certificationObjectCode);

        $unifiedCountryCode = $this->loadUrl("https://api.belgiss.by/tsouz/unified-country-code", $source);
        $this->saveAdditionalInformationToFile('unified-country-code.json', $unifiedCountryCode);

        $measure = $this->loadUrl("https://api.belgiss.by/tsouz/unified-commodity-measure", $source);
        $this->saveAdditionalInformationToFile('unified-commodity-measure.json', $measure);

        $conformityAuthority = $this->loadUrl("https://api.belgiss.by/tsouz/conformity-authority-public", $source);
        $this->saveAdditionalInformationToFile('conformity-authority-public.json', $conformityAuthority);
    }

    private function saveAdditionalInformationToFile($filename, $value)
    {
        file_put_contents($filename, json_encode($value));
    }

    private function loadAdditionalInformationFromFile($filename)
    {
        $content = file_get_contents($filename);
        return json_decode($content);
    }
}
