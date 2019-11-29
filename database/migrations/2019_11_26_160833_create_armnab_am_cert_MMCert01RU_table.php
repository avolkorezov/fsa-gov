<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArmnabAmCertMMCert01RUTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('armnab_am_certList_MMCert01RU', function(Blueprint $table){
            $table->increments('id');

            $table->string('STATUS')->nullable()->comment('Статус');

            $table->string('REG_NUMBER')->unique()->comment('Рег.номер');
            $table->string('VALIDFROM_DATE')->nullable()->comment('Срок действия с');
            $table->string('EXPIRATION_DATE')->nullable()->comment('По включительно');
            $table->string('SERIAL_NUMBER')->nullable()->comment('Рег.номер бланка');

            $table->string('APPLICANT_CORP_NAME')->nullable()
                ->comment('Данные о заявителе. Данные о юридическом лице. Название организации');
            $table->string('APPLICANT_CORP_LEADERNAME')->nullable()
                ->comment('Данные о заявителе. Данные о юридическом лице. Руководитель организации заявителя. Имя');
            $table->string('APPLICANT_CORP_LEADERLASTNAME')->nullable()
                ->comment('Данные о заявителе. Данные о юридическом лице. Руководитель организации заявителя. Фамилия');
            $table->string('APPLICANT_CORP_REGNUMBER')->nullable()
                ->comment('Данные о заявителе. Данные о юридическом лице. Рег. номер Гос.регистра');
            $table->string('APPLICANT_CORP_HVHH')->nullable()
                ->comment('Данные о заявителе. Данные о юридическом лице. УНН');
            $table->string('APPLICANT_CORP_PHONE')->nullable()
                ->comment('Данные о заявителе. Данные о юридическом лице. Телефон');
            $table->string('APPLICANT_CORP_FAX')->nullable()
                ->comment('Данные о заявителе. Данные о юридическом лице. Факс');
            $table->string('APPLICANT_CORP_EMAIL')->nullable()
                ->comment('Данные о заявителе. Данные о юридическом лице. Адрес электронной почты');
            $table->string('APPLICANT_CORP_ADDRESS1')->nullable()
                ->comment('Данные о заявителе. Данные о юридическом лице. Место нахождение (юридический адрес)');
            $table->string('APPLICANT_CORP_ADDRESS2')->nullable()
                ->comment('Данные о заявителе. Данные о юридическом лице. Место осуществления деятельности (фактический адрес)');

            $table->string('APPLICANT_PERS_NAME')->nullable()
                ->comment('Данные о заявителе. Данные об индивидульном предпринимателе. Имя');
            $table->string('APPLICANT_PERS_LASTNAME')->nullable()
                ->comment('Данные о заявителе. Данные об индивидульном предпринимателе. Фамилия');
            $table->string('APPLICANT_PERS_REGNUMBER')->nullable()
                ->comment('Данные о заявителе. Данные об индивидульном предпринимателе. Рег. номер Гос.регистра');
            $table->string('APPLICANT_PERS_HVHH')->nullable()
                ->comment('Данные о заявителе. Данные об индивидульном предпринимателе. УНН');
            $table->string('APPLICANT_PERS_PHONE')->nullable()
                ->comment('Данные о заявителе. Данные об индивидульном предпринимателе. Телефон');
            $table->string('APPLICANT_PERS_FAX')->nullable()
                ->comment('Данные о заявителе. Данные об индивидульном предпринимателе. Факс');
            $table->string('APPLICANT_PERS_EMAIL')->nullable()
                ->comment('Данные о заявителе. Данные об индивидульном предпринимателе. Адрес электронной почты');
            $table->string('APPLICANT_PERS_ADDRESS1')->nullable()
                ->comment('Данные о заявителе. Данные об индивидульном предпринимателе. Место нахождение (юридический адрес)');
            $table->string('APPLICANT_PERS_ADDRESS2')->nullable()
                ->comment('Данные о заявителе. Данные об индивидульном предпринимателе. Место осуществления деятельности (фактический адрес)');

            $table->string('MANUFACTURER_EXT_NAME')->nullable()
                ->comment('Данные о производителе. Данные об юридическом лице иностранного производителя. Название организации');
            $table->string('MANUFACTURER_EXT_COUNTRY')->nullable()
                ->comment('Данные о производителе. Данные об юридическом лице иностранного производителя. Страна');
            $table->string('MANUFACTURER_EXT_ADDRESS')->nullable()
                ->comment('Данные о производителе. Данные об юридическом лице иностранного производителя. Адрес');
            $table->string('MANUFACTURER_EXT_HVHH')->nullable()
                ->comment('Данные о производителе. Данные об юридическом лице иностранного производителя. УНН');
            $table->string('MANUFACTURER_EXT_PHONE')->nullable()
                ->comment('Данные о производителе. Данные об юридическом лице иностранного производителя. Телефон');
            $table->string('MANUFACTURER_EXT_FAX')->nullable()
                ->comment('Данные о производителе. Данные об юридическом лице иностранного производителя. Факс');
            $table->string('MANUFACTURER_EXT_EMAIL')->nullable()
                ->comment('Данные о производителе. Данные об юридическом лице иностранного производителя. Адрес электронной почты');

            $table->string('PRODUCT_NAME')->nullable()
                ->comment('Данные о продукции. Полное наименование продукции');
            $table->string('PRODUCT_SPECIFICATION')->nullable()
                ->comment('Данные о продукции. Информация о продуктции(тип, марка, модель, артикул и т.д)');

            $table->string('PRODUCT_TK_NAME')->nullable()
                ->comment('Данные о продукции. Название технического регламента');
            $table->string('PRODUCT_TK_REKVISIT')->nullable()
                ->comment('Данные о продукции. Реквизиты технического регламента');
            $table->string('PRODUCT_ST_NAME')->nullable()
                ->comment('Данные о продукции. Наименование стандарта');
            $table->string('PRODUCT_ST_REKVISIT')->nullable()
                ->comment('Данные о продукции. Реквизиты стандарта');
            $table->string('PRODUCT_CS_NAME')->nullable()
                ->comment('Данные о продукции. Наименование стандарта организации');
            $table->string('PRODUCT_CS_REKVISIT')->nullable()
                ->comment('Данные о продукции. Обозначение стандарта организации');

            $table->string('PRODUCT_CERTOBJECT_TYPE')->nullable()
                ->comment('Данные о продукции. Наименование объекта сертификации');
            $table->string('PRODUCT_BATCH')->nullable()
                ->comment('Данные о продукции. Размер партии');
            $table->string('PRODUCT_BATCH_DOCUMENTS')->nullable()
                ->comment('Данные о продукции. Реквизиты товарно-сопроводительной документации');

            $table->string('PRODUCT_MMATGAA')->nullable()
                ->comment('Данные о продукции. код ы ТН ВЭД ТС');
            $table->string('PRODUCT_TECHLIST')->nullable()
                ->comment('Данные о продукции. Наименование ТР ТС');

            $table->text('ProductTestReport')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Протоколы испытаний (номер, датa, наименования исп. лаб., рег. номер аттестата аккредитации и срок его действия)');
            $table->text('ProductOtherDocuments')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Другие документы');
            $table->text('ProductExtraInfo')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Дополнительная информация');



            $table->text('Attachments')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Приложение (приложения) к сертификату соответствия');

            $table->string('HGM_NAME')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Данные о ООС. Наименование ООС');
            $table->string('AC_NUMBER')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Данные о ООС. Рег.номер аттестата аккредитации');

            $table->string('HGM_LEADER_NAME')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Данные о ООС. Руководитель. Имя');
            $table->string('HGM_LEADER_LASTNAME')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Данные о ООС. Руководитель. Фамилия');
            $table->string('HGM_LEADER_FATHERNAME')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Данные о ООС. Руководитель. Отчество');

            $table->string('HGM_PHONE')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Данные о ООС. Телефон');
            $table->string('HGM_FAX')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Данные о ООС. Факс');
            $table->string('HGM_EMAIL')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Данные о ООС. Адрес электронной почты');
            $table->string('HGM_ADDRESS1')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Данные о ООС. Место нахождение (юридический адрес)');
            $table->string('HGM_ADDRESS2')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Данные о ООС. Место осуществления деятельности (фактический адрес)');

            $table->string('HGM_EXPERT_NAME')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Данные о ООС. Эксперт. Имя');
            $table->string('HGM_EXPERT_LASTNAME')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Данные о ООС. Эксперт. Фамилия');
            $table->string('HGM_EXPERT_FATHERNAME')->nullable()
                ->comment('Оснoвания для выдачи сертификата. Данные о ООС. Эксперт. Отчество');

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
        Schema::drop('armnab_am_certList_MMCert01RU');
    }
}
