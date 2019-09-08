<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RaoRfPub
 *
 * @property int $id
 * @property string $STATUS Статус
 * @property string $CERT_NUM Номер аттестата аккредитации
 * @property string $REG_DATE Дата внесения в реестр сведений об аккредитованном лице
 * @property string $END_DATE Срок действия
 * @property string $STATUS_DATE Дата приостановления действия
 * @property string $NUM_DECISION Номер решения об аккредитации
 * @property string $DATE_DECISION Дата решения об аккредитации
 * @property string $TYPE_DECLARANT Тип заявителя
 * @property string $FULL_NAME Полное наименование юридического лица
 * @property string $FIO ФИО руководителя юридического лица
 * @property string $ADDRESS Адреса места нахождения юридического лица
 * @property string $PHONE Номер телефона
 * @property string $FAX Номер факса
 * @property string $EMAIL Адрес электронной почты
 * @property string $GOS_REG_YR Государственный регистрационный номер записи о регистрации юридического лица
 * @property string $INN Идентификационный номер налогоплательщика
 * @property string $FIO_RUC_ACC_LICA ФИО руководителя аккредитованного лица
 * @property string $ADRESS_ACC_AREA Адрес мест осуществления деятельности в области аккредитации
 * @property string $ACC_AREA Область аккредитации
 * @property string $TR Технический регламент ТС
 * @property string $OKPD Коды ОКПД
 * @property string $OKP Коды ОКП
 * @property string $OKUN Коды ОКУН
 * @property string $TN_VAD Коды ТН ВЭД
 * @property string $SKAN Скан-копия области аккредитации
 * @property string $NC_TN_VAD_EAS Национальная часть-Коды ТН ВЭД ЕАЭС
 * @property string $NC_TR_EAS Национальная часть-Технический регламент ЕАЭС
 * @property string $NC_ACC_AREA Национальная часть-Область аккредитации
 * @property string $NUM_DECISION_INCR_ACC_AREA Номер решения о расширении области аккредитации
 * @property string $DATE_DECISION_INCR_ACC_AREA Дата решения о расширении области аккредитации
 * @property string $TR_INCR_ACC_AREA Технический регламент ТС расширяемой области аккредитации
 * @property string $TN_VAD_INCR_ACC_AREA Коды ТН ВЭД расширяемой области аккредитации
 * @property string $DESC_ACC_AREA Описание области аккредитации
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereACCAREA($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereADDRESS($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereADRESSACCAREA($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereCERTNUM($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereDATEDECISION($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereDATEDECISIONINCRACCAREA($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereDESCACCAREA($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereEMAIL($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereENDDATE($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereFAX($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereFIO($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereFIORUCACCLICA($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereFULLNAME($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereGOSREGYR($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereINN($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereNCACCAREA($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereNCTNVADEAS($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereNCTREAS($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereNUMDECISION($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereNUMDECISIONINCRACCAREA($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereOKP($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereOKPD($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereOKUN($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub wherePHONE($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereREGDATE($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereSKAN($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereSTATUS($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereSTATUSDATE($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereTNVAD($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereTNVADINCRACCAREA($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereTR($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereTRINCRACCAREA($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereTYPEDECLARANT($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RaoRfPub whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RaoRfPub extends Model
{
    protected $table = 'rao_rf_pub';

    public $timestamps = true;

    protected $fillable = [
        'IN_REESTR',
        'SHORT_NAME',
        'DOL_RUC_ACC_LICA',
        'TYPE_NAPRAVLENIYA',
        'NUM_RESHENIYA',
        'EXPERT_FIO',
        'REESTR_NUM',
        'EXPERT_ORG',
        'EXPERT_TEH',
        'Z_TYPE',
        'Z_FORM',
        'Z_FULL_NAME',
        'Z_NAME',
        'Z_INN',
        'Z_KPP',
        'Z_OGRN',
        'Z_ADRES',
        'Z_ORGAN',
        'Z_DATA_UCHET',
        'Z_FIO_RUK',
        'Z_DOLJNOST_RUK',
        'Z_PHONE',
        'Z_FAX',
        'Z_EMAIL',

        'STATUS',
        'CERT_NUM',
        'REG_DATE',
        'END_DATE',
        'STATUS_DATE',

        'NUM_DECISION',
        'DATE_DECISION',
        'TYPE_DECLARANT',

        'FULL_NAME',
        'FIO',
        'ADDRESS',

        'PHONE',
        'FAX',
        'EMAIL',
        'GOS_REG_YR',
        'INN',

        'FIO_RUC_ACC_LICA',
        'ADRESS_ACC_AREA',

        'ACC_AREA',
        'TR',
        'OKPD',
        'OKP',
        'OKUN',
        'TN_VAD',
        'SKAN',

        'NC_TN_VAD_EAS',
        'NC_TR_EAS',
        'NC_ACC_AREA',

        'NUM_DECISION_INCR_ACC_AREA',
        'DATE_DECISION_INCR_ACC_AREA',
        'TR_INCR_ACC_AREA',
        'TN_VAD_INCR_ACC_AREA',
        'DESC_ACC_AREA',
    ];

    protected $guarded = [];

    public static function rules()
    {
        return [
            'CERT_NUM' => 'required',
        ];
    }
        
}