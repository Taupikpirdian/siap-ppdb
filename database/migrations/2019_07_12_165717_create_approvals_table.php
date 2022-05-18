<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('npm', 20)->nullable();
            $table->integer('semester');
            $table->date('tgl_bayar');
            $table->integer('payment_id')->unsigned();
            $table->foreign('payment_id')->references('id')->on('payment_types');
            $table->integer('sekolah_id');
            $table->integer('no_kwitansi')->nullable();
            $table->integer('amount');
            $table->text('ket');
            $table->string('status_approvals')->nullable();
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
        Schema::dropIfExists('approvals');
    }
}
