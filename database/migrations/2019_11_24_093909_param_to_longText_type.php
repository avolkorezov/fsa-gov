<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ParamToLongTextType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE sources CHANGE  param param LONGTEXT;');
        Schema::table('sources', function(Blueprint $table){
            $table->longText('param')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE sources CHANGE  param param TEXT;');
        Schema::table('sources', function(Blueprint $table){
            $table->text('param')->change();
        });
    }
}
