<?php

use Illuminate\Database\Seeder;
use App\JenisPenerimaan;	

class JenisPenerimaanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenis_penerimaan = new JenisPenerimaan();
        $jenis_penerimaan->nama = 'Tunai';
        $jenis_penerimaan->save();

        $jenis_penerimaan = new JenisPenerimaan();
        $jenis_penerimaan->nama = 'Cek';
        $jenis_penerimaan->save();

        $jenis_penerimaan = new JenisPenerimaan();
        $jenis_penerimaan->nama = 'Giro';
        $jenis_penerimaan->save();
    }
}
