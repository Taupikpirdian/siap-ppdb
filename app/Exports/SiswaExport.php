<?php

namespace App\Exports;

use DB;
use Auth;
use View;
use App\Group;
use App\Siswa;
use App\UserSekolah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class SiswaExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents   
{
    /**
    * @return \Illuminate\Support\Collection
    */

     public function headings(): array
    {
        return [
            'Sekolah', 'NPM', 'RFID', 'Nama Siswa', 'Program', 'Tahun Masuk', 'Tempat Lahir', 'Tanggal Lahir', 'Kelas', 'Sub Kelas', 'Semester', 'Nama Ayah', 'No Kontak Ayah', 'Nama Ibu', 'No Kontak Ibu', 'Nama Wali', 'No Kontak Wali', 'Alamat', 'Kecamatan', 'Kota/kabupaten', 'Status'
        ];
    }

    public function collection()
    {

    $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();
    if($login->group=='Admin')
    {
        $user = Auth::user();
        $id_user = Auth::user()->id;
        return $siswa = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'sekolahs.nama_sekolah',
                    'siswas.npm',
                    'siswas.rfid',
                    'siswas.nama_siswa',
                    'programs.nama',
                    'years.tahun',
                    'siswas.tempat_lahir',
                    'siswas.tgl_lahir',
                    'siswas.kelas',
                    'siswas.subkelas',
                    'siswas.semester',
                    'siswas.nama_ayah',
                    'siswas.hp_ayah',
                    'siswas.nama_ibu',
                    'siswas.hp_ibu',
                    'siswas.nama_wali',
                    'siswas.hp_wali',
                    'siswas.alamat',
                    'siswas.kecamatan',
                    'siswas.kota_kab',
                    'siswas.status'
                    )
            ->get();
    }else{
    $user = Auth::user();
    $id_user = Auth::user()->id;
     return   $siswa = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'sekolahs.nama_sekolah',
                    'siswas.npm',
                    'siswas.rfid',
                    'siswas.nama_siswa',
                    'programs.nama',
                    'years.tahun',
                    'siswas.tempat_lahir',
                    'siswas.tgl_lahir',
                    'siswas.kelas',
                    'siswas.subkelas',
                    'siswas.semester',
                    'siswas.nama_ayah',
                    'siswas.hp_ayah',
                    'siswas.nama_ibu',
                    'siswas.hp_ibu',
                    'siswas.nama_wali',
                    'siswas.hp_wali',
                    'siswas.alamat',
                    'siswas.kecamatan',
                    'siswas.kota_kab',
                    'siswas.status'
                    )
            ->where('user_id', '=', $id_user)
            ->get();
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
            },
        ];
    }

}
