<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->Increments('id');
            $table->smallInteger('id_sekolah');
            $table->string('npm', 20)->nullable();
            $table->string('rfid', 15)->nullable();
            $table->string('program', 10);
            $table->string('tahun_masuk', 4);
            $table->string('nama_siswa', 100);
            $table->string('tempat_lahir', 50)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('kelas', 5)->nullable();
            $table->string('subkelas', 10)->nullable();
            $table->string('nama_ayah', 30)->nullable();
            $table->string('hp_ayah', 20)->nullable();
            $table->string('nama_ibu', 30)->nullable();
            $table->string('hp_ibu', 20)->nullable();
            $table->string('nama_wali', 30)->nullable();
            $table->string('hp_wali', 20)->nullable();
            $table->string('alamat', 100)->nullable();
            $table->string('kecamatan', 30)->nullable();
            $table->string('kota_kab', 20)->nullable();
            $table->string('status', 20);
            $table->string('foto')->nullable();
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
        Schema::dropIfExists('siswas');
    }
}
