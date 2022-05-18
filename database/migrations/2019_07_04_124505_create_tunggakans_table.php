<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTunggakansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tunggakans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('npm', 20)->nullable();
            $table->integer('candidates_id')->nullable();
            $table->integer('sekolah_id')->nullable();
            $table->integer('spp')->nullable();
            $table->integer('payment_amount')->nullable();
            $table->integer('leftovers')->nullable();
            $table->string('arrears_status')->nullable();
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
        Schema::dropIfExists('tunggakans');
    }
}
