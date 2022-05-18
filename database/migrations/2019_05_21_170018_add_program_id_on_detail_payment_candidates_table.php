<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProgramIdOnDetailPaymentCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_payment_candidates', function (Blueprint $table) {
            $table->unsignedBigInteger('program_id')->after('thn_id')->unsigned();
            $table->foreign('program_id')->references('id')->on('programs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_payment_candidates', function (Blueprint $table) {
            $table->dropForeign('detail_payment_candidates_program_id_foreign');
            $table->dropForeign(['program_id']);
        });
    }
}
