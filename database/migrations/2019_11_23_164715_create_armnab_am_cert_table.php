<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArmnabAmCertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('armnab_am_cert', function(Blueprint $table){
            $table->increments('id');

            $table->string('AP_NUMBER')->unique()->comment('APNumber');

            $table->string('STATUS')->nullable()->comment('Статус');
            $table->string('HGM_NAME')->nullable()->comment('Название органа по сертификации');
            $table->text('Addresses')->nullable()->comment('Адрес(а) органа по сертификации');
            $table->string('PHONE')->nullable()->comment('Телефон');
            $table->string('FAX')->nullable()->comment('Факс');
            $table->string('EMAIL')->nullable()->comment('Адрес электронной почты');

            $table->string('HGM_LEADER_NAME')->nullable()->comment('Руководитель органа по сертификации. Имя');
            $table->string('HGM_LEADER_LASTNAME')->nullable()->comment('Руководитель органа по сертификации. Фамилия');
            $table->string('HGM_LEADER_FATHERNAME')->nullable()->comment('Руководитель органа по сертификации. Отчество');

            $table->longText('HGMSCOPE_DETAILS')->nullable()->comment('Подробное описание области аккредитации(с указанием обозначения ТР ТС)');
            $table->longText('MMATGAA')->nullable()->comment('коды ТН ВЭД ТС');

            $table->string('SCOPE_EXTEND_DATE')->nullable()->comment('Расширение. Дата вступление в силу решения о расширении области аккредитации');
            $table->text('SCOPE_EXTEND_CHANGES')->nullable()->comment('Расширение. Описание в части изменения области аккредитации');
            $table->text('SCOPE_EXTEND_MMATGAA')->nullable()->comment('Расширение. Коды ТН ВЭД ТС');

            $table->string('SCOPE_REDUCTION_DATE')->nullable()->comment('Сокращение. Дата вступление в силу решения о расширении области аккредитации');
            $table->text('SCOPE_REDUCTION_CHANGES')->nullable()->comment('Сокращение. Описание в части изменения области аккредитации');
            $table->text('SCOPE_REDUCTION_MMATGAA')->nullable()->comment('Сокращение. Коды ТН ВЭД ТС');

            $table->string('AC_NUMBER')->nullable()->comment('Аттестат об аккредитации. Рег.номер аттестата аккредитации');
            $table->string('AC_BLANKNUMBER')->nullable()->comment('Аттестат об аккредитации. Номер бланка');
            $table->string('AC_DECISIONNUMBER')->nullable()->comment('Аттестат об аккредитации. Номер решения');
            $table->string('AC_DECISIONDATE')->nullable()->comment('Аттестат об аккредитации. Дата решения');
            $table->string('AC_STARTDATE')->nullable()->comment('Аттестат об аккредитации. Дата выдачи');
            $table->string('AC_EXPIRATIONDATE')->nullable()->comment('Аттестат об аккредитации. Дата окончания');

            $table->string('SCOPE_SUSPENSION_DATE')->nullable()->comment('Приостановление. Дата приостановления действия атестата аккредитации');
            $table->text('SCOPE_SUSPENSION_CHANGES')->nullable()->comment('Приостановление. Причина для приостановления действия атестата аккредитации');

            $table->string('SCOPE_STOPAGE_DATE')->nullable()->comment('Аннулирование. Дата аннулирования атестата аккредитации');
            $table->text('SCOPE_STOPAGE_CHANGES')->nullable()->comment('Аннулирование. Причина для аннулирования атестата аккредитации');
            $table->text('AC_CHANGES')->nullable()->comment('Аннулирование. Данные о переоформлении аттестата аккредитации');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('armnab_am_cert');
    }
}
