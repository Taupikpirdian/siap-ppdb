<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSiswaIdOnSiswas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran_siswa_aktifs', function (Blueprint $table) {
            $table->string('npm', 20)->after('id')->nullable();
            $table->integer('payment_id')->after('tgl_bayar')->unsigned();
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
        Schema::table('pembayaran_siswa_aktifs', function (Blueprint $table) {
            $table->dropColumn('npm');
            $table->dropForeign(['payment_id']);
            $table->dropColumn('payment_id');
        });
    }
}
