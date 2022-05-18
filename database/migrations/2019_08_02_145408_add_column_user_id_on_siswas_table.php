<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUserIdOnSiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->integer('user_id')->after('id')->nullable();
            $table->dropColumn(['candidate_id']);
            $table->integer('payment_status')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['user_id']);
            $table->string('candidate_id')->after('remainder')->nullable();
            $table->dropColumn(['payment_status']);
        });
    }
}
