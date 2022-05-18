<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(GroupTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(JenisPenerimaanTableSeeder::class);
        $this->call(SekolahTableSeeder::class);
        // $this->call(SiswaTableSeeder::class);
        $this->call(YearTableSeeder::class);
        $this->call(ProgramTableSeeder::class);
        $this->call(PaymentTypeTableSeeder::class);
        $this->call(CostTableSeeder::class);
    }
}
