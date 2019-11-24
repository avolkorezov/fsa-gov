<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sources', function(Blueprint $table){
            $table->increments('id');
            $table->index(['donor_class_name','source'],'filtr');
            $table->string('donor_class_name');
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->text('desc')->nullable();
            $table->string('hash', 32)->unique();
            $table->string('source')->unique();
            $table->boolean('parseit')->default(0);
            $table->boolean('available')->default(true);
            $table->integer('version')->default(0);
            $table->text('param')->nullable();
            $table->timestamps();
        });
//        \App\Models\Source::create([
//            'donor_class_name' => 'Rao_rf_pub',
//            'name' => 'Реестр аккредитованных лиц, включая Национальную часть Единого реестра органов по сертификации и испытательных лабораторий Таможенного союза',
//            'hash' => md5('http://public.fsa.gov.ru/table_rao_pub_rf/index.php'),
//            'source' => 'http://public.fsa.gov.ru/table_rao_pub_rf/index.php',
//            'version' => '1',
//        ]);
        \App\Models\Source::create([
            'donor_class_name' => 'Rao_rf_pub_new',
            'name' => 'Реестр аккредитованных лиц',
            'hash' => md5('https://pub.fsa.gov.ru/api/v1/ral/common/showcases/get'),
            'source' => 'https://pub.fsa.gov.ru/api/v1/ral/common/showcases/get',
            'version' => '1',
        ]);
        \App\Models\Source::create([
            'donor_class_name' => 'Rss_rf_pub',
            'name' => 'Единый реестр сертификатов соответствия',
            'hash' => md5('http://public.fsa.gov.ru/table_rss_pub_rf/'),
            'source' => 'http://public.fsa.gov.ru/table_rss_pub_rf/',
            'version' => '1',
        ]);
        \App\Models\Source::create([
            'donor_class_name' => 'Rss_ts_pub',
            'name' => 'Национальная часть единого реестра выданных сертификатов соответствия, оформленных по единой форме',
            'hash' => md5('http://public.fsa.gov.ru/table_rss_pub_ts/'),
            'source' => 'http://public.fsa.gov.ru/table_rss_pub_ts/',
            'version' => '1',
        ]);
        \App\Models\Source::create([
            'donor_class_name' => 'Rss_pub_gost_r',
            'name' => 'Реестр сертификатов соответствия продукции, включенной в Единый перечень продукции РФ',
            'hash' => md5('http://public.fsa.gov.ru/table_rss_pub_gost_r/'),
            'source' => 'http://public.fsa.gov.ru/table_rss_pub_gost_r/',
            'version' => '1',
        ]);
        \App\Models\Source::create([
            'donor_class_name' => 'Rds_rf_pub',
            'name' => 'Единый реестр деклараций о соответствии',
            'hash' => md5('http://public.fsa.gov.ru/table_rds_pub_rf/'),
            'source' => 'http://public.fsa.gov.ru/table_rds_pub_rf/',
            'version' => '1',
        ]);
//        \App\Models\Source::create([
//            'donor_class_name' => 'Rds_ts_pub',
//            'name' => 'Национальная часть единого реестра зарегистрированных деклараций о соответствии, оформленных по единой форме',
//            'hash' => md5('http://public.fsa.gov.ru/table_rds_pub_ts/'),
//            'source' => 'http://public.fsa.gov.ru/table_rds_pub_ts/',
//            'version' => '1',
//        ]);
        \App\Models\Source::create([
            'donor_class_name' => 'Rds_ts_pub_new',
            'name' => 'Национальная часть единого реестра зарегистрированных деклараций о соответствии, оформленных по единой форме',
            'hash' => md5('https://pub.fsa.gov.ru/api/v1/rds/common/declarations/get'),
            'source' => 'https://pub.fsa.gov.ru/api/v1/rds/common/declarations/get',
            'version' => '1',
        ]);
        \App\Models\Source::create([
            'donor_class_name' => 'Rss_rf_ts_gost_pub',
            'name' => 'Сертификаты соответствия',
            'hash' => md5('https://pub.fsa.gov.ru/api/v1/rss/common/certificates/get'),
            'source' => 'https://pub.fsa.gov.ru/api/v1/rss/common/certificates/get',
            'version' => '1',
        ]);
        \App\Models\Source::create([
            'donor_class_name' => 'Rds_pub_gost_r',
            'name' => 'Реестр деклараций о соответствии продукции, включенной в Единый перечень продукции РФ',
            'hash' => md5('http://public.fsa.gov.ru/table_rds_pub_gost_r/'),
            'source' => 'http://public.fsa.gov.ru/table_rds_pub_gost_r/',
            'version' => '1',
        ]);
        \App\Models\Source::create([
            'donor_class_name' => 'ArmnabAm_Cert',
            'name' => 'Национальный орган по аккредитации Армении',
            'hash' => md5('http://armnab.am/CertificationBodyListRU'),
            'source' => 'http://armnab.am/CertificationBodyListRU',
            'version' => '1',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sources');
    }
}
