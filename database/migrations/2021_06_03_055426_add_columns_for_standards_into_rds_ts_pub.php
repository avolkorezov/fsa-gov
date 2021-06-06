<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsForStandardsIntoRdsTsPub extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rds_ts_pub', function(Blueprint $table){
            $table->string('a_product_info-standard_designation')->nullable()->comment('Обозначение стандарта, нормативного документ');
            $table->string('a_product_info-name_of_the_standard')->nullable()->comment('Наименование стандарта, нормативного документа');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rds_ts_pub', function(Blueprint $table){
            $table->dropColumn('a_product_info-standard_designation');
            $table->dropColumn('a_product_info-name_of_the_standard');
        });
    }
}
