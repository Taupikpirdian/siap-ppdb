<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumnToBuktiTuntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bukti_tuntas', function (Blueprint $table) {
            $table->string('jumlah_penyaluran', 50)->after('user_id')->nullable();
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
            $table->dropColumn('jumlah_penyaluran');
        });
    }
}
