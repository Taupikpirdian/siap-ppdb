<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SiswaFakeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

    	for($i = 1; $i <= 10; $i++){

    	      // insert data ke table pegawai menggunakan Faker
    		DB::table('siswas')->insert([
    			'id_sekolah' => $faker->numberBetween(25,40),
    			'npm' => $faker->numberBetween(25,40),
    			'rfid' => $faker->numberBetween(25,40),
    			'program' => $faker->numberBetween(25,40),
    			'tahun_masuk' => $faker->numberBetween(25,40),
    			'nama_siswa' => $faker->name,
    			'tempat_lahir' => $faker->numberBetween(25,40),
    			'tgl_lahir' => $faker->date,
    			'kelas' => $faker->numberBetween(25,40),
    			'subkelas' => $faker->numberBetween(25,40),
    			'nama_ayah' => $faker->name,
    			'hp_ayah' => $faker->numberBetween(25,40),
    			'nama_ibu' => $faker->name,
    			'hp_ibu' => $faker->numberBetween(25,40),
    			'nama_wali' => $faker->name,
    			'hp_wali' => $faker->numberBetween(25,40),
    			'alamat' => $faker->address,
    			'kecamatan' => $faker->numberBetween(25,40),
    			'kota_kab' => $faker->numberBetween(25,40),
    			'status' => $faker->numberBetween(25,40)
    		]);

    	}
    }
}
