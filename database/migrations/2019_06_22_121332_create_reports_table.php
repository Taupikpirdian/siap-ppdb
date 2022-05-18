<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('npm', 20)->nullable();
            $table->smallInteger('sekolah_id');
            $table->integer('candidate_id')->nullable();
            $table->integer('form')->nullable();
            $table->integer('register')->nullable();
            // $table->integer('spp')->nullable();
            $table->integer('has_payment_spp')->nullable();
            // $table->integer('payment_amount')->nullable();
            // $table->string('arrears_status')->nullable();
            $table->date('last_date_payment')->nullable();
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
        Schema::dropIfExists('reports');
    }
}
