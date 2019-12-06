<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSingleColumnInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('armnab_am_certList_RTRTS01001', function(Blueprint $table){
            $table->longText('APPLICANT_ADDRESS')->nullable()->comment('Место нахождения(юридический адрес)');
            $table->longText('APPLICANT_CONTACTS')->nullable()->comment('Контакты');
            $table->longText('PRODUCT_NAME')->nullable()->comment('Наименование продукции');
            $table->longText('PRODUCT_MMATGAA')->nullable()->comment('Код товара по ТН ВЭД ЕАЭС');
            $table->longText('MANUFACTURER_ADDRESS')->nullable()->comment('Юридический адрес изготовителя');
            $table->longText('MANUFACTURER_NAME')->nullable()->comment('Изготовитель');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('armnab_am_certList_RTRTS01001', function(Blueprint $table){
            $table->dropColumn('APPLICANT_ADDRESS');
            $table->dropColumn('APPLICANT_CONTACTS');
            $table->dropColumn('PRODUCT_NAME');
            $table->dropColumn('PRODUCT_MMATGAA');
            $table->dropColumn('MANUFACTURER_ADDRESS');
            $table->dropColumn('MANUFACTURER_NAME');
        });
    }
}
