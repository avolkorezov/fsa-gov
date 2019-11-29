<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MMCert01RU extends Model
{
    protected $table = 'armnab_am_certList_MMCert01RU';

    public $timestamps = true;

    protected $fillable = [
        'STATUS',
        'REG_NUMBER',
        'VALIDFROM_DATE',
        'EXPIRATION_DATE',
        'SERIAL_NUMBER',

        'APPLICANT_CORP_NAME',
        'APPLICANT_CORP_LEADERNAME',
        'APPLICANT_CORP_LEADERLASTNAME',
        'APPLICANT_CORP_REGNUMBER',
        'APPLICANT_CORP_HVHH',
        'APPLICANT_CORP_PHONE',
        'APPLICANT_CORP_FAX',
        'APPLICANT_CORP_EMAIL',
        'APPLICANT_CORP_ADDRESS1',
        'APPLICANT_CORP_ADDRESS2',

        'APPLICANT_PERS_NAME',
        'APPLICANT_PERS_LASTNAME',
        'APPLICANT_PERS_REGNUMBER',
        'APPLICANT_PERS_HVHH',
        'APPLICANT_PERS_PHONE',
        'APPLICANT_PERS_FAX',
        'APPLICANT_PERS_EMAIL',
        'APPLICANT_PERS_ADDRESS1',
        'APPLICANT_PERS_ADDRESS2',

        'MANUFACTURER_EXT_NAME',
        'MANUFACTURER_EXT_COUNTRY',
        'MANUFACTURER_EXT_ADDRESS',
        'MANUFACTURER_EXT_HVHH',
        'MANUFACTURER_EXT_PHONE',
        'MANUFACTURER_EXT_FAX',
        'MANUFACTURER_EXT_EMAIL',
        'PRODUCT_NAME',
        'PRODUCT_SPECIFICATION',
        'PRODUCT_TK_NAME',
        'PRODUCT_TK_REKVISIT',
        'PRODUCT_ST_NAME',
        'PRODUCT_ST_REKVISIT',
        'PRODUCT_CS_NAME',
        'PRODUCT_CS_REKVISIT',
        'PRODUCT_CERTOBJECT_TYPE',
        'PRODUCT_BATCH',
        'PRODUCT_BATCH_DOCUMENTS',
        'PRODUCT_MMATGAA',
        'PRODUCT_TECHLIST',
        'ProductTestReport',
        'ProductOtherDocuments',
        'ProductExtraInfo',
        'Attachments',
        'HGM_NAME',
        'AC_NUMBER',
        'HGM_LEADER_NAME',
        'HGM_LEADER_LASTNAME',
        'HGM_LEADER_FATHERNAME',
        'HGM_PHONE',
        'HGM_FAX',
        'HGM_EMAIL',
        'HGM_ADDRESS1',
        'HGM_ADDRESS2',
        'HGM_EXPERT_NAME',
        'HGM_EXPERT_LASTNAME',
        'HGM_EXPERT_FATHERNAME',
    ];

    protected $guarded = [];

    public static function rules()
    {
        return [
            'REG_NUMBER' => 'required',
        ];
    }
}