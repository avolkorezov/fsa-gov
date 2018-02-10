<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProxyListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proxy_list', function(Blueprint $table){
            $table->increments('id');
            $table->string('proxy')->unique();
            $table->boolean('used')->default(0);
            $table->integer('fails')->default(0);
            $table->integer('useragent_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('proxy_list');
    }
}
