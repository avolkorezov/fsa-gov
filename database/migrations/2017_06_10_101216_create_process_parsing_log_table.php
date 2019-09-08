<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessParsingLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_parsing_log', function(Blueprint $table){
            $table->increments('id');
            $table->string('process');
            $table->dateTime('begin');
            $table->dateTime('end');
            $table->string('donor_class_name');
            $table->string('source');
            $table->dateTime('write_begin');
            $table->dateTime('write_end');
            $table->text('message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('process_parsing_log');
    }
}
