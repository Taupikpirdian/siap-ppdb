<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuktiPengeluaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bukti_pengeluarans', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedBigInteger('id_pengeluaran');
            $table->integer('user_id');
            $table->integer('jumlah_pengeluaran');
            $table->string('file');
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
        Schema::dropIfExists('bukti_pengeluarans');
    }
}
