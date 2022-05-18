<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSekolahIdOnDetailPaymentCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_payment_candidates', function (Blueprint $table) {
            $table->unsignedBigInteger('sekolah_id')->after('cost_id');
            $table->foreign('sekolah_id')->references('id')->on('sekolahs');
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
            $table->dropForeign('detail_payment_candidates_sekolah_id_foreign');
            $table->dropForeign(['sekolah_id']);
        });
    }
}
