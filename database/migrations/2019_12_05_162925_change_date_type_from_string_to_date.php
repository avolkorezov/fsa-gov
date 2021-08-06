<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDateTypeFromStringToDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('armnab_am_cert', function(Blueprint $table){
            $table->date('SCOPE_EXTEND_DATE')->change()->comment('Расширение. Дата вступление в силу решения о расширении области аккредитации')->default(date("Y-m-d H:i:s"));
            $table->date('SCOPE_REDUCTION_DATE')->nullable()->change()->comment('Сокращение. Дата вступление в силу решения о расширении области аккредитации')->default(date("Y-m-d H:i:s"));
            $table->date('AC_DECISIONDATE')->nullable()->change()->comment('Аттестат об аккредитации. Дата решения')->default(date("Y-m-d H:i:s"));
            $table->date('AC_STARTDATE')->nullable()->change()->comment('Аттестат об аккредитации. Дата выдачи')->default(date("Y-m-d H:i:s"));
            $table->date('AC_EXPIRATIONDATE')->nullable()->change()->comment('Аттестат об аккредитации. Дата окончания')->default(date("Y-m-d H:i:s"));
            $table->date('SCOPE_SUSPENSION_DATE')->nullable()->change()->comment('Приостановление. Дата приостановления действия атестата аккредитации')->default(date("Y-m-d H:i:s"));
            $table->date('SCOPE_STOPAGE_DATE')->nullable()->change()->comment('Аннулирование. Дата аннулирования атестата аккредитации')->default(date("Y-m-d H:i:s"));
        });
        Schema::table('armnab_am_certList_MMCert01RU', function(Blueprint $table){
            $table->date('VALIDFROM_DATE')->nullable()->change()->comment('Срок действия с')->default(date("Y-m-d H:i:s"));
            $table->date('EXPIRATION_DATE')->nullable()->change()->comment('По включительно')->default(date("Y-m-d H:i:s"));
        });
        Schema::table('armnab_am_certList_RTRTS01001', function(Blueprint $table){
            $table->date('VALIDFROM_DATE')->nullable()->change()->comment('Основная информация. Срок действия с')->default(date("Y-m-d H:i:s"));
            $table->date('EXPIRATION_DATE')->nullable()->change()->comment('Основная информация. по')->default(date("Y-m-d H:i:s"));
            $table->date('STATUS_DATE_BEGIN')->nullable()->change()->comment('Основная информация. Статус. начальная дата действия статуса')->default(date("Y-m-d H:i:s"));
        });
        Schema::table('armnab_am_laboratory', function(Blueprint $table){
            $table->date('SCOPE_EXTEND_DATE')->nullable()->change()->comment('Расширение. Дата вступление в силу решения о расширении области аккредитации')->default(date("Y-m-d H:i:s"));
            $table->date('SCOPE_REDUCTION_DATE')->nullable()->change()->comment('Сокращение. Дата вступление в силу решения о расширении области аккредитации')->default(date("Y-m-d H:i:s"));
            $table->date('AC_DECISIONDATE')->nullable()->change()->comment('Аттестат об аккредитации. Дата решения')->default(date("Y-m-d H:i:s"));
            $table->date('AC_STARTDATE')->nullable()->change()->comment('Аттестат об аккредитации. Дата выдачи')->default(date("Y-m-d H:i:s"));
            $table->date('AC_EXPIRATIONDATE')->nullable()->change()->comment('Аттестат об аккредитации. Дата окончания')->default(date("Y-m-d H:i:s"));
            $table->date('SCOPE_SUSPENSION_DATE')->nullable()->change()->comment('Приостановление. Дата приостановления действия атестата аккредитации')->default(date("Y-m-d H:i:s"));
            $table->date('SCOPE_STOPAGE_DATE')->nullable()->change()->comment('Аннулирование. Дата аннулирования атестата аккредитации')->default(date("Y-m-d H:i:s"));
        });
        Schema::table('armnab_am_certList_Decl01RU', function(Blueprint $table){
            $table->date('VALIDFROM_DATE')->nullable()->change()->comment('Срок действия с')->default(date("Y-m-d H:i:s"));
            $table->date('EXPIRATION_DATE')->nullable()->change()->comment('По включительно')->default(date("Y-m-d H:i:s"));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('armnab_am_cert', function(Blueprint $table){
            $table->string('SCOPE_EXTEND_DATE')->nullable()->change()->comment('Расширение. Дата вступление в силу решения о расширении области аккредитации');
            $table->string('SCOPE_REDUCTION_DATE')->nullable()->change()->comment('Сокращение. Дата вступление в силу решения о расширении области аккредитации');
            $table->string('AC_DECISIONDATE')->nullable()->change()->comment('Аттестат об аккредитации. Дата решения');
            $table->string('AC_STARTDATE')->nullable()->change()->comment('Аттестат об аккредитации. Дата выдачи');
            $table->string('AC_EXPIRATIONDATE')->nullable()->change()->comment('Аттестат об аккредитации. Дата окончания');
            $table->string('SCOPE_SUSPENSION_DATE')->nullable()->change()->comment('Приостановление. Дата приостановления действия атестата аккредитации');
            $table->string('SCOPE_STOPAGE_DATE')->nullable()->change()->comment('Аннулирование. Дата аннулирования атестата аккредитации');
        });
        Schema::table('armnab_am_certList_MMCert01RU', function(Blueprint $table){
            $table->string('VALIDFROM_DATE')->nullable()->change()->comment('Срок действия с');
            $table->string('EXPIRATION_DATE')->nullable()->change()->comment('По включительно');
        });
        Schema::table('armnab_am_certList_RTRTS01001', function(Blueprint $table){
            $table->string('VALIDFROM_DATE')->nullable()->change()->comment('Основная информация. Срок действия с');
            $table->string('EXPIRATION_DATE')->nullable()->change()->comment('Основная информация. по');
            $table->string('STATUS_DATE_BEGIN')->nullable()->change()->comment('Основная информация. Статус. начальная дата действия статуса');
        });
        Schema::table('armnab_am_laboratory', function(Blueprint $table){
            $table->string('SCOPE_EXTEND_DATE')->nullable()->change()->comment('Расширение. Дата вступление в силу решения о расширении области аккредитации');
            $table->string('SCOPE_REDUCTION_DATE')->nullable()->change()->comment('Сокращение. Дата вступление в силу решения о расширении области аккредитации');
            $table->string('AC_DECISIONDATE')->nullable()->change()->comment('Аттестат об аккредитации. Дата решения');
            $table->string('AC_STARTDATE')->nullable()->change()->comment('Аттестат об аккредитации. Дата выдачи');
            $table->string('AC_EXPIRATIONDATE')->nullable()->change()->comment('Аттестат об аккредитации. Дата окончания');
            $table->string('SCOPE_SUSPENSION_DATE')->nullable()->change()->comment('Приостановление. Дата приостановления действия атестата аккредитации');
            $table->string('SCOPE_STOPAGE_DATE')->nullable()->change()->comment('Аннулирование. Дата аннулирования атестата аккредитации');
        });
        Schema::table('armnab_am_certList_Decl01RU', function(Blueprint $table){
            $table->string('VALIDFROM_DATE')->nullable()->change()->comment('Срок действия с');
            $table->string('EXPIRATION_DATE')->nullable()->change()->comment('По включительно');
        });
    }
}
