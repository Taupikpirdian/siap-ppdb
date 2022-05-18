<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUangKeluarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uang_keluars', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('siswa_id');
            $table->integer('payment_id');
            $table->integer('sekolah_id');
            $table->date('tgl_pengumuman')->nullable();
            $table->date('tgl_masuk')->nullable();
            $table->date('tgl_pengembalian');
            $table->integer('jumlah');
            $table->integer('denda');
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
        Schema::dropIfExists('uang_keluars');
    }
}
