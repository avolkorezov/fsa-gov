<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDataFieldsTypeIntoRssTsPubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rss_ts_pub', function(Blueprint $table){
            $table->date('a_date_begin')->change();
            $table->date('a_date_finish')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rss_ts_pub', function(Blueprint $table){
            $table->string('a_date_begin')->change();
            $table->string('a_date_finish')->change();
        });
    }
}
