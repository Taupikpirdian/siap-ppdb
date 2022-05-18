<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSekolahIdOnCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->unsignedBigInteger('sekolah_id')->after('status_id');
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
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign('candidates_sekolah_id_foreign');
            $table->dropForeign(['sekolah_id']);
        });
    }
}
