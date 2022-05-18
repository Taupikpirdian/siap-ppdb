<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdSekolahOnPengeluaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sekolah')->after('user_id');
            $table->foreign('id_sekolah')->references('id')->on('sekolahs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropForeign('pengeluarans_id_sekolah_foreign');
            $table->dropForeign(['id_sekolah']);
        });
    }
}
