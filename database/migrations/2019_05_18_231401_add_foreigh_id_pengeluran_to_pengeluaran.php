<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeighIdPengeluranToPengeluaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bukti_pengeluarans', function (Blueprint $table) {
            $table->foreign('id_pengeluaran')->references('id')->on('pengeluarans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bukti_pengeluarans', function (Blueprint $table) {
            $table->dropForeign(['id_pengeluaran']);
        });
    }
}
