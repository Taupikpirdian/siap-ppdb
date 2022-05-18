<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSekolahIdOnCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('costs', function (Blueprint $table) {
            $table->unsignedBigInteger('sekolah_id')->after('name');
            $table->foreign('sekolah_id')->references('id')->on('sekolahs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('costs', function (Blueprint $table) {
            $table->dropForeign('costs_sekolah_id_foreign');
            $table->dropForeign(['sekolah_id']);
        });
    }
}
