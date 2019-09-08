<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedNewFieldsIntoRaoRfPubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rao_rf_pub', function(Blueprint $table){
            $table->string('IN_REESTR')->nullable()->comment('Включен в национальную часть Единого реестра');
            $table->string('SHORT_NAME')->nullable()->comment('Сокращенное наименование аккредитованного лица');
            $table->string('DOL_RUC_ACC_LICA')->nullable()->comment('Должность руководителя аккредитованного лица');
            $table->string('TYPE_NAPRAVLENIYA')->nullable()->comment('Тип направления деятельности');
            $table->string('NUM_RESHENIYA')->nullable()->comment('Номер решения об аккредитации');
            $table->string('EXPERT_FIO')->nullable()->comment('ФИО эксперта по аккредитации');
            $table->string('REESTR_NUM')->nullable()->comment('Регистрационный номер записи в реестре экспертов по аккредитации');
            $table->string('EXPERT_ORG')->nullable()->comment('Экспертная организация');
            $table->string('EXPERT_TEH')->nullable()->comment('Технический эксперт');

            $table->string('Z_TYPE')->nullable()->comment('Тип заявителя');
            $table->string('Z_FORM')->nullable()->comment('Организационно-правовая форма юридического лица');
            $table->string('Z_FULL_NAME')->nullable()->comment('Полное наименование юридического лица');
            $table->string('Z_NAME')->nullable()->comment('Сокращенное наименование юридического лица');
            $table->string('Z_INN')->nullable()->comment('ИНН юридического лица');
            $table->string('Z_KPP')->nullable()->comment('КПП юридического лица');
            $table->string('Z_OGRN')->nullable()->comment('ОГРН юридического лица');
            $table->string('Z_ADRES')->nullable()->comment('Адрес места нахождения юридического лица');
            $table->string('Z_ORGAN')->nullable()->comment('Наименование налогового органа');
            $table->string('Z_DATA_UCHET')->nullable()->comment('Дата постановки на учет в налоговом органе');
            $table->string('Z_FIO_RUK')->nullable()->comment('ФИО руководителя юридического лица');
            $table->string('Z_DOLJNOST_RUK')->nullable()->comment('Должность руководителя юридического лица');
            $table->string('Z_PHONE')->nullable()->comment('Номер телефона юридического лица');
            $table->string('Z_FAX')->nullable()->comment('Номер факса юридического лица');
            $table->string('Z_EMAIL')->nullable()->comment('Адрес электронной почты юридического лица');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rao_rf_pub', function(Blueprint $table){
            $table->dropColumn("IN_REESTR");
            $table->dropColumn("SHORT_NAME");
            $table->dropColumn("DOL_RUC_ACC_LICA");
            $table->dropColumn("TYPE_NAPRAVLENIYA");
            $table->dropColumn("NUM_RESHENIYA");
            $table->dropColumn("EXPERT_FIO");
            $table->dropColumn("REESTR_NUM");
            $table->dropColumn("EXPERT_ORG");
            $table->dropColumn("EXPERT_TEH");

            $table->dropColumn("Z_TYPE");
            $table->dropColumn("Z_FORM");
            $table->dropColumn("Z_FULL_NAME");
            $table->dropColumn("Z_NAME");
            $table->dropColumn("Z_INN");
            $table->dropColumn("Z_KPP");
            $table->dropColumn("Z_OGRN");
            $table->dropColumn("Z_ADRES");
            $table->dropColumn("Z_ORGAN");
            $table->dropColumn("Z_DATA_UCHET");
            $table->dropColumn("Z_FIO_RUK");
            $table->dropColumn("Z_DOLJNOST_RUK");
            $table->dropColumn("Z_PHONE");
            $table->dropColumn("Z_FAX");
            $table->dropColumn("Z_EMAIL");
        });
    }
}
