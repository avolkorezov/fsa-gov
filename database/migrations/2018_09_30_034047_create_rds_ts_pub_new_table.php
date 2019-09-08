<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRdsTsPubNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rds_ts_pub_new', function(Blueprint $table){
            $table->increments('id');
            $table->string('idStatus')->nullable()->comment('Статус');
            $table->string('number')->unique()->comment('Номер декларации');

            $table->text('declDate')->nullable()->comment('');
            $table->text('declEndDate')->nullable()->comment('');
            $table->text('declDraftDate')->nullable()->comment('');
            $table->text('technicalReglaments')->nullable()->comment('');
            $table->text('group')->nullable()->comment('');
            $table->text('productSingleList')->nullable()->comment('');
            $table->text('declType')->nullable()->comment('');
            $table->text('declObjectType')->nullable()->comment('');
            $table->text('awaitForApprove')->nullable()->comment('');
            $table->text('unpublishedChanges')->nullable()->comment('');
            $table->text('editApp')->nullable()->comment('');
            $table->text('applicantLegalSubjectType')->nullable()->comment('');
            $table->text('applicantType')->nullable()->comment('');
            $table->text('applicantName')->nullable()->comment('');
            $table->text('applicantAddress')->nullable()->comment('');
            $table->text('applicantOpf')->nullable()->comment('');
            $table->text('applicantFilialFullNames')->nullable()->comment('');
            $table->text('manufacterLegalSubjectType')->nullable()->comment('');
            $table->text('manufacterType')->nullable()->comment('');
            $table->text('manufacterName')->nullable()->comment('');
            $table->text('manufacterFilialFullNames')->nullable()->comment('');
            $table->text('certificationAuthorityAttestatRegNumber')->nullable()->comment('');
            $table->text('productOrig')->nullable()->comment('');
            $table->text('productFullName')->nullable()->comment('');
            $table->text('productBatchSize')->nullable()->comment('');
            $table->text('productIdentificationName')->nullable()->comment('');
            $table->text('productIdentificationType')->nullable()->comment('');
            $table->text('productIdentificationTrademark')->nullable()->comment('');
            $table->text('productIdentificationModel')->nullable()->comment('');
            $table->text('productIdentificationArticle')->nullable()->comment('');
            $table->text('productIdentificationSort')->nullable()->comment('');
            $table->text('productIdentificationGtin')->nullable()->comment('');
            $table->text('expertFio')->nullable()->comment('');
            $table->text('statusTestingLabs')->nullable()->comment('');
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
        Schema::drop('rds_ts_pub_new');
    }
}
