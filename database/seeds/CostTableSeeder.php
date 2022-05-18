<?php

use Illuminate\Database\Seeder;
use App\Cost;

class CostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Costs = new Cost();
        $Costs->name = '4000000';
        $Costs->thn_id = 1;
        $Costs->sekolah_id = 1;
        $Costs->program_id = 1;
        $Costs->payment_id = 3;
        $Costs->save();

        $Costs = new Cost();
        $Costs->name = '3000000';
        $Costs->thn_id = 1;
        $Costs->sekolah_id = 2;
        $Costs->program_id = 1;
        $Costs->payment_id = 3;
        $Costs->save();

        $Costs = new Cost();
        $Costs->name = '3500000';
        $Costs->thn_id = 1;
        $Costs->sekolah_id = 3;
        $Costs->program_id = 1;
        $Costs->payment_id = 3;
        $Costs->save();
    }
}
