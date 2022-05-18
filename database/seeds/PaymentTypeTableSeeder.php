<?php

use Illuminate\Database\Seeder;
use App\PaymentType;

class PaymentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $programs = new PaymentType();
        $programs->id = 1;
        $programs->name = 'Formulir';
        $programs->alias = 'FRM';
        $programs->save();

        $programs = new PaymentType();
        $programs->id = 2;
        $programs->name = 'Daftar Ulang';
        $programs->alias = 'DU';
        $programs->save();

        $programs = new PaymentType();
        $programs->id = 3;
        $programs->name = 'SPP';
        $programs->alias = 'SPP';
        $programs->save();
    }
}
