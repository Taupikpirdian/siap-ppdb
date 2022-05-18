<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSekolahIdOnPembayaranSiswaAktifs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::table('pembayaran_siswa_aktifs', function (Blueprint $table) {
            $table->integer('sekolah_id')->after('payment_id');
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
            $table->dropColumn(['sekolah_id']);
        });
    }
}
