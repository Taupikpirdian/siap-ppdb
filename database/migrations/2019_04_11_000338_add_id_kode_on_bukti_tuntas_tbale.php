<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdKodeOnBuktiTuntasTbale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bukti_tuntas', function (Blueprint $table) {
            $table->integer('id_kode')->after('file')->unsigned();
            $table->foreign('id_kode')->references('id')->on('penerimaans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bukti_tuntas', function (Blueprint $table) {
            $table->dropColumn('id_kode');
        });
    }
}
