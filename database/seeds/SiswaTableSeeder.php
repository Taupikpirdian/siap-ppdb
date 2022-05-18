<?php

use Illuminate\Database\Seeder;
use App\Siswa;

class SiswaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $siswa = new Siswa();
        $siswa->sekolah_id = 1;
        $siswa->program_id = 1;
        $siswa->thn_id = 1;
        $siswa->nama_siswa = 'Saepuloh';
        $siswa->status = 'Aktif';
        $siswa->save();

        $siswa = new Siswa();
        $siswa->sekolah_id = 2;
        $siswa->program_id = 2;
        $siswa->thn_id = 2;
        $siswa->nama_siswa = 'Jaenudin';
        $siswa->status = 'Aktif';
        $siswa->save();

        $siswa = new Siswa();
        $siswa->sekolah_id = 3;
        $siswa->program_id = 2;
        $siswa->thn_id = 2;
        $siswa->nama_siswa = 'Rifky';
        $siswa->status = 'Aktif';
        $siswa->save();
    }
}
