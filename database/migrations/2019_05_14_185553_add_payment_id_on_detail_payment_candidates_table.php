<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentIdOnDetailPaymentCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_payment_candidates', function (Blueprint $table) {
            $table->integer('payment_id')->after('candidates_id')->unsigned();
            $table->foreign('payment_id')->references('id')->on('payment_types');
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
            $table->dropForeign('candidates_payment_id_foreign');
            $table->dropForeign(['payment_id']);
        });
    }
}
