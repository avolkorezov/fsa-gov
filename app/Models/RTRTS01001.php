<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class RTRTS01001 extends Model
{
    protected $table = 'armnab_am_certList_RTRTS01001';

    public $timestamps = true;

    protected $fillable = [
        'Doc_Type',
        'REG_NUMBER',
        'VALIDFROM_DATE',
        'EXPIRATION_DATE',
        'SERIAL_NUMBER',
        'ORG_PO_OCENKE_SOOTVET',
        'SCHEME_SERTIFIC',
        'TYPE_OBJ_TR',
        'STATUS',
        'STATUS_DATE_BEGIN',
        'TK_REKVISIT',
        'APPLICANT_PERS_NAME',
        'APPLICANT_PERS_OPF',
        'APPLICANT_PERS_COUNTRY',
        'APPLICANT_PERS_REGNUMBER',
        'APPLICANT_PERS_HVHH',
        'APPLICANT_PERS_ADDRESS',
        'APPLICANT_PERS_CONTACTS',
        'APPLICANT_PERS_FILIALS',
        'PRODUCT_LIST',
        'PRODUCT_BATCH',
        'PRODUCT_TECHLIST',
        'MANUFACTURER_INFO',
        'PRODUCT_BATCH_DOCUMENTS',
        'ProductExtraInfo',
        'EXPERT_INFO',
        'Attachments',
    ];

    protected $guarded = [];

    public static function rules()
    {
        return [
            'REG_NUMBER' => 'required',
        ];
    }
}