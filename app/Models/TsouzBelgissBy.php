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
}