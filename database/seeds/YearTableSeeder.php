<?php

use Illuminate\Database\Seeder;
use App\Year;

class YearTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $year = new Year();
        $year->id = 1;
        $year->tahun = '2019';
        $year->save();

        $year = new Year();
        $year->id = 2;
        $year->tahun = '2020';
        $year->save();
    }
}
