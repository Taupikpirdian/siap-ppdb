<?php

namespace App\Exports;


use DB;
use Auth;
use View;
use App\Siswa;
use App\Group;
use App\UserSekolah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class CandidateExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents   
{
    /**
    * @return \Illuminate\Support\Collection
    */ public function headings(): array
    {
        return [
            'Sekolah', 'Program', 'Nama Siswa', 'Tanggal Lahir', 'Tahun Masuk', 'Jenis Pembayaran', 'Total Bayar' 
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
    return    $calon = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->leftjoin('detail_payment_candidates', 'detail_payment_candidates.candidates_id', '=', 'siswas.id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'sekolahs.nama_sekolah',
                    'programs.nama as nm_prog',
                    'siswas.nama_siswa',
                    'siswas.tgl_lahir',
                    'years.tahun',
                    'payment_types.name as nm_payment',
                    'detail_payment_candidates.cost_id'
                    )
            ->where('status', '=', "Calon")
            ->get();
    }else{
        $user = Auth::user();
        $id_user = Auth::user()->id;
    return    $calon = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->rightjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_masuk')
            ->leftjoin('detail_payment_candidates', 'detail_payment_candidates.candidates_id', '=', 'siswas.id')
            ->orderBy('candidates.created_at', 'desc')
            ->select(
                    'sekolahs.nama_sekolah',
                    'siswas.program',
                    'siswas.nama_siswa',
                    'siswas.tgl_lahir',
                    'years.tahun',
                    'detail_payment_candidates.payments_id',
                    'detail_payment_candidates.amount'
                )
            ->where('user_id', '=', $id_user)
            ->where('status', '=', "Calon")
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
