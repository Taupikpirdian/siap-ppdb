<?php

namespace App\Exports;

use DB;
use Auth;
use View;
use App\Candidate;
use App\Group;
use App\UserSekolah;
use App\DetailPaymentCandidate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\Input;

class FormulirHarianExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents	
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
        $to_date          = Input::get('to_date');
        $from_date        = Input::get('from_date');
        
        // Kondisi ketika yang login admin dan bukan admin, parameter di ambil dari $login diatas
        if($login->group!='Admin')
        {
        return    $formulir = DetailPaymentCandidate::orderBy('detail_payment_candidates.created_at', 'desc')
                ->leftJoin('candidates', 'candidates.id', '=', 'detail_payment_candidates.candidates_id')
                ->leftJoin('sekolahs', 'sekolahs.id', '=', 'detail_payment_candidates.sekolah_id')
                ->leftJoin('years', 'years.id', '=', 'detail_payment_candidates.thn_id')
                ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
                ->leftjoin('costs', 'costs.id', '=', 'detail_payment_candidates.cost_id')
                ->leftJoin('programs', 'programs.id', '=', 'detail_payment_candidates.program_id')
                ->where('detail_payment_candidates.payment_id', '=', 1)
                ->where('detail_payment_candidates.tgl_bayar', '=', $dataDate)
                ->select(
                        'detail_payment_candidates.tgl_bayar',
                        'candidates.nama',
                        'sekolahs.nama_sekolah',
                        // 'years.tahun',
                        // 'programs.nama as nm_prog',
                        'payment_types.name as nm_payment',
                        // 'costs.name as nm_cost',
                        'detail_payment_candidates.cost_id'
                        )
                ->get();
            // return view('admin.laporan.formulir',array('formulir'=>$formulir, 'user' => $user));
        }else{
            return    $formulir = DetailPaymentCandidate::orderBy('detail_payment_candidates.created_at', 'desc')
                ->leftJoin('candidates', 'candidates.id', '=', 'detail_payment_candidates.candidates_id')
                ->leftJoin('sekolahs', 'sekolahs.id', '=', 'detail_payment_candidates.sekolah_id')
                ->leftJoin('years', 'years.id', '=', 'detail_payment_candidates.thn_id')
                ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
                ->leftjoin('costs', 'costs.id', '=', 'detail_payment_candidates.cost_id')
                ->leftJoin('programs', 'programs.id', '=', 'detail_payment_candidates.program_id')
                ->where('detail_payment_candidates.payment_id', '=', 1)
                ->where('detail_payment_candidates.tgl_bayar', '=', $dataDate)
                ->select(
                        'detail_payment_candidates.tgl_bayar',
                        'candidates.nama',
                        'sekolahs.nama_sekolah',
                        // 'years.tahun',
                        // 'programs.nama as nm_prog',
                        'payment_types.name as nm_payment',
                        // 'costs.name as nm_cost',
                        'detail_payment_candidates.cost_id'
                        )
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
