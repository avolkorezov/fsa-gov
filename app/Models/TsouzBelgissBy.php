<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TsouzBelgissBy extends Model
{
    protected $table = 'tsouz_belgiss_by';

    public $timestamps = true;

    protected $fillable = [
        'certdecltr_id',

        'DocId',
        'DocStartDate',
        'DocValidityDate',
        'ConformityDocKindCode',
        'SingleListProductIndicator',
        'CertificationSchemeCode',
        'CertificationObjectCode',
        'TechnicalRegulationId',
        'FormNumberId',
        'DocStatusCode',
        'StartDate',
        'EndDate',
        'NoteText',
        'FullNameDetails',
        'AdditionalInfoText',

        'ConformityAuthorityId',
        'BusinessEntityName',
        'ConformityAuthorityV2Details_DocId',
        'ConformityAuthorityV2Details_DocCreationDate',
        'OfficerDetails_PositionName',
        'OfficerDetails_FullNameDetails',
        'OfficerDetails_CommunicationDetails',
        'ConformityAuthorityV2Details_AddressV4Details',
        'ConformityAuthorityV2Details_CommunicationDetails',

        'DocAnnexDetails_ObjectOrdinal',
        'DocAnnexDetails_PageQuantity',
        'DocAnnexDetails_FormNumberId',

        'App_UnifiedCountryCode',
        'App_BusinessEntityBriefName',
        'App_BusinessEntityId',
        'App_BusinessEntityName',
        'App_SubjectAddressDetails',
        'App_CommunicationDetails',
        'App_DeclaringOfficerDetails_FIO',
        'App_DeclaringOfficerDetails_CommunicationDetails',
        'App_Declaring_DocInformationDetails_DocId',
        'App_Declaring_DocInformationDetails_DocName',
        'App_Declaring_DocInformationDetails_DocCreationDate',

        'Manuf_UnifiedCountryCode',
        'Manuf_BusinessEntityBriefName',
        'Manuf_BusinessEntityName',
        'Manuf_AddressV4Details',
        'Manuf_CommunicationDetails',

        'TechnicalRegulationObjectKindName',
        'ProductDetails',
        'DocInformationDetails',

        'ComplianceDocDetails',

        'ComplianceProvidingDocDetails',
    ];

    protected $guarded = [];

    public static function rules()
    {
        return [
            'certdecltr_id' => 'required',
        ];
    }

    public function toCSVRow()
    {
        $products = [];
//        $fixed_data = preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {
//            return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
//        },$this->ProductDetails );
        $ProductDetails = unserialize($this->ProductDetails);
        foreach ($ProductDetails as $product)
        {
            if (isset($product->ProductName))
            {
                $products[] = $product->ProductName;
            }
        }
        $comunications = explode(';', $this->App_CommunicationDetails);
        $phone = '';
        $email = '';
        foreach ($comunications as $comunication)
        {
            $comunication = trim($comunication);
            if (strpos($comunication, 'TE'))
            {
                $phone = $comunication;
            }
            else if (strpos($comunication, 'EM'))
            {
                $email = $comunication;
            }
        }

//        print_r($comunications);die();
        $row = [
            'Номер декларации/сертификата' => $this->DocId,
            'Дата начала действия' => date('d.m.Y', strtotime($this->DocStartDate)),
            'Дата окончания действия' => date('d.m.Y', strtotime($this->DocValidityDate)),
            'Продукция' => implode(', ', $products),
            'Технический регламент' => $this->TechnicalRegulationId,
            'Схема декларирования' => $this->CertificationSchemeCode,
            'Заявитель' => $this->App_BusinessEntityName,
            'Адрес' => $this->App_SubjectAddressDetails,
            'Телефон' => @$phone,
            'Электронная почта' => @$email,
            'ОГРН' => $this->App_BusinessEntityId,
            'Изготовитель (иностранное ЮЛ)' => $this->Manuf_BusinessEntityName,
            'Адрес(изготовителя)' => $this->Manuf_AddressV4Details,
            'Орган по сертификации' => $this->BusinessEntityName,
        ];
//        print_r($row);die();
//        $line = '"';
        $line = implode('Ω', $row);
        $line = str_replace("\n\r", ' ', $line);
        $line = str_replace("\r\n", ' ', $line);
        $line = str_replace("\r", ' ', $line);
        $line = str_replace("\n", ' ', $line);
        return $line."\r";
    }
}