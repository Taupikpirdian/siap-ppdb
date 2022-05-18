<?php

use Illuminate\Database\Seeder;
use App\Sekolah;

class SekolahTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sekolah = new Sekolah();
        $sekolah->id = 1;
        $sekolah->nama_sekolah = 'SMK CERIA SELALU';
        $sekolah->save();

        $sekolah = new Sekolah();
        $sekolah->id = 2;
        $sekolah->nama_sekolah = 'SMK SELALU SENYUM';
        $sekolah->save();

        $sekolah = new Sekolah();
        $sekolah->id = 3;
        $sekolah->nama_sekolah = 'SMK SELALU BAHAGIA';
        $sekolah->save();
    }
}
