<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenerimaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penerimaans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('kode_penerimaan', 10);
            $table->date('tgl_terima');
            $table->string('asal_penerimaan', 50);
            $table->string('nama_penerimaan', 50);
            $table->integer('jumlah')->nullable();
            $table->integer('giro')->nullable();
            $table->text('ket')->nullable();
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
        Schema::dropIfExists('penerimaans');
    }
}
