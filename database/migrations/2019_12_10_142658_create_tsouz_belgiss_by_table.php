<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsouzBelgissByTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tsouz_belgiss_by', function(Blueprint $table){
            $table->increments('id');

            $table->string('certdecltr_id')->unique()->comment('certdecltr_id');

            $table->string('DocId')->nullable()->comment('Сведения о документе. Регистрационный номер');
            $table->string('ConformityDocKindCode')->nullable()->comment('Сведения о документе. Вид документа об оценке соответствия');
            $table->string('SingleListProductIndicator')->nullable()->comment('Сведения о документе. Признак включения продукции в единый перечень');
            $table->string('CertificationSchemeCode')->nullable()->comment('Сведения о документе. Схема сертификации (декларирования)');
            $table->longText('CertificationObjectCode')->nullable()->comment('Сведения о документе. Код объекта оценки соответствия');
            $table->longText('TechnicalRegulationId')->nullable()->comment('Сведения о документе. Номер технического регламента');
            $table->string('FormNumberId')->nullable()->comment('Сведения о документе. Типографский номер бланка (заполняется для сертификатов)');
            $table->string('DocStatusCode')->nullable()->
            comment('Сведения о документе. Статус действия сертификата (декларации). Код статуса действия сертификата (декларации)');
            $table->date('StartDate')->nullable()->
            comment('Сведения о документе. Статус действия сертификата (декларации). Начальная дата');
            $table->date('EndDate')->nullable()->
            comment('Сведения о документе. Статус действия сертификата (декларации). Конечная дата');
            $table->longText('NoteText')->nullable()->
            comment('Сведения о документе. Статус действия сертификата (декларации). Описание причины изменения статуса действия сертификата (декларации)');
            $table->string('FullNameDetails')->nullable()->comment('Сведения о документе. ФИО эксперта (эксперта-аудитора)');
            $table->longText('AdditionalInfoText')->nullable()->comment('Сведения о документе. Иная дополнительная информация');

            $table->string('ConformityAuthorityId')->nullable()->comment('Орган по оценке соответствия. Номер органа по оценке соответствия');
            $table->longText('BusinessEntityName')->nullable()->comment('Орган по оценке соответствия. Полное наименование органа по сертификации (из аттестата аккредитации)');
            $table->string('ConformityAuthorityV2Details_DocId')->nullable()->comment('Орган по оценке соответствия. Регистрационный номер');
            $table->date('ConformityAuthorityV2Details_DocCreationDate')->nullable()->comment('Орган по оценке соответствия. Дата выдачи аттестата аккредитации');
            $table->longText('OfficerDetails_PositionName')->nullable()->comment('Орган по оценке соответствия. Руководитель органа по оценке соответствия. Наименование должности');
            $table->string('OfficerDetails_FullNameDetails')->nullable()->comment('Орган по оценке соответствия. Руководитель органа по оценке соответствия. ФИО');
            $table->longText('OfficerDetails_CommunicationDetails')->nullable()->comment('Орган по оценке соответствия. Руководитель органа по оценке соответствия. Контактный реквизит');
            $table->longText('ConformityAuthorityV2Details_AddressV4Details')->nullable()->comment('Орган по оценке соответствия. Адрес органа по оценке соответствия');
            $table->longText('ConformityAuthorityV2Details_CommunicationDetails')->nullable()->comment('Орган по оценке соответствия. Контактный реквизит органа по оценке соответствия');

            $table->string('DocAnnexDetails_ObjectOrdinal')->nullable()->comment('Приложение к документу. Порядковый номер');
            $table->string('DocAnnexDetails_PageQuantity')->nullable()->comment('Приложение к документу. Количество листов');
            $table->longText('DocAnnexDetails_FormNumberId')->nullable()->comment('Приложение к документу. Номер бланка документа');

            $table->string('App_UnifiedCountryCode')->nullable()->comment('Заявитель. Страна');
            $table->string('App_BusinessEntityBriefName')->nullable()->comment('Заявитель. Краткое наименование хозяйствующего субъекта');
            $table->string('App_BusinessEntityId')->nullable()->comment('Заявитель. Идентификатор хозяйствующего субъекта');
            $table->string('App_BusinessEntityName')->nullable()->comment('Заявитель. Наименование хозяйствующего субъекта');
            $table->longText('App_SubjectAddressDetails')->nullable()->comment('Заявитель. Адрес заявителя');
            $table->longText('App_CommunicationDetails')->nullable()->comment('Заявитель. Контактный реквизит заявителя');
            $table->string('App_DeclaringOfficerDetails_FIO')->nullable()->comment('Заявитель. Лицо, принявшее декларацию. ФИО');
            $table->longText('App_DeclaringOfficerDetails_CommunicationDetails')->nullable()->comment('Заявитель. Лицо, принявшее декларацию. Контактный реквизит');
            $table->string('App_Declaring_DocInformationDetails_DocId')->nullable()->comment('Заявитель. Лицо, принявшее декларацию. Документ, на основании которого лицо уполномочено принимать декларацию. Номер документа');
            $table->string('App_Declaring_DocInformationDetails_DocName')->nullable()->comment('Заявитель. Лицо, принявшее декларацию. Документ, на основании которого лицо уполномочено принимать декларацию. Наименование документа');
            $table->date('App_Declaring_DocInformationDetails_DocCreationDate')->nullable()->comment('Заявитель. Лицо, принявшее декларацию. Документ, на основании которого лицо уполномочено принимать декларацию. Дата документа');

            $table->string('Manuf_UnifiedCountryCode')->nullable()->comment('Изготовитель. Страна');
            $table->string('Manuf_BusinessEntityBriefName')->nullable()->comment('Изготовитель. Краткое наименование хозяйствующего субъекта');
            $table->string('Manuf_BusinessEntityName')->nullable()->comment('Изготовитель. Наименование хозяйствующего субъекта');
            $table->longText('Manuf_AddressV4Details')->nullable()->comment('Изготовитель. Адрес изготовителя');
            $table->longText('Manuf_CommunicationDetails')->nullable()->comment('Изготовитель. Контактный реквизит изготовителя');

            $table->string('TechnicalRegulationObjectKindName')->nullable()->comment('Объект технического регулирования. Наименование вида объекта технического регулирования');
            $table->longText('ProductDetails')->nullable()->comment('Объект технического регулирования. Продукт');
            $table->longText('DocInformationDetails')->nullable()->comment('Объект технического регулирования. Реквизиты товаросопроводительной документации');

            $table->longText('ComplianceDocDetails')->nullable()->comment('Сведения о документе, подтверждающем соответствие');

            $table->longText('ComplianceProvidingDocDetails')->nullable()->comment('Примененные стандарты (документы)');

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
        Schema::drop('tsouz_belgiss_by');
    }
}
