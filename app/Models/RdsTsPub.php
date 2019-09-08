<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RdsTsPub
 *
 * @property int $id
 * @property string $STATUS Статус
 * @property string $DECL_NUM Номер декларации
 * @property string $a_cert_type-cert_ts Выбор раздела. Раздел реестра
 * @property string $a_cert_ts_type-ts Выбор раздела. Подраздел реестра
 * @property string $a_applicant_org_type-ul Сведения о заявителе, изготовителе, продукции. Тип заявителя
 * @property string $a_manufacturer_type-ul Сведения о заявителе, изготовителе, продукции. Тип изготовителя
 * @property string $a_applicant_info-rds-app_legal_person-applicant_type Сведения о юридическом лице (заявитель). Тип декларанта
 * @property string $a_applicant_info-rds-app_legal_person-name Сведения о юридическом лице (заявитель). Полное наименование
 * @property string $a_applicant_info-rds-app_legal_person-director_name Сведения о юридическом лице (заявитель). ФИО руководителя
 * @property string $a_applicant_info-rds-app_legal_person-address Сведения о юридическом лице (заявитель). Адрес места нахождения
 * @property string $a_applicant_info-rds-app_legal_person-phone Сведения о юридическом лице (заявитель). Номер телефона
 * @property string $a_applicant_info-rds-app_legal_person-fax Сведения о юридическом лице (заявитель). Номер факса
 * @property string $a_applicant_info-rds-app_legal_person-email Сведения о юридическом лице (заявитель). Адрес электронной почты
 * @property string $a_applicant_info-rds-app_legal_person-ogrn Сведения о юридическом лице (заявитель). Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН)
 * @property string $a_manufacturer_info-rds-man_foreign_legal_person-name Сведения об изготовителе иностранном юридическом лице (изготовитель). Полное наименование
 * @property string $a_manufacturer_info-rds-man_foreign_legal_person-address Сведения об изготовителе иностранном юридическом лице (изготовитель). Адрес места нахождения
 * @property string $a_manufacturer_info-rds-man_foreign_legal_person-phone Сведения об изготовителе иностранном юридическом лице (изготовитель). Номер телефона
 * @property string $a_manufacturer_info-rds-man_foreign_legal_person-fax Сведения об изготовителе иностранном юридическом лице (изготовитель). Номер факса
 * @property string $a_manufacturer_info-rds-man_foreign_legal_person-email Сведения об изготовителе иностранном юридическом лице (изготовитель). Адрес электронной почты
 * @property string $a_manufacturer_info-rds-man_legal_person-name Сведения о юридическом лице (изготовитель). Полное наименование
 * @property string $a_manufacturer_info-rds-man_legal_person-address Сведения о юридическом лице (изготовитель). Адрес места нахождения
 * @property string $a_manufacturer_info-rds-man_legal_person-address_actual Сведения о юридическом лице (изготовитель). Фактический адрес
 * @property string $a_manufacturer_info-rds-man_legal_person-phone Сведения о юридическом лице (изготовитель). Номер телефона
 * @property string $a_manufacturer_info-rds-man_legal_person-fax Сведения о юридическом лице (изготовитель). Номер факса
 * @property string $a_manufacturer_info-rds-man_legal_person-email Сведения о юридическом лице (изготовитель). Адрес электронной почты
 * @property string $a_manufacturer_info-rds-man_legal_person-ogrn Сведения о юридическом лице (изготовитель). Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН)
 * @property string $cert_doc_issued-document_info Сведения о документах, на основании которых выдана декларация. Представленные документы
 * @property string $cert_doc_issued-certification_scheme Сведения о документах, на основании которых выдана декларация. Схема декларирования
 * @property string $cert_doc_issued-testing_lab-0-basis_for_certificate Сведения о документах, на основании которых выдана декларация. Основание выдачи декларации
 * @property string $cert_doc_issued-testing_lab-1-basis_for_certificate Сведения об испытательной лаборатории. Основание выдачи декларации
 * @property string $cert_doc_issued-testing_lab-0-reg_number Сведения об испытательной лаборатории. Регистрационный номер
 * @property string $cert_doc_issued-testing_lab-0-name Сведения об испытательной лаборатории. Нaименование испытательной лаборатории (центра)
 * @property string $cert_doc_issued-testing_lab-0-date_reg Сведения об испытательной лаборатории. Дата аккредитации
 * @property string $a_cert_doc_issued-rds-cert_doc_issued-additional_info Сведения об испытательной лаборатории. Дополнительная информация
 * @property string $a_product_info-rds-product_ts-object_type_cert Сведения о продукции. «Тип объекта декларирования»: серийный выпуск, партия, единичное изделие
 * @property string $a_product_info-rds-product_ts-product_type Сведения о продукции. Происхождение продукции
 * @property string $a_product_info-rds-product_ts-product_name Сведения о продукции. Полное наименование продукции
 * @property string $a_product_info-rds-product_ts-product_info Сведения о продукции. Сведения о продукции (тип, марка, модель, сорт, артикул и др.), обеспечивающие ее идентификацию
 * @property string $a_product_info-rds-product_ts-okpd2 Сведения о продукции. Коды ОКПД 2
 * @property string $a_product_info-rds-product_ts-okpd2_text Сведения о продукции. Коды ОКПД 2
 * @property string $a_product_info-rds-product_ts-tn_ved Сведения о продукции. Код ТН ВЭД ЕАЭС
 * @property string $a_product_info-rds-product_ts-tn_ved_text Сведения о продукции. Код ТН ВЭД ЕАЭС
 * @property string $a_product_info-rds-product_ts-name_doc_made_product Сведения о продукции. Наименование и реквизиты документа, в соответствии с которыми изготовлена продукция
 * @property string $a_product_info-rds-product_ts-product_info_ext Сведения о продукции. Иная информация, идентифицирующая продукцию
 * @property string $a_product_info-rds-product_ts-serial_number_product Сведения о продукции. Размер партии или заводской номер изделия
 * @property string $a_product_info-rds-product_ts-requisites_doc Сведения о продукции. Реквизиты товаросопроводительной документации
 * @property string $tech_reg Сведения о продукции. Технический регламент
 * @property string $tech_reg_ext Сведения о продукции. Дополнительные сведения о технических регламентах
 * @property string $a_expert-0-last_name Cведения об экспертах по сертификации, проводивших работы по сертификации. Фамилия
 * @property string $a_expert-0-first_name Cведения об экспертах по сертификации, проводивших работы по сертификации. Имя
 * @property string $a_expert-0-patr_name Cведения об экспертах по сертификации, проводивших работы по сертификации. Отчество
 * @property string $organ_to_certification-name Сведения об органе по сертификации. Полное наименование
 * @property string $organ_to_certification-reg_number Сведения об органе по сертификации. Номер аттестата
 * @property string $organ_to_certification-reg_date Сведения об органе по сертификации. Дата регистрации аттестата
 * @property string $organ_to_certification-head_name Сведения об органе по сертификации. ФИО руководителя
 * @property string $organ_to_certification-address Сведения об органе по сертификации. Юридический адрес
 * @property string $organ_to_certification-address_actual Сведения об органе по сертификации. Адрес места нахождения
 * @property string $organ_to_certification-phone Сведения об органе по сертификации. Номер телефона
 * @property string $organ_to_certification-fax Сведения об органе по сертификации. Номер факса
 * @property string $organ_to_certification-email Сведения об органе по сертификации. Адрес электронной почты
 * @property string $a_info_pril Сведения о приложениях к сертификату. Сведения о приложении (приложениях) к сертификату
 * @property string $a_apps-free_form Сведения о приложениях к сертификату. Свободная форма
 * @property string $a_apps-table_standart Сведения о приложениях к сертификату. Таблица стандартов
 * @property string $a_table_standart_number Сведения о приложениях к сертификату. Номер бланка приложения к СС для таблицы стандартов
 * @property string $rds-table_standart_designation Сведения о приложениях к сертификату. Обозначение национального стандарта или свода правил
 * @property string $rds-table_standart_name Сведения о приложениях к сертификату. Наименование национального стандарта или свода правил
 * @property string $rds-table_standart_confirmation_requirements Сведения о приложениях к сертификату. Подтверждение требованиям национального стандарта или свода правил
 * @property string $a_free_form Сведения о приложениях к сертификату. Прочие сведения о сертификате соответствия
 * @property string $a_reg_number Реквизиты декларации. Регистрационный номер
 * @property string $a_blank_number Реквизиты декларации. Номер бланка
 * @property string $a_date_begin Реквизиты декларации. Дата начала действия
 * @property string $a_date_finish Реквизиты декларации. Дата окончания действия
 * @property string $a_is_date_finish Реквизиты декларации. Без срока действия
 * @property string $conformityDocType Основные сведения. Тип декларации
 * @property string $techregProductList Основные сведения. Группа продукции
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAApplicantInfoRdsAppLegalPersonAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAApplicantInfoRdsAppLegalPersonApplicantType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAApplicantInfoRdsAppLegalPersonDirectorName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAApplicantInfoRdsAppLegalPersonEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAApplicantInfoRdsAppLegalPersonFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAApplicantInfoRdsAppLegalPersonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAApplicantInfoRdsAppLegalPersonOgrn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAApplicantInfoRdsAppLegalPersonPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAApplicantOrgTypeUl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAAppsFreeForm($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAAppsTableStandart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereABlankNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereACertDocIssuedRdsCertDocIssuedAdditionalInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereACertTsTypeTs($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereACertTypeCertTs($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereADateBegin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereADateFinish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAExpert0FirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAExpert0LastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAExpert0PatrName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAFreeForm($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAInfoPril($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAIsDateFinish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAManufacturerInfoRdsManForeignLegalPersonAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAManufacturerInfoRdsManForeignLegalPersonEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAManufacturerInfoRdsManForeignLegalPersonFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAManufacturerInfoRdsManForeignLegalPersonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAManufacturerInfoRdsManForeignLegalPersonPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAManufacturerInfoRdsManLegalPersonAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAManufacturerInfoRdsManLegalPersonAddressActual($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAManufacturerInfoRdsManLegalPersonEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAManufacturerInfoRdsManLegalPersonFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAManufacturerInfoRdsManLegalPersonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAManufacturerInfoRdsManLegalPersonOgrn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAManufacturerInfoRdsManLegalPersonPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAManufacturerTypeUl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAProductInfoRdsProductTsNameDocMadeProduct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAProductInfoRdsProductTsObjectTypeCert($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAProductInfoRdsProductTsOkpd2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAProductInfoRdsProductTsOkpd2Text($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAProductInfoRdsProductTsProductInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAProductInfoRdsProductTsProductInfoExt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAProductInfoRdsProductTsProductName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAProductInfoRdsProductTsProductType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAProductInfoRdsProductTsRequisitesDoc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAProductInfoRdsProductTsSerialNumberProduct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAProductInfoRdsProductTsTnVed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereAProductInfoRdsProductTsTnVedText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereARegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereATableStandartNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereCertDocIssuedCertificationScheme($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereCertDocIssuedDocumentInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereCertDocIssuedTestingLab0BasisForCertificate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereCertDocIssuedTestingLab0DateReg($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereCertDocIssuedTestingLab0Name($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereCertDocIssuedTestingLab0RegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereCertDocIssuedTestingLab1BasisForCertificate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereDECLNUM($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereOrganToCertificationAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereOrganToCertificationAddressActual($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereOrganToCertificationEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereOrganToCertificationFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereOrganToCertificationHeadName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereOrganToCertificationName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereOrganToCertificationPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereOrganToCertificationRegDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereOrganToCertificationRegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereRdsTableStandartConfirmationRequirements($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereRdsTableStandartDesignation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereRdsTableStandartName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereSTATUS($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereTechReg($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereTechRegExt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsTsPub whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RdsTsPub extends Model
{
    protected $table = 'rds_ts_pub';

    public $timestamps = true;

    protected $fillable = [
        'STATUS',
        'DECL_NUM',
        'a_cert_type-cert_ts',
        'a_cert_ts_type-ts',
        'a_applicant_org_type-ul',
        'a_manufacturer_type-ul',
        'a_applicant_info-rds-app_legal_person-applicant_type',
        'a_applicant_info-rds-app_legal_person-name',
        'a_applicant_info-rds-app_legal_person-director_name',
        'a_applicant_info-rds-app_legal_person-address',
        'a_applicant_info-rds-app_legal_person-phone',
        'a_applicant_info-rds-app_legal_person-fax',
        'a_applicant_info-rds-app_legal_person-email',
        'a_applicant_info-rds-app_legal_person-ogrn',
        'a_manufacturer_info-rds-man_foreign_legal_person-name',
        'a_manufacturer_info-rds-man_foreign_legal_person-address',
        'a_manufacturer_info-rds-man_foreign_legal_person-phone',
        'a_manufacturer_info-rds-man_foreign_legal_person-fax',
        'a_manufacturer_info-rds-man_foreign_legal_person-email',
        'a_manufacturer_info-rds-man_legal_person-name',
        'a_manufacturer_info-rds-man_legal_person-address',
        'a_manufacturer_info-rds-man_legal_person-address_actual',
        'a_manufacturer_info-rds-man_legal_person-phone',
        'a_manufacturer_info-rds-man_legal_person-fax',
        'a_manufacturer_info-rds-man_legal_person-email',
        'a_manufacturer_info-rds-man_legal_person-ogrn',
        'cert_doc_issued-document_info',
        'cert_doc_issued-certification_scheme',
        'cert_doc_issued-testing_lab-0-basis_for_certificate',
        'cert_doc_issued-testing_lab-1-basis_for_certificate',
        'cert_doc_issued-testing_lab-0-reg_number',
        'cert_doc_issued-testing_lab-0-name',
        'cert_doc_issued-testing_lab-0-date_reg',
        'a_cert_doc_issued-rds-cert_doc_issued-additional_info',
        'a_product_info-rds-product_ts-object_type_cert',
        'a_product_info-rds-product_ts-product_type',
        'a_product_info-rds-product_ts-product_name',
        'a_product_info-rds-product_ts-product_info',
        'a_product_info-rds-product_ts-okpd2',
        'a_product_info-rds-product_ts-okpd2_text',
        'a_product_info-rds-product_ts-tn_ved',
        'a_product_info-rds-product_ts-tn_ved_text',
        'a_product_info-rds-product_ts-name_doc_made_product',
        'a_product_info-rds-product_ts-product_info_ext',
        'a_product_info-rds-product_ts-serial_number_product',
        'a_product_info-rds-product_ts-requisites_doc',
        'tech_reg',
        'tech_reg_ext',
        'a_expert-0-last_name',
        'a_expert-0-first_name',
        'a_expert-0-patr_name',
        'organ_to_certification-name',
        'organ_to_certification-reg_number',
        'organ_to_certification-reg_date',
        'organ_to_certification-head_name',
        'organ_to_certification-address',
        'organ_to_certification-address_actual',
        'organ_to_certification-phone',
        'organ_to_certification-fax',
        'organ_to_certification-email',
        'a_info_pril',
        'a_apps-free_form',
        'a_apps-table_standart',
        'a_table_standart_number',
        'rds-table_standart_designation',
        'rds-table_standart_name',
        'rds-table_standart_confirmation_requirements',
        'a_free_form',
        'a_reg_number',
        'a_blank_number',
        'a_date_begin',
        'a_date_finish',
        'a_is_date_finish',
        'conformityDocType',
        'techregProductList'
    ];

    protected $guarded = [];

    public static function rules()
    {
        return [
            'DECL_NUM' => 'required',
        ];
    }

    public static function parse_cert_doc_issued_reg_number($string)
    {
        if (preg_match('%(\№| )([a-zA-Zа-яА-Я]{2}\.[a-zA-Zа-яА-Я]{2,}\.\d{2}[a-zA-Zа-яА-Я]{2}\d{2})%uis', $string, $match))
        {
            return $match[2];
        }
        return null;
    }
        
}