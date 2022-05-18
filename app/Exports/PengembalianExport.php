<?php

namespace App\Exports;

use Auth;
use App\UangKeluar;
use App\Group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class PengembalianExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents	
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return UangKeluar::all();
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
        if($login->group=='Admin')
        {
            $user = Auth::user();
            return  $uang_keluar = UangKeluar::orderBy('uang_keluars.created_at', 'desc')
                ->leftjoin('siswas', 'siswas.id', '=', 'uang_keluars.siswa_id')
                ->leftjoin('sekolahs', 'sekolahs.id', '=', 'uang_keluars.sekolah_id')
                ->leftjoin('payment_types', 'payment_types.id', '=', 'uang_keluars.payment_id')
                ->select(
                    'siswas.nama_siswa',
                    'payment_types.name',
                    'sekolahs.nama_sekolah',
                    'uang_keluars.tgl_pengembalian',
                    'uang_keluars.jumlah',
                    'uang_keluars.denda'
            )
            ->get();
        }else{
            
        }
        // ->paginate(25);
        // return view('admin.pengembalian.list',array('uang_keluar'=>$uang_keluar, 'user' => $user));
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Jenis Pembayaran',
            'Sekolah',
            'Tanggal Pengembalian',
            'Jumlah',
            'Uang Kembali'
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
