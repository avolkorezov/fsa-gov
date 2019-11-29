<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArmnabAmCertRTRTS01001 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('armnab_am_certList_RTRTS01001', function(Blueprint $table){
            $table->increments('id');

            $table->string('Doc_Type')->nullable()->comment('Основная информация. Вид документа');
            $table->string('REG_NUMBER')->unique()->comment('Основная информация. Регистрационный номер');
            $table->string('VALIDFROM_DATE')->nullable()->comment('Основная информация. Срок действия с');
            $table->string('EXPIRATION_DATE')->nullable()->comment('Основная информация. по');
            $table->string('SERIAL_NUMBER')->nullable()->comment('Основная информация. Типографский номер бланка');
            $table->string('ORG_PO_OCENKE_SOOTVET')->nullable()->comment('Основная информация. Органа по оценке соответствия');
            $table->string('SCHEME_SERTIFIC')->nullable()->comment('Основная информация. Схема');
            $table->string('TYPE_OBJ_TR')->nullable()->comment('Основная информация. Наименование вида объекта технического регулирования');

            $table->string('STATUS')->nullable()
                ->comment('Основная информация. Статус. Статус действия документа');
            $table->string('STATUS_DATE_BEGIN')->nullable()
                ->comment('Основная информация. Статус. начальная дата действия статуса');

            $table->string('TK_REKVISIT')->nullable()
                ->comment('Основная информация. Реквизиты технического регламента. Реквизиты');

            $table->string('APPLICANT_PERS_NAME')->nullable()
                ->comment('Основная информация. Информация о заявителе. Наименование хозяйствующего субъекта');
            $table->string('APPLICANT_PERS_OPF')->nullable()
                ->comment('Основная информация. Информация о заявителе. Организационно-правовая форма');
            $table->string('APPLICANT_PERS_COUNTRY')->nullable()
                ->comment('Основная информация. Информация о заявителе. Страна');
            $table->string('APPLICANT_PERS_REGNUMBER')->nullable()
                ->comment('Основная информация. Информация о заявителе. Номер государственной регистрации');
            $table->string('APPLICANT_PERS_HVHH')->nullable()
                ->comment('Основная информация. Информация о заявителе. УНН');

            $table->longText('APPLICANT_PERS_ADDRESS')->nullable()
                ->comment('Адреса Заявителя / Контакты / Филиалы. Адрес(а)');
            $table->longText('APPLICANT_PERS_CONTACTS')->nullable()
                ->comment('Адреса Заявителя / Контакты / Филиалы. Контакт(ы)');
            $table->longText('APPLICANT_PERS_FILIALS')->nullable()
                ->comment('Адреса Заявителя / Контакты / Филиалы. Филиал(ы)');

            $table->longText('PRODUCT_LIST')->nullable()
                ->comment('Продукт. Таблица продуктов');
            $table->longText('PRODUCT_BATCH')->nullable()
                ->comment('Продукт. Информация о единице продукции');
            $table->longText('PRODUCT_TECHLIST')->nullable()
                ->comment('Продукт. Документ в соответствии с которым изготовлена продукция');

            $table->longText('MANUFACTURER_INFO')->nullable()
                ->comment('Изготовитель. Таблица');

            $table->longText('PRODUCT_BATCH_DOCUMENTS')->nullable()
                ->comment('Реквизиты товаросопроводительной документации. Таблица');

            $table->longText('ProductExtraInfo')->nullable()
                ->comment('Основания для выдачи сертификата. Таблица');

            $table->longText('EXPERT_INFO')->nullable()
                ->comment('Эксперт. Таблица');

            $table->longText('Attachments')->nullable()
                ->comment('Приложение. Таблица');

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
        Schema::drop('armnab_am_certList_RTRTS01001');
    }
}
