<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddedDateOfExistenceDoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tsouz_belgiss_by', function(Blueprint $table){
            $table->date('DocStartDate')->nullable()->
            comment('СРОК ДЕЙСТВИЯ С');
            $table->date('DocValidityDate')->nullable()->
            comment('СРОК ДЕЙСТВИЯ ПО');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tsouz_belgiss_by', function(Blueprint $table){
            $table->dropColumn(['DocStartDate', 'DocValidityDate']);
        });
    }
}
