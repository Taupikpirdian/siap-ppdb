<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThnIdOnDetailPaymentCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_payment_candidates', function (Blueprint $table) {
            $table->integer('thn_id')->after('sekolah_id')->unsigned();
            $table->foreign('thn_id')->references('id')->on('years');
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
            $table->dropForeign('detail_payment_candidates_thn_id_foreign');
            $table->dropForeign(['thn_id']);
        });
    }
}
