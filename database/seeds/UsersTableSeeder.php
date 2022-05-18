<?php

use Illuminate\Database\Seeder;
use App\Group;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group  = Group::where('name', 'Admin')->first();
        $user_admin = new User();
        $user_admin->name = 'Faiz Kaffah';
        $user_admin->email = 'admin@uninus.ac.id';
        $user_admin->password = bcrypt('aaa123');
        $user_admin->save();
        $user_admin->groups()->attach($group);

        $group  = Group::where('name', 'Bendahara')->first();
        $user_admin = new User();
        $user_admin->name = 'Noneng Nurhayati';
        $user_admin->email = 'bendahara@uninus.ac.id';
        $user_admin->password = bcrypt('aaa123');
        $user_admin->save();
        $user_admin->groups()->attach($group);

        $group  = Group::where('name', 'Kasir')->first();
        $user_admin = new User();
        $user_admin->name = 'Ujang';
        $user_admin->email = 'kasir@uninus.ac.id';
        $user_admin->password = bcrypt('aaa123');
        $user_admin->save();
        $user_admin->groups()->attach($group);

        $group  = Group::where('name', 'Kepala Keuangan')->first();
        $user_admin = new User();
        $user_admin->name = 'Rahman';
        $user_admin->email = 'kepalakeuangan@uninus.ac.id';
        $user_admin->password = bcrypt('aaa123');
        $user_admin->save();
        $user_admin->groups()->attach($group);

        $group  = Group::where('name', 'Yayasan')->first();
        $user_admin = new User();
        $user_admin->name = 'Cipto';
        $user_admin->email = 'yayasan@uninus.ac.id';
        $user_admin->password = bcrypt('aaa123');
        $user_admin->save();
        $user_admin->groups()->attach($group);
    }
}
