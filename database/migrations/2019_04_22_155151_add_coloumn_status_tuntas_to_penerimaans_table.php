<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumnStatusTuntasToPenerimaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('penerimaans', function (Blueprint $table) {
            $table->integer('status_tuntas')->after('ket')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penerimaans', function (Blueprint $table) {
            $table->dropColumn('status_tuntas');
        });
    }
}