<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RssTsPub
 *
 * @property int $id
 * @property string $STATUS Статус
 * @property string $CERT_NUM Номер сертификата
 * @property string $a_cert_type-cert_ts Выбор раздела. Раздел реестра
 * @property string $a_cert_ts_type-ts Выбор раздела. Подраздел реестра
 * @property string $a_applicant_org_type-ul Сведения о заявителе, изготовителе, продукции. Тип заявителя
 * @property string $a_manufacturer_type-iul Сведения о заявителе, изготовителе, продукции. Тип изготовителя
 * @property string $a_applicant_info-rss-app_legal_person-applicant_type Сведения о юридическом лице (заявитель). Вид заявителя
 * @property string $a_applicant_info-rss-app_legal_person-name Сведения о юридическом лице (заявитель). Полное наименование
 * @property string $a_applicant_info-rss-app_legal_person-director_name Сведения о юридическом лице (заявитель). ФИО руководителя
 * @property string $a_applicant_info-rss-app_legal_person-address Сведения о юридическом лице (заявитель). Адрес места нахождения
 * @property string $a_applicant_info-rss-app_legal_person-phone Сведения о юридическом лице (заявитель). Номер телефона
 * @property string $a_applicant_info-rss-app_legal_person-fax Сведения о юридическом лице (заявитель). Номер факса
 * @property string $a_applicant_info-rss-app_legal_person-email Сведения о юридическом лице (заявитель). Адрес электронной почты
 * @property string $a_applicant_info-rss-app_legal_person-ogrn Сведения о юридическом лице (заявитель). Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН)
 * @property string $a_manufacturer_info-rss-man_foreign_legal_person-name Сведения об изготовителе иностранном юридическом лице (изготовитель). Полное наименование
 * @property string $a_manufacturer_info-rss-man_foreign_legal_person-address Сведения об изготовителе иностранном юридическом лице (изготовитель). Адрес места нахождения
 * @property string $a_manufacturer_info-rss-man_legal_person-name Сведения о юридическом лице (изготовитель). Полное наименование
 * @property string $a_manufacturer_info-rss-man_legal_person-address Сведения о юридическом лице (изготовитель). Адрес места нахождения
 * @property string $a_manufacturer_info-rss-man_legal_person-phone Сведения о юридическом лице (изготовитель). Номер телефона
 * @property string $a_manufacturer_info-rss-man_legal_person-fax Сведения о юридическом лице (изготовитель). Номер факса
 * @property string $a_manufacturer_info-rss-man_legal_person-email Сведения о юридическом лице (изготовитель). Адрес электронной почты
 * @property string $a_manufacturer_info-rss-man_legal_person-ogrn Сведения о юридическом лице (изготовитель). Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН)
 * @property string $cert_doc_issued-document_info Сведения о документах, на основании которых выдан сертификат. Представленные документы
 * @property string $cert_doc_issued-testing_lab-0-basis_for_certificate Сведения о документах, на основании которых выдан сертификат. Основание выдачи сертификата
 * @property string $cert_doc_issued-testing_lab-1-basis_for_certificate Сведения об испытательной лаборатории. Основание выдачи сертификата
 * @property string $cert_doc_issued-testing_lab-0-reg_number Сведения об испытательной лаборатории. Регистрационный номер аттестата аккредитации
 * @property string $a_cert_doc_issued-rss-cert_doc_issued-additional_info Сведения об испытательной лаборатории. Дополнительная информация
 * @property string $a_product_info-rss-product_ts-object_type_cert Сведения о продукции. «Тип объекта сертификации»: серийный выпуск, партия, единичное изделие
 * @property string $a_product_info-rss-product_ts-product_type Сведения о продукции. Вид продукции
 * @property string $a_product_info-rss-product_ts-product_name Сведения о продукции. Полное наименование продукции
 * @property string $a_product_info-rss-product_ts-product_info Сведения о продукции. Сведения о продукции (тип, марка, модель, сорт, артикул и др.), обеспечивающие ее идентификацию
 * @property string $a_product_info-rss-product_ts-okpd2 Сведения о продукции. Коды ОКПД 2
 * @property string $a_product_info-rss-product_ts-okpd2_text Сведения о продукции. Коды ОКПД 2
 * @property string $a_product_info-rss-product_ts-tn_ved Сведения о продукции. Код ТН ВЭД ЕАЭС
 * @property string $a_product_info-rss-product_ts-tn_ved_text Сведения о продукции. Код ТН ВЭД ЕАЭС
 * @property string $a_product_info-rss-product_ts-name_doc_made_product Сведения о продукции. Наименование и реквизиты документа, в соответствии с которыми изготовлена продукция
 * @property string $a_product_info-rss-product_ts-product_info_ext Сведения о продукции. Иная информация, идентифицирующая продукцию
 * @property string $a_product_info-rss-product_ts-serial_number_product Сведения о продукции. Размер партии или заводской номер изделия
 * @property string $a_product_info-rss-product_ts-requisites_doc Сведения о продукции. Реквизиты товаросопроводительной документации
 * @property string $tech_reg Сведения о документах, на основании которых изготовлена продукция. Технический регламент
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
 * @property string $rss-table_standart_designation Сведения о приложениях к сертификату. Обозначение национального стандарта или свода правил
 * @property string $rss-table_standart_name Сведения о приложениях к сертификату. Наименование национального стандарта или свода правил
 * @property string $rss-table_standart_confirmation_requirements Сведения о приложениях к сертификату. Подтверждение требованиям национального стандарта или свода правил
 * @property string $a_free_form Сведения о приложениях к сертификату. Прочие сведения о сертификате соответствия
 * @property string $a_reg_number Реквизиты сертификата. Регистрационный номер
 * @property string $a_blank_number Реквизиты сертификата. Номер бланка
 * @property string $a_date_begin Реквизиты сертификата. Дата начала действия
 * @property string $a_date_finish Реквизиты сертификата. Дата окончания действия
 * @property string $a_is_date_finish Реквизиты сертификата. Без срока действия
 * @property string $conformityDocType Основные сведения. Тип сертификата
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAApplicantInfoRssAppLegalPersonAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAApplicantInfoRssAppLegalPersonApplicantType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAApplicantInfoRssAppLegalPersonDirectorName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAApplicantInfoRssAppLegalPersonEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAApplicantInfoRssAppLegalPersonFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAApplicantInfoRssAppLegalPersonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAApplicantInfoRssAppLegalPersonOgrn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAApplicantInfoRssAppLegalPersonPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAApplicantOrgTypeUl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAAppsFreeForm($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAAppsTableStandart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereABlankNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereACertDocIssuedRssCertDocIssuedAdditionalInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereACertTsTypeTs($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereACertTypeCertTs($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereADateBegin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereADateFinish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAExpert0FirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAExpert0LastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAExpert0PatrName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAFreeForm($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAInfoPril($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAIsDateFinish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAManufacturerInfoRssManForeignLegalPersonAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAManufacturerInfoRssManForeignLegalPersonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAManufacturerInfoRssManLegalPersonAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAManufacturerInfoRssManLegalPersonEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAManufacturerInfoRssManLegalPersonFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAManufacturerInfoRssManLegalPersonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAManufacturerInfoRssManLegalPersonOgrn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAManufacturerInfoRssManLegalPersonPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAManufacturerTypeIul($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAProductInfoRssProductTsNameDocMadeProduct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAProductInfoRssProductTsObjectTypeCert($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAProductInfoRssProductTsOkpd2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAProductInfoRssProductTsOkpd2Text($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAProductInfoRssProductTsProductInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAProductInfoRssProductTsProductInfoExt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAProductInfoRssProductTsProductName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAProductInfoRssProductTsProductType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAProductInfoRssProductTsRequisitesDoc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAProductInfoRssProductTsSerialNumberProduct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAProductInfoRssProductTsTnVed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereAProductInfoRssProductTsTnVedText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereARegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereATableStandartNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereCERTNUM($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereCertDocIssuedDocumentInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereCertDocIssuedTestingLab0BasisForCertificate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereCertDocIssuedTestingLab0RegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereCertDocIssuedTestingLab1BasisForCertificate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereOrganToCertificationAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereOrganToCertificationAddressActual($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereOrganToCertificationEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereOrganToCertificationFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereOrganToCertificationHeadName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereOrganToCertificationName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereOrganToCertificationPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereOrganToCertificationRegDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereOrganToCertificationRegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereRssTableStandartConfirmationRequirements($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereRssTableStandartDesignation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereRssTableStandartName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereSTATUS($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereTechReg($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssTsPub whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RssTsPub extends Model
{
    protected $table = 'rss_ts_pub';

    public $timestamps = true;

    protected $fillable = [
        'STATUS',
        'CERT_NUM',
        'a_cert_type-cert_ts',
        'a_cert_ts_type-ts',
        'a_applicant_org_type-ul',
        'a_manufacturer_type-iul',
        'a_applicant_info-rss-app_legal_person-applicant_type',
        'a_applicant_info-rss-app_legal_person-name',
        'a_applicant_info-rss-app_legal_person-director_name',
        'a_applicant_info-rss-app_legal_person-address',
        'a_applicant_info-rss-app_legal_person-phone',
        'a_applicant_info-rss-app_legal_person-fax',
        'a_applicant_info-rss-app_legal_person-email',
        'a_applicant_info-rss-app_legal_person-ogrn',
        'a_manufacturer_info-rss-man_foreign_legal_person-name',
        'a_manufacturer_info-rss-man_foreign_legal_person-address',
        'a_manufacturer_info-rss-man_legal_person-name',
        'a_manufacturer_info-rss-man_legal_person-address',
        'cert_doc_issued-document_info',
        'cert_doc_issued-testing_lab-0-basis_for_certificate',
        'cert_doc_issued-testing_lab-1-basis_for_certificate',
        'cert_doc_issued-testing_lab-0-reg_number',
        'a_cert_doc_issued-rss-cert_doc_issued-additional_info',
        'a_product_info-rss-product_ts-object_type_cert',
        'a_product_info-rss-product_ts-product_type',
        'a_product_info-rss-product_ts-product_name',
        'a_product_info-rss-product_ts-product_info',
        'a_product_info-rss-product_ts-tn_ved',
        'a_product_info-rss-product_ts-tn_ved_text',
        'a_product_info-rss-product_ts-name_doc_made_product',
        'a_product_info-rss-product_ts-product_info_ext',
        'tech_reg',
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
        'rss-table_standart_designation',
        'rss-table_standart_name',
        'rss-table_standart_confirmation_requirements',
        'a_free_form',
        'a_reg_number',
        'a_blank_number',
        'a_date_begin',
        'a_date_finish',
        'a_is_date_finish',
        'conformityDocType'
    ];

    protected $guarded = [];

    public static function rules()
    {
        return [
            'CERT_NUM' => 'required',
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