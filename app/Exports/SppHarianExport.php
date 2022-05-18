<?php

namespace App\Exports;

use DB;
use Auth;
use View;
use App\Candidate;
use App\Group;
use App\UserSekolah;
use App\DetailPaymentCandidate;
use App\PembayaranSiswaAktif;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class SppHarianExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents	
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Candidate::all();

		$user = Auth::user();
        // Mengambil id login
        $id_user = Auth::user()->id;
        // Mengambil tanggal sekarang
        $dataDate = date('Y-m-d');
        // Mengambil nama group yang login
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();

        if($login->group!='Admin')
        {
        return    $spp = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->rightjoin('pembayaran_siswa_aktifs', 'pembayaran_siswa_aktifs.npm', '=', 'siswas.npm')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'pembayaran_siswa_aktifs.payment_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'pembayaran_siswa_aktifs.tgl_bayar',
            	    'siswas.nama_siswa',
                    'sekolahs.nama_sekolah',
                    'pembayaran_siswa_aktifs.semester',
                    'pembayaran_siswa_aktifs.amount',
                    'payment_types.name as nm_payment',
                    'pembayaran_siswa_aktifs.ket'
                    )
            ->where('user_id', '=', $id_user)
            ->where('pembayaran_siswa_aktifs.payment_id', '=', 3)
            ->where('pembayaran_siswa_aktifs.tgl_bayar', '=', $dataDate)
            ->paginate(25);
            return view('admin.laporan.spp', array('spp'=>$spp, 'user' => $user));
        }else{
        return    $spp = PembayaranSiswaAktif::leftjoin('sekolahs', 'sekolahs.id', '=', 'pembayaran_siswa_aktifs.sekolah_id')
            ->leftjoin('siswas', 'siswas.npm', '=', 'pembayaran_siswa_aktifs.npm')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'pembayaran_siswa_aktifs.payment_id')
            ->orderBy('pembayaran_siswa_aktifs.created_at', 'desc')
            ->select(
                    'pembayaran_siswa_aktifs.tgl_bayar',
            	    'siswas.nama_siswa',
                    'sekolahs.nama_sekolah',
                    'pembayaran_siswa_aktifs.semester',
                    'pembayaran_siswa_aktifs.amount',
                    'payment_types.name as nm_payment',
                    'pembayaran_siswa_aktifs.ket'
                    )
            ->where('pembayaran_siswa_aktifs.payment_id', '=', 3)
            ->where('pembayaran_siswa_aktifs.tgl_bayar', '=', $dataDate)
            ->paginate(25);
            return view('admin.laporan.spp', array('spp'=>$spp, 'user' => $user));
        }
    }
    public function headings(): array
    {
        return [
            'Tanggal Bayar',
            'Nama',
            'Sekolah',
            'Semester',
            'Jenis Pembayaran',
            'Biaya',
            'Keterangan'
        ];
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
