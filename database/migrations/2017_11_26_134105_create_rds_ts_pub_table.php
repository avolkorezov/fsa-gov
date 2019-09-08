<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRdsTsPubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rds_ts_pub', function(Blueprint $table){
            $table->increments('id');
            $table->string('STATUS')->nullable()->comment('Статус');
            $table->string('DECL_NUM')->unique()->comment('Номер декларации');

            $table->text('a_cert_type-cert_ts')->nullable()->comment('Выбор раздела. Раздел реестра');
            $table->text('a_cert_ts_type-ts')->nullable()->comment('Выбор раздела. Подраздел реестра');

            $table->text('a_applicant_org_type-ul')->nullable()->comment('Сведения о заявителе, изготовителе, продукции. Тип заявителя');
            $table->text('a_manufacturer_type-ul')->nullable()->comment('Сведения о заявителе, изготовителе, продукции. Тип изготовителя');

            $table->text('a_applicant_info-rds-app_legal_person-applicant_type')->nullable()->comment('Сведения о юридическом лице (заявитель). Тип декларанта');
            $table->text('a_applicant_info-rds-app_legal_person-name')->nullable()->comment('Сведения о юридическом лице (заявитель). Полное наименование');
            $table->text('a_applicant_info-rds-app_legal_person-director_name')->nullable()->comment('Сведения о юридическом лице (заявитель). ФИО руководителя');
            $table->text('a_applicant_info-rds-app_legal_person-address')->nullable()->comment('Сведения о юридическом лице (заявитель). Адрес места нахождения');
            $table->text('a_applicant_info-rds-app_legal_person-phone')->nullable()->comment('Сведения о юридическом лице (заявитель). Номер телефона');
            $table->text('a_applicant_info-rds-app_legal_person-fax')->nullable()->comment('Сведения о юридическом лице (заявитель). Номер факса');
            $table->text('a_applicant_info-rds-app_legal_person-email')->nullable()->comment('Сведения о юридическом лице (заявитель). Адрес электронной почты');
            $table->text('a_applicant_info-rds-app_legal_person-ogrn')->nullable()->comment('Сведения о юридическом лице (заявитель). Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН)');

            $table->text('a_manufacturer_info-rds-man_foreign_legal_person-name')->nullable()->comment('Сведения об изготовителе иностранном юридическом лице (изготовитель). Полное наименование');
            $table->text('a_manufacturer_info-rds-man_foreign_legal_person-address')->nullable()->comment('Сведения об изготовителе иностранном юридическом лице (изготовитель). Адрес места нахождения');
            $table->text('a_manufacturer_info-rds-man_foreign_legal_person-phone')->nullable()->comment('Сведения об изготовителе иностранном юридическом лице (изготовитель). Номер телефона');
            $table->text('a_manufacturer_info-rds-man_foreign_legal_person-fax')->nullable()->comment('Сведения об изготовителе иностранном юридическом лице (изготовитель). Номер факса');
            $table->text('a_manufacturer_info-rds-man_foreign_legal_person-email')->nullable()->comment('Сведения об изготовителе иностранном юридическом лице (изготовитель). Адрес электронной почты');

            $table->text('a_manufacturer_info-rds-man_legal_person-name')->nullable()->comment('Сведения о юридическом лице (изготовитель). Полное наименование');
            $table->text('a_manufacturer_info-rds-man_legal_person-address')->nullable()->comment('Сведения о юридическом лице (изготовитель). Адрес места нахождения');
            $table->text('a_manufacturer_info-rds-man_legal_person-address_actual')->nullable()->comment('Сведения о юридическом лице (изготовитель). Фактический адрес');
            $table->text('a_manufacturer_info-rds-man_legal_person-phone')->nullable()->comment('Сведения о юридическом лице (изготовитель). Номер телефона');
            $table->text('a_manufacturer_info-rds-man_legal_person-fax')->nullable()->comment('Сведения о юридическом лице (изготовитель). Номер факса');
            $table->text('a_manufacturer_info-rds-man_legal_person-email')->nullable()->comment('Сведения о юридическом лице (изготовитель). Адрес электронной почты');
            $table->text('a_manufacturer_info-rds-man_legal_person-ogrn')->nullable()->comment('Сведения о юридическом лице (изготовитель). Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН)');

            $table->text('cert_doc_issued-document_info')->nullable()->comment('Сведения о документах, на основании которых выдана декларация. Представленные документы');
            $table->text('cert_doc_issued-certification_scheme')->nullable()->comment('Сведения о документах, на основании которых выдана декларация. Схема декларирования');
            $table->text('cert_doc_issued-testing_lab-0-basis_for_certificate')->nullable()->comment('Сведения о документах, на основании которых выдана декларация. Основание выдачи декларации');

            $table->text('cert_doc_issued-testing_lab-1-basis_for_certificate')->nullable()->comment('Сведения об испытательной лаборатории. Основание выдачи декларации');
            $table->text('cert_doc_issued-testing_lab-0-reg_number')->nullable()->comment('Сведения об испытательной лаборатории. Регистрационный номер');
            $table->text('cert_doc_issued-testing_lab-0-name')->nullable()->comment('Сведения об испытательной лаборатории. Нaименование испытательной лаборатории (центра)');
            $table->text('cert_doc_issued-testing_lab-0-date_reg')->nullable()->comment('Сведения об испытательной лаборатории. Дата аккредитации');
//            $table->text('')->nullable()->comment('Сведения об испытательной лаборатории. Прочие документы, послужившие основанием выдачи сертификата');
            $table->text('a_cert_doc_issued-rds-cert_doc_issued-additional_info')->nullable()->comment('Сведения об испытательной лаборатории. Дополнительная информация');

            $table->text('a_product_info-rds-product_ts-object_type_cert')->nullable()->comment('Сведения о продукции. «Тип объекта декларирования»: серийный выпуск, партия, единичное изделие');
            $table->text('a_product_info-rds-product_ts-product_type')->nullable()->comment('Сведения о продукции. Происхождение продукции');
            $table->text('a_product_info-rds-product_ts-product_name')->nullable()->comment('Сведения о продукции. Полное наименование продукции');
            $table->text('a_product_info-rds-product_ts-product_info')->nullable()->comment('Сведения о продукции. Сведения о продукции (тип, марка, модель, сорт, артикул и др.), обеспечивающие ее идентификацию');
            $table->text('a_product_info-rds-product_ts-okpd2')->nullable()->comment('Сведения о продукции. Коды ОКПД 2');
            $table->text('a_product_info-rds-product_ts-okpd2_text')->nullable()->comment('Сведения о продукции. Коды ОКПД 2');
            $table->text('a_product_info-rds-product_ts-tn_ved')->nullable()->comment('Сведения о продукции. Код ТН ВЭД ЕАЭС');
            $table->text('a_product_info-rds-product_ts-tn_ved_text')->nullable()->comment('Сведения о продукции. Код ТН ВЭД ЕАЭС');
            $table->text('a_product_info-rds-product_ts-name_doc_made_product')->nullable()->comment('Сведения о продукции. Наименование и реквизиты документа, в соответствии с которыми изготовлена продукция');
            $table->text('a_product_info-rds-product_ts-product_info_ext')->nullable()->comment('Сведения о продукции. Иная информация, идентифицирующая продукцию');
            $table->text('a_product_info-rds-product_ts-serial_number_product')->nullable()->comment('Сведения о продукции. Размер партии или заводской номер изделия');
            $table->text('a_product_info-rds-product_ts-requisites_doc')->nullable()->comment('Сведения о продукции. Реквизиты товаросопроводительной документации');
            $table->text('tech_reg')->nullable()->comment('Сведения о продукции. Технический регламент');
            $table->text('tech_reg_ext')->nullable()->comment('Сведения о продукции. Дополнительные сведения о технических регламентах');

            $table->text('a_expert-0-last_name')->nullable()->comment('Cведения об экспертах по сертификации, проводивших работы по сертификации. Фамилия');
            $table->text('a_expert-0-first_name')->nullable()->comment('Cведения об экспертах по сертификации, проводивших работы по сертификации. Имя');
            $table->text('a_expert-0-patr_name')->nullable()->comment('Cведения об экспертах по сертификации, проводивших работы по сертификации. Отчество');

            $table->text('organ_to_certification-name')->nullable()->comment('Сведения об органе по сертификации. Полное наименование');
            $table->text('organ_to_certification-reg_number')->nullable()->comment('Сведения об органе по сертификации. Номер аттестата');
            $table->text('organ_to_certification-reg_date')->nullable()->comment('Сведения об органе по сертификации. Дата регистрации аттестата');
            $table->text('organ_to_certification-head_name')->nullable()->comment('Сведения об органе по сертификации. ФИО руководителя');
            $table->text('organ_to_certification-address')->nullable()->comment('Сведения об органе по сертификации. Юридический адрес');
            $table->text('organ_to_certification-address_actual')->nullable()->comment('Сведения об органе по сертификации. Адрес места нахождения');
            $table->text('organ_to_certification-phone')->nullable()->comment('Сведения об органе по сертификации. Номер телефона');
            $table->text('organ_to_certification-fax')->nullable()->comment('Сведения об органе по сертификации. Номер факса');
            $table->text('organ_to_certification-email')->nullable()->comment('Сведения об органе по сертификации. Адрес электронной почты');

            $table->text('a_info_pril')->nullable()->comment('Сведения о приложениях к сертификату. Сведения о приложении (приложениях) к сертификату');
            $table->text('a_apps-free_form')->nullable()->comment('Сведения о приложениях к сертификату. Свободная форма');
            $table->text('a_apps-table_standart')->nullable()->comment('Сведения о приложениях к сертификату. Таблица стандартов');
            $table->text('a_table_standart_number')->nullable()->comment('Сведения о приложениях к сертификату. Номер бланка приложения к СС для таблицы стандартов');
            $table->text('rds-table_standart_designation')->nullable()->comment('Сведения о приложениях к сертификату. Обозначение национального стандарта или свода правил');
            $table->text('rds-table_standart_name')->nullable()->comment('Сведения о приложениях к сертификату. Наименование национального стандарта или свода правил');
            $table->text('rds-table_standart_confirmation_requirements')->nullable()->comment('Сведения о приложениях к сертификату. Подтверждение требованиям национального стандарта или свода правил');
            $table->text('a_free_form')->nullable()->comment('Сведения о приложениях к сертификату. Прочие сведения о сертификате соответствия');

            $table->text('a_reg_number')->nullable()->comment('Заявитель. Сведения о государственной регистрации');
            $table->text('a_blank_number')->nullable()->comment('Реквизиты декларации. Номер бланка');
            $table->text('a_date_begin')->nullable()->comment('Реквизиты декларации. Дата начала действия');
            $table->text('a_date_finish')->nullable()->comment('Реквизиты декларации. Дата окончания действия');
            $table->text('a_is_date_finish')->nullable()->comment('Реквизиты декларации. Без срока действия');

            $table->text('conformityDocType')->nullable()->comment('Основные сведения. Тип декларации');
            $table->text('techregProductList')->nullable()->comment('Основные сведения. Группа продукции');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rds_ts_pub');
    }
}
