<?php

use Illuminate\Database\Seeder;
use App\Group;
use App\Role;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$role_list_group  = Role::where('name', 'List Groups')->first();
        $role_create_group  = Role::where('name', 'Create Group')->first();
        $role_details_group  = Role::where('name', 'Details Group')->first();
        $role_edit_group  = Role::where('name', 'Edit Group')->first();
        $role_delete_group  = Role::where('name', 'Delete Group')->first();
        $role_search_group  = Role::where('name', 'Search Group')->first();

        $role_list_role  = Role::where('name', 'List Roles')->first();
        $role_create_role  = Role::where('name', 'Create Role')->first();
        $role_details_role  = Role::where('name', 'Details Role')->first();
        $role_edit_role  = Role::where('name', 'Edit Role')->first();
        $role_delete_role  = Role::where('name', 'Delete Role')->first();
        $role_search_role  = Role::where('name', 'Search Role')->first();

        $role_list_user_group  = Role::where('name', 'List User Groups')->first();
        $role_create_user_group  = Role::where('name', 'Create User Group')->first();
        $role_details_user_group  = Role::where('name', 'Details User Group')->first();
        $role_edit_user_group  = Role::where('name', 'Edit User Group')->first();
        $role_delete_user_group  = Role::where('name', 'Delete User Group')->first();
        $role_search_user_group  = Role::where('name', 'Search User Group')->first();

        $role_list_group_role  = Role::where('name', 'List Group Roles')->first();
        $role_create_group_role  = Role::where('name', 'Create Group Role')->first();
        $role_details_group_role  = Role::where('name', 'Details Group Role')->first();
        $role_edit_group_role  = Role::where('name', 'Edit Group Role')->first();
        $role_delete_group_role  = Role::where('name', 'Delete Group Role')->first();
        $role_search_group_role  = Role::where('name', 'Search Group Role')->first();

        $sekolah_list  = Role::where('name', 'List Sekolah')->first();
        $sekolah_create  = Role::where('name', 'Create Sekolah')->first();
        $sekolah_detail  = Role::where('name', 'Details Sekolah')->first();
        $sekolah_edit  = Role::where('name', 'Edit Sekolah')->first();
        $sekolah_delete  = Role::where('name', 'Delete Sekolah')->first();
        $sekolah_search  = Role::where('name', 'Search Sekolah')->first();

        $siswa_list  = Role::where('name', 'List Siswa')->first();
        $siswa_create  = Role::where('name', 'Create Siswa')->first();
        $siswa_detail  = Role::where('name', 'Details Siswa')->first();
        $siswa_edit  = Role::where('name', 'Edit Siswa')->first();
        $siswa_delete  = Role::where('name', 'Delete Siswa')->first();
        $siswa_search  = Role::where('name', 'Search Siswa')->first();

        $penerimaan_list  = Role::where('name', 'List Penerimaan')->first();
        $penerimaan_create  = Role::where('name', 'Create Penerimaan')->first();
        $penerimaan_detail  = Role::where('name', 'Details Penerimaan')->first();
        $penerimaan_edit  = Role::where('name', 'Edit Penerimaan')->first();
        $penerimaan_delete  = Role::where('name', 'Delete Penerimaan')->first();
        $penerimaan_search  = Role::where('name', 'Search Penerimaan')->first();

        $pengeluaran_list  = Role::where('name', 'List Pengeluaran')->first();
        $pengeluaran_create  = Role::where('name', 'Create Pengeluaran')->first();
        $pengeluaran_detail  = Role::where('name', 'Details Pengeluaran')->first();
        $pengeluaran_edit  = Role::where('name', 'Edit Pengeluaran')->first();
        $pengeluaran_delete  = Role::where('name', 'Delete Pengeluaran')->first();
        $pengeluaran_search  = Role::where('name', 'Search Pengeluaran')->first();

        $bukti_tuntas_list  = Role::where('name', 'List Bukti Tuntas')->first();
        $bukti_tuntas_create  = Role::where('name', 'Create Bukti Tuntas')->first();
        $bukti_tuntas_detail  = Role::where('name', 'Details Bukti Tuntas')->first();
        $bukti_tuntas_edit  = Role::where('name', 'Edit Bukti Tuntas')->first();
        $bukti_tuntas_delete  = Role::where('name', 'Delete Bukti Tuntas')->first();
        $bukti_tuntas_search  = Role::where('name', 'Search Bukti Tuntas')->first();

        $biaya_list  = Role::where('name', 'List Biaya')->first();
        $biaya_create  = Role::where('name', 'Create Biaya')->first();
        $biaya_detail  = Role::where('name', 'Details Biaya')->first();
        $biaya_edit  = Role::where('name', 'Edit Biaya')->first();
        $biaya_delete  = Role::where('name', 'Delete Biaya')->first();
        $biaya_search  = Role::where('name', 'Search Biaya')->first();

        $calon_list  = Role::where('name', 'List Calon')->first();
        $calon_create  = Role::where('name', 'Create Calon')->first();
        $calon_detail  = Role::where('name', 'Details Calon')->first();
        $calon_edit  = Role::where('name', 'Edit Calon')->first();
        $calon_delete  = Role::where('name', 'Delete Calon')->first();
        $calon_search  = Role::where('name', 'Search Calon')->first();
        $calon_aktif  = Role::where('name', 'Aktif Calon')->first();
        $calon_bayar  = Role::where('name', 'Bayar Calon')->first();

        $jenis_bayar_list  = Role::where('name', 'List Jenis Bayar')->first();
        $jenis_bayar_create  = Role::where('name', 'Create Jenis Bayar')->first();
        $jenis_bayar_detail  = Role::where('name', 'Details Jenis Bayar')->first();
        $jenis_bayar_edit  = Role::where('name', 'Edit Jenis Bayar')->first();
        $jenis_bayar_delete  = Role::where('name', 'Delete Jenis Bayar')->first();
        $jenis_bayar_search  = Role::where('name', 'Search Jenis Bayar')->first();

        $bukti_pengeluaran_list  = Role::where('name', 'List Bukti Pengeluaran')->first();
        $bukti_pengeluaran_create  = Role::where('name', 'Create Bukti Pengeluaran')->first();
        $bukti_pengeluaran_detail  = Role::where('name', 'Details Bukti Pengeluaran')->first();
        $bukti_pengeluaran_edit  = Role::where('name', 'Edit Bukti Pengeluaran')->first();
        $bukti_pengeluaran_delete  = Role::where('name', 'Delete Bukti Pengeluaran')->first();
        $bukti_pengeluaran_search  = Role::where('name', 'Search Bukti Pengeluaran')->first();

        $penerimaan  = Role::where('name', 'Penerimaan')->first();
        $pengeluaran  = Role::where('name', 'Pengeluaran')->first();

        $tambah_semester  = Role::where('name', 'Tambah Semester')->first();

    	$group = new Group();
        $group->name = 'Admin';
        $group->save();

        $group->roles()->attach($role_list_group);
        $group->roles()->attach($role_create_group);
        $group->roles()->attach($role_details_group);
        $group->roles()->attach($role_edit_group);
        $group->roles()->attach($role_delete_group);
        $group->roles()->attach($role_search_group);

        $group->roles()->attach($role_list_role);
        $group->roles()->attach($role_create_role);
        $group->roles()->attach($role_details_role);
        $group->roles()->attach($role_edit_role);
        $group->roles()->attach($role_delete_role);
        $group->roles()->attach($role_search_role);

        $group->roles()->attach($role_list_user_group);
        $group->roles()->attach($role_create_user_group);
        $group->roles()->attach($role_details_user_group);
        $group->roles()->attach($role_edit_user_group);
        $group->roles()->attach($role_delete_user_group);
        $group->roles()->attach($role_search_user_group);
        
        $group->roles()->attach($role_list_group_role);
        $group->roles()->attach($role_create_group_role);
        $group->roles()->attach($role_details_group_role);
        $group->roles()->attach($role_edit_group_role);
        $group->roles()->attach($role_delete_group_role);
        $group->roles()->attach($role_search_group_role);

        $group->roles()->attach($sekolah_list);
        $group->roles()->attach($sekolah_create);
        $group->roles()->attach($sekolah_detail);
        $group->roles()->attach($sekolah_edit);
        $group->roles()->attach($sekolah_delete);
        $group->roles()->attach($sekolah_search);

        $group->roles()->attach($siswa_list);
        $group->roles()->attach($siswa_create);
        $group->roles()->attach($siswa_detail);
        $group->roles()->attach($siswa_edit);
        $group->roles()->attach($siswa_delete);
        $group->roles()->attach($siswa_search);

        $group->roles()->attach($penerimaan_list);
        $group->roles()->attach($penerimaan_create);
        $group->roles()->attach($penerimaan_detail);
        $group->roles()->attach($penerimaan_edit);
        $group->roles()->attach($penerimaan_delete);
        $group->roles()->attach($penerimaan_search);

        $group->roles()->attach($pengeluaran_list);
        $group->roles()->attach($pengeluaran_create);
        $group->roles()->attach($pengeluaran_detail);
        $group->roles()->attach($pengeluaran_edit);
        $group->roles()->attach($pengeluaran_delete);
        $group->roles()->attach($pengeluaran_search);

        $group->roles()->attach($bukti_tuntas_list);
        $group->roles()->attach($bukti_tuntas_create);
        $group->roles()->attach($bukti_tuntas_detail);
        $group->roles()->attach($bukti_tuntas_edit);
        $group->roles()->attach($bukti_tuntas_delete);
        $group->roles()->attach($bukti_tuntas_search);

        $group->roles()->attach($biaya_list);
        $group->roles()->attach($biaya_create);
        $group->roles()->attach($biaya_detail);
        $group->roles()->attach($biaya_edit);
        $group->roles()->attach($biaya_delete);
        $group->roles()->attach($biaya_search);

        $group->roles()->attach($calon_list);
        $group->roles()->attach($calon_create);
        $group->roles()->attach($calon_detail);
        $group->roles()->attach($calon_edit);
        $group->roles()->attach($calon_delete);
        $group->roles()->attach($calon_search);
        $group->roles()->attach($calon_aktif);
        $group->roles()->attach($calon_bayar);

        $group->roles()->attach($jenis_bayar_list);
        $group->roles()->attach($jenis_bayar_create);
        $group->roles()->attach($jenis_bayar_detail);
        $group->roles()->attach($jenis_bayar_edit);
        $group->roles()->attach($jenis_bayar_delete);
        $group->roles()->attach($jenis_bayar_search);

        $group->roles()->attach($bukti_pengeluaran_list);
        $group->roles()->attach($bukti_pengeluaran_create);
        $group->roles()->attach($bukti_pengeluaran_detail);
        $group->roles()->attach($bukti_pengeluaran_edit);
        $group->roles()->attach($bukti_pengeluaran_delete);
        $group->roles()->attach($bukti_pengeluaran_search);

        $group->roles()->attach($penerimaan);
        $group->roles()->attach($pengeluaran);

        $group->roles()->attach($tambah_semester);

        $group = new Group();
        $group->name = 'Bendahara';
        $group->save();

        $group->roles()->attach($siswa_list);
        $group->roles()->attach($siswa_detail);

        $group->roles()->attach($sekolah_list);
        $group->roles()->attach($sekolah_detail);

        $group->roles()->attach($penerimaan_list);
        $group->roles()->attach($penerimaan_detail);

        $group->roles()->attach($pengeluaran_list);
        $group->roles()->attach($pengeluaran_detail);

        $group->roles()->attach($bukti_tuntas_list);
        $group->roles()->attach($bukti_tuntas_detail);

        $group->roles()->attach($penerimaan);
        $group->roles()->attach($pengeluaran);

        $group = new Group();
        $group->name = 'Kasir';
        $group->save();

        $group->roles()->attach($sekolah_list);
        $group->roles()->attach($sekolah_create);
        $group->roles()->attach($sekolah_detail);
        $group->roles()->attach($sekolah_search);

        $group->roles()->attach($siswa_list);
        $group->roles()->attach($siswa_create);
        $group->roles()->attach($siswa_detail);
        $group->roles()->attach($siswa_search);

        $group->roles()->attach($calon_list);
        $group->roles()->attach($calon_create);
        $group->roles()->attach($calon_detail);
        $group->roles()->attach($calon_edit);
        $group->roles()->attach($calon_delete);
        $group->roles()->attach($calon_search);
        $group->roles()->attach($calon_aktif);
        $group->roles()->attach($calon_bayar);

        $group = new Group();
        $group->name = 'Kepala Keuangan';
        $group->save();

        $group->roles()->attach($siswa_list);
        $group->roles()->attach($siswa_detail);

        $group->roles()->attach($sekolah_list);
        $group->roles()->attach($sekolah_detail);

        $group->roles()->attach($penerimaan_list);
        $group->roles()->attach($penerimaan_detail);

        $group->roles()->attach($pengeluaran_list);
        $group->roles()->attach($pengeluaran_detail);

        $group->roles()->attach($bukti_tuntas_list);
        $group->roles()->attach($bukti_tuntas_detail);

        $group->roles()->attach($penerimaan);
        $group->roles()->attach($pengeluaran);

        $group = new Group();
        $group->name = 'Yayasan';
        $group->save();

        $group->roles()->attach($siswa_list);
        $group->roles()->attach($siswa_detail);

        $group->roles()->attach($sekolah_list);
        $group->roles()->attach($sekolah_detail);

        $group->roles()->attach($penerimaan_list);
        $group->roles()->attach($penerimaan_detail);

        $group->roles()->attach($pengeluaran_list);
        $group->roles()->attach($pengeluaran_detail);

        $group->roles()->attach($bukti_tuntas_list);
        $group->roles()->attach($bukti_tuntas_detail);

        $group->roles()->attach($penerimaan);
        $group->roles()->attach($pengeluaran);

    }
}
