<?php

use Illuminate\Database\Seeder;
use App\Program;

class ProgramTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $programs = new Program();
        $programs->id = 1;
        $programs->nama = 'Reguler';
        $programs->save();

        $programs = new Program();
        $programs->id = 2;
        $programs->nama = 'Khusus';
        $programs->save();

    }
}
