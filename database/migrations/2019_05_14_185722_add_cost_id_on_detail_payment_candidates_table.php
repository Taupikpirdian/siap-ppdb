<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostIdOnDetailPaymentCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_payment_candidates', function (Blueprint $table) {
            $table->integer('cost_id')->after('payment_id');
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
            $table->dropColumn(['cost_id']);
        });
    }
}
