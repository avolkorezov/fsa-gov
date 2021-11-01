<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProtocolTestingLab extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rds_ts_pub', function(Blueprint $table){
            $table->string('testingLabs-protocol_number')->nullable()->comment('Исследования, испытания, измерения. ПРОТОКОЛ ИССЛЕДОВАНИЯ (ИСПЫТАНИЯ) И ИЗМЕРЕНИЯ. Номер протокола');
            $table->string('testingLabs-protocol_date')->nullable()->comment('Исследования, испытания, измерения. ПРОТОКОЛ ИССЛЕДОВАНИЯ (ИСПЫТАНИЯ) И ИЗМЕРЕНИЯ. Дата протокола');
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
            $table->dropColumn('testingLabs-protocol_number');
            $table->dropColumn('testingLabs-protocol_date');
        });
    }
}
