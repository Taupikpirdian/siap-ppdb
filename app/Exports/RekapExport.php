<?php

namespace App\Exports;

use DB;
use Auth;
use View;
use App\Candidate;
use App\Group;
use App\UserSekolah;
use App\DetailPaymentCandidate;
use App\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class RekapExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents	
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
        // Kondisi ketika yang login admin dan bukan admin, parameter di ambil dari $login diatas
        if($login->group!='Admin')
        {
        return    $rekap = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('reports', 'reports.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.npm', '=', 'reports.npm')
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('SUM(form) as total_formulir'), 
                DB::raw('SUM(register) as total_register'), 
                DB::raw('SUM(has_payment_spp) as total_spp'), 
                DB::raw('SUM( (semester*spp)-has_payment_spp ) as tunggakan'),
                DB::raw('SUM( form+has_payment_spp+register ) as jumlah'))
            ->groupBy('nama_sekolah.nama_sekolah')
            ->where('user_id', '=', $id_user)
            ->get();

        }else{
            return    $rekap = DB::table('reports')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'reports.sekolah_id')
            ->leftjoin('siswas', 'siswas.npm', '=', 'reports.npm')
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('SUM(form) as total_formulir'), 
                DB::raw('SUM(register) as total_register'), 
                DB::raw('SUM(has_payment_spp) as total_spp'), 
                DB::raw('SUM( (semester*spp)-has_payment_spp ) as tunggakan'),
                DB::raw('SUM( form+has_payment_spp+register ) as jumlah'))
            ->groupBy('sekolahs.nama_sekolah')
                ->get();
                // ->paginate(25);
                // return view('admin.laporan.formulir',array('formulir'=>$formulir, 'user' => $user));
        }
    }
    public function headings(): array
    {
        return [
            'Tanggal Bayar',
            'Nama',
            'Sekolah',
            'Jenis Pembayaran',
            'Biaya'
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
