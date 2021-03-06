<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToUserSekolahs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_sekolahs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::table('user_sekolahs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['sekolah_id']);
        });
    }
}
