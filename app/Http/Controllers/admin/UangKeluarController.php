<?php

namespace App\Http\Controllers\admin;

use Auth;
use View;
use DB;
use Image;
use DateTime;
use App\UangKeluar;
use App\Siswa;
use App\PaymentType;
use App\PembayaranSiswaAktif;
use App\Exports\PengembalianExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class UangKeluarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $uang_keluar = UangKeluar::orderBy('uang_keluars.created_at', 'desc')
        ->leftjoin('siswas', 'siswas.id', '=', 'uang_keluars.siswa_id')
        ->leftjoin('sekolahs', 'sekolahs.id', '=', 'uang_keluars.sekolah_id')
        ->leftjoin('payment_types', 'payment_types.id', '=', 'uang_keluars.payment_id')
        ->select(
            'siswas.id as siswa_id',
            'siswas.nama_siswa',
            'siswas.sekolah_id',
            'sekolahs.nama_sekolah',
            'payment_types.name',
            'uang_keluars.tgl_pengembalian',
            'uang_keluars.jumlah',
            'uang_keluars.denda',
            'uang_keluars.id'
        )
        ->paginate(25);
        return view('admin.pengembalian.list',array('uang_keluar'=>$uang_keluar, 'user' => $user));
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $user = Auth::user();
        $siswa = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
        ->leftjoin('pembayaran_siswa_aktifs', 'pembayaran_siswa_aktifs.candidate_id', '=', 'siswas.id')
        ->orderBy('siswas.created_at', 'desc')
        ->where('siswas.id', $id)
        ->select(
            'siswas.id',
            'siswas.nama_siswa',
            'siswas.sekolah_id',
            'sekolahs.nama_sekolah',
            'pembayaran_siswa_aktifs.amount'
        )
        ->firstOrFail();

        $cost_count = DB::table('pembayaran_siswa_aktifs')
            ->where('pembayaran_siswa_aktifs.candidate_id', $id)
            ->where('pembayaran_siswa_aktifs.payment_id', 3)
            ->sum('amount');

        $payments=PaymentType::pluck('name', 'id');
        $payments->prepend('Pilih Jenis Pembayaran', '');
        
        return View::make('admin.pengembalian.create', compact('cost_count', 'siswa', 'payments'), array('user' => $user));
    }

    public function store(Request $request)
    {

        $uang_keluar= new UangKeluar;
        $uang_keluar->siswa_id          = Input::get('siswa_id');
        $uang_keluar->payment_id        = Input::get('payment_id');
        $uang_keluar->sekolah_id        = Input::get('sekolah_id');
        $uang_keluar->tgl_pengembalian  = Input::get('tgl_pengembalian');
        $uang_keluar->tgl_pengumuman    = Input::get('tgl_pengumuman');

        // mengambil tanggal, lalu menghitung rangenya
        $tgl_pengumuman                 = new DateTime($uang_keluar->tgl_pengumuman);
        $tgl_pengembalian               = new DateTime($uang_keluar->tgl_pengembalian);
        $interval                       = $tgl_pengumuman->diff($tgl_pengembalian);

        // kondisi untuk membuat nilai negatif
        if($interval->invert == 1){
            $days = $interval->days*-1;
        }else{
            $days = $interval->days*1;
        }

        $uang_keluar->jumlah            = Input::get('jumlah');

        if($days == 0 || $days == 1){
            $denda = $uang_keluar->jumlah * 0.75;
        }elseif ($days > 1) {
            $denda = $uang_keluar->jumlah * 0.50;
        }else{
            $denda = $uang_keluar->jumlah * 1;
        }

        $uang_keluar->denda             = $denda;
        $uang_keluar->save();

        $siswa = Siswa::where('id', $uang_keluar->siswa_id)->firstOrFail();
        $siswa->status = 'Tidak Aktif';
        $siswa->save();
        // redirect
        return Redirect::action('admin\UangKeluarController@index')->with('flash-store','Data berhasil ditambahkan.');
    }

    public function store_class(Request $request)
    {

        $uang_keluar= new UangKeluar;
        $uang_keluar->siswa_id          = Input::get('siswa_id');
        $uang_keluar->payment_id        = Input::get('payment_id');
        $uang_keluar->sekolah_id        = Input::get('sekolah_id');
        $uang_keluar->tgl_pengembalian  = Input::get('tgl_pengembalian');
        $uang_keluar->tgl_masuk         = Input::get('tgl_masuk');

        // mengambil tanggal, lalu menghitung rangenya
        $tgl_masuk                      = new DateTime($uang_keluar->tgl_masuk);
        $tgl_pengembalian               = new DateTime($uang_keluar->tgl_pengembalian);
        $interval                       = $tgl_masuk->diff($tgl_pengembalian);

        // kondisi untuk membuat nilai negatif
        if($interval->invert == 1){
            $days = $interval->days*-1;
        }else{
            $days = $interval->days*1;
        }

        $uang_keluar->jumlah            = Input::get('jumlah');

        if ($days < 31) {
            $denda = $uang_keluar->jumlah * 0.25;
        }else {
            $denda = $uang_keluar->jumlah * 0;
        }

        $uang_keluar->denda             = $denda;
        $uang_keluar->save();

        $siswa = Siswa::where('id', $uang_keluar->siswa_id)->firstOrFail();
        $siswa->status = 'Tidak Aktif';
        $siswa->save();
        // redirect
        return Redirect::action('admin\UangKeluarController@index')->with('flash-store','Data berhasil ditambahkan.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $uang_keluar = UangKeluar::where('id', $id)->firstOrFail();
        $uang_keluar->delete();
        return Redirect::action('admin\UangKeluarController@index')->with('flash-destroy','Data berhasil dihapus.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function search(Request $request){
        $user = Auth::user();
        $search = $request->get('search');
        $uang_keluar = UangKeluar::orderBy('uang_keluars.created_at', 'desc')
        ->leftjoin('siswas', 'siswas.id', '=', 'uang_keluars.siswa_id')
        ->leftjoin('sekolahs', 'sekolahs.id', '=', 'uang_keluars.sekolah_id')
        ->leftjoin('payment_types', 'payment_types.id', '=', 'uang_keluars.payment_id')
        ->select(
            'siswas.id',
            'siswas.nama_siswa',
            'siswas.sekolah_id',
            'sekolahs.nama_sekolah',
            'payment_types.name',
            'uang_keluars.tgl_pengembalian',
            'uang_keluars.jumlah',
            'uang_keluars.denda'
        )
        ->where('nama_siswa','LIKE','%'.$search.'%')
        ->orwhere('nama_sekolah','LIKE','%'.$search.'%')
        ->paginate(25);
        return view('admin.pengembalian.list', compact('uang_keluar'),array('user' => $user));
    }

    public function export_pengembalian()
    {
        return Excel::download(new PengembalianExport, 'Pengembalian.xlsx');
    }

    public function Pengembalian()
    {   
        $user = Auth::user();
        $uang_keluar = UangKeluar::orderBy('uang_keluars.created_at', 'desc')
        ->leftjoin('siswas', 'siswas.id', '=', 'uang_keluars.siswa_id')
        ->leftjoin('sekolahs', 'sekolahs.id', '=', 'uang_keluars.sekolah_id')
        ->leftjoin('payment_types', 'payment_types.id', '=', 'uang_keluars.payment_id')
        ->select(
            'siswas.id as siswa_id',
            'siswas.nama_siswa',
            'siswas.sekolah_id',
            'sekolahs.nama_sekolah',
            'payment_types.name',
            'uang_keluars.tgl_pengembalian',
            'uang_keluars.jumlah',
            'uang_keluars.denda',
            'uang_keluars.id'
        )
        ->paginate(25);
        return view('admin.pengembalian.invoice_pengembalian',array('uang_keluar'=>$uang_keluar, 'user' => $user));
    }
}
