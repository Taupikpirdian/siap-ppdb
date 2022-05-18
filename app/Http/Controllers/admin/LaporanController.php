<?php

namespace App\Http\Controllers\admin;

use Auth;
use View;
use Image;
use DB;
use App\Group;
use App\Report;
use App\Sekolah;
use App\UserSekolah;
use App\Siswa;
use App\Year;
use App\Program;
use App\PaymentType;
use App\Cost;
use App\Candidate;
use App\DetailPaymentCandidate;
use App\PembayaranSiswaAktif;
use App\Tunggakan;
use App\Exports\FormulirHarianExport;
use App\Exports\DaftarExport;
use App\Exports\SppHarianExport;
use App\Exports\RekapExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class LaporanController extends Controller
{
    public function rekap()
    {
        $user = Auth::user();
        $id_user = Auth::user()->id;
        $dataDate = date('Y-m-d');
        $search = date('Y-m-d');
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();
        if($login->group!='Admin')
        {
            $rekap = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('reports', 'reports.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'sekolahs.id')
            ->where('reports.last_date_payment', '=', $dataDate)
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('SUM(form) as total_formulir'), 
                DB::raw('SUM(register) as total_register'), 
                DB::raw('SUM(has_payment_spp) as total_spp'), 
                DB::raw('COUNT(reports.sekolah_id) as jumlah_transaksi'),
                DB::raw('SUM(leftovers) as tunggakan'))
            ->groupBy('sekolahs.nama_sekolah')
            ->paginate(25);

            $tunggakan = DB::table('user_sekolahs')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('COUNT(tunggakans.sekolah_id) as jumlah_siswa'), 
                DB::raw('SUM(leftovers) as tunggakan'))
            ->groupBy('sekolahs.nama_sekolah')
            ->paginate(25);
        }else{
            $rekap = DB::table('reports')
            ->leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->rightjoin('tunggakans', 'tunggakans.candidates_id', '=', 'reports.candidate_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->where('reports.last_date_payment', '=', $dataDate)
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('SUM(form) as total_formulir'), 
                DB::raw('SUM(register) as total_register'), 
                DB::raw('SUM(has_payment_spp) as total_spp'), 
                DB::raw('COUNT(reports.sekolah_id) as jumlah_transaksi'), 
                DB::raw('COUNT(tunggakans.sekolah_id) as jumlah_siswa'), 
                DB::raw('SUM(leftovers) as tunggakan'))
            ->groupBy('sekolahs.nama_sekolah')
            ->paginate(25);

            $tunggakan = DB::table('tunggakans')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'tunggakans.sekolah_id')
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('COUNT(tunggakans.sekolah_id) as jumlah_siswa'), 
                DB::raw('SUM(leftovers) as tunggakan'))
            ->groupBy('sekolahs.nama_sekolah')
            ->paginate(25);
        }

        $jumlah = 0;
        $formulirs = 0;
        $pendaftarans = 0;
        $pendidikans = 0;
        $tunggkans = 0;
        $total_tunggakans = 0;
        $must_pay = 0;
        $jumlah_formulir = 0;
        $jumlah_register = 0;
        $jumlah_spp = 0;
        $total_seluruh = 0;
        $jumlah_tunggakan = 0;

        foreach ($rekap as $key => $formulir) {
            $jumlah_formulir += $formulir->total_formulir ;
            $formulirs = $jumlah_formulir;
        }

        foreach ($rekap as $key => $pendaftaran) {
            $jumlah_register += $pendaftaran->total_register ;
            $pendaftarans = $jumlah_register;
        }

        foreach ($rekap as $key => $pendidikan) {
            $jumlah_spp += $pendidikan->total_spp ;
            $pendidikans = $jumlah_spp;
        }

        foreach ($rekap as $key => $hutang) {
            $jumlah_tunggakan += $hutang->tunggakan ;
            $tunggkans = $jumlah_tunggakan;
        }

        $total_seluruh = $formulirs + $pendaftarans + $pendidikans; 
        return view('admin.laporan.rekap',compact('tunggkans', 'total_seluruh', 'rekap', 'tunggakan', 'search', 'formulirs', 'pendaftarans', 'pendidikans'), array('user' => $user));
    }   

    public function harian()
    {
        $user = Auth::user();    
        $id_user = Auth::user()->id;
        $dataDate = date('Y-m-d');
        $search = date('Y-m-d');
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();
        if($login->group=='Admin')
        {
            $all_siswa = Report::leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.candidates_id', '=', 'reports.candidate_id')
            ->orderBy('reports.last_date_payment', 'desc')
            ->where('reports.last_date_payment', '=', $dataDate)
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.kelas',
                    'siswas.semester',
                    'reports.form',
                    'reports.register',
                    'tunggakans.spp as spp_on_tunggakans',
                    'tunggakans.payment_amount as amount_on_tunggakans',
                    'reports.has_payment_spp',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
        }else{
            $all_siswa = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('reports', 'reports.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->leftjoin('tunggakans', 'tunggakans.candidates_id', '=', 'reports.candidate_id')
            ->where('reports.last_date_payment', '=', $dataDate)
            ->select(
                'siswas.nama_siswa',
                'siswas.npm',
                'siswas.kelas',
                'siswas.semester',
                'reports.form',
                'reports.register',
                'tunggakans.spp as spp_on_tunggakans',
                'tunggakans.payment_amount as amount_on_tunggakans',
                'reports.has_payment_spp',
                'sekolahs.nama_sekolah'
            )
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->paginate(25);
        }

        if($login->group=='Admin')
        {
        $has_payment_spp = DB::table('reports')
        ->leftjoin('siswas', 'siswas.npm', '=', 'reports.npm')
        // ini masih ambigu
        // ->where('reports.arrears_status', '=', 1)
        ->where('siswas.status', '=', "Aktif")
        ->sum('has_payment_spp');
        }else{
            $has_payment_spp = DB::table('user_sekolahs')
            // ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            // ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('reports', 'reports.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            // ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'users_sekolahs.sekolah_id')
            // ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'users_sekolahs.sekolah_id')
            // ini masih ambigu
            // ->where('tunggakans.arrears_status', '=', 2)
            // ->where('user_id', '=', $id_user)
            // ->where('siswas.status', '=', "Aktif")
            ->sum('reports.has_payment_spp');
        }

         // #hitung jumlah seluruh pembayaran spp yang sudah dibayar
        $jumlah = 0;
        // $jumlah_seluruh = 0;
        $a = 0;
        $spps = 0;
        $must_pay = 0;
        // $sp=0;

        foreach ($all_siswa as $key => $sub_tunggakan) {
            $a += $sub_tunggakan->spp * $sub_tunggakan->semester;
            $must_pay = $a;
        }

        foreach ($all_siswa as $key => $spp) {
            $jumlah += $spp->form + $spp->has_payment_spp + $spp->spp ;
            $spps = $jumlah;
        }

        $mod = $must_pay - $has_payment_spp;

        
        // foreach ($all_siswa as $key => $sp) {
        //     $sp = $jumlah;
        // }
        $formulirs = 0;
        $pendaftarans = 0;
        $pendidikans = 0;
        $tunggakans = 0;
        $jumlah_formulir = 0;
        $jumlah_register = 0;
        $jumlah_spp = 0;
        $jumlah_tunggakan = 0;

        foreach ($all_siswa as $key => $formulir) {
            $jumlah_formulir += $formulir->form ;
            $formulirs = $jumlah_formulir;
        }

        foreach ($all_siswa as $key => $pendaftaran) {
            $jumlah_register += $pendaftaran->register ;
            $pendaftarans = $jumlah_register;
        }

        foreach ($all_siswa as $key => $pendidikan) {
            $jumlah_spp += $pendidikan->has_payment_spp ;
            $pendidikans = $jumlah_spp;
        }

        foreach ($all_siswa as $key => $hutang) {
            $jumlah_tunggakan += ($hutang->spp_on_tunggakans * $hutang->semester) - $hutang->amount_on_tunggakans ;
            $tunggakans = $jumlah_tunggakan;
        }

        $total_seluruh = $formulirs + $pendaftarans + $pendidikans;

        // echo "<pre>";
        // print_r($all_siswa);
        // echo "</pre>";
        // exit();

        return view('admin.laporan.harian',compact('formulirs', 'pendaftarans', 'pendidikans', 'tunggakans', 'total_seluruh', 'all_siswa', 'has_payment_spp', 'spps', 'mod', 'search'), array('user' => $user));
    }   
    // mulai disini
    public function tunggakan()
    {
        $user = Auth::user();
        $id_user = Auth::user()->id;
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();

        if($login->group!='Admin')
        {
            $tunggakan = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->orderBy('tunggakans.created_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
        }else{
            $tunggakan = Tunggakan::leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'tunggakans.sekolah_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->orderBy('tunggakans.updated_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
        }

        if($login->group=='Admin')
        {
        $has_payment_spp = DB::table('tunggakans')
        ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
        ->where('tunggakans.arrears_status', '=', 2)
        ->where('siswas.status', '=', "Aktif")
        ->sum('tunggakans.payment_amount');
        }else{
            $has_payment_spp = DB::table('user_sekolahs')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->sum('tunggakans.payment_amount');
        }
        // hitung jumlah seluruh pembayaran spp yang sudah dibayar
        $jumlah_spp = 0;
        $jm_tunggakan = 0;
        $a = 0;
        $must_pay = 0;
        $spps=0;

        foreach ($tunggakan as $key => $spp) {
            $jumlah_spp += $spp->spp * $spp->semester ;
            $spps = $jumlah_spp;
        }

        foreach ($tunggakan as $key => $sub_tunggakan) {
            $a += $sub_tunggakan->spp * $sub_tunggakan->semester;
            $must_pay = $a;
        }

        $mod = $must_pay - $has_payment_spp;

        // Filter
        $scholls = Sekolah::orderBy('created_at', 'desc')
        ->get();
        $class = Siswa::distinct()->whereNotNull('kelas')->get(['kelas']);
        $classes = "";
        $school = "";
        return view('admin.laporan.tunggakan',compact('class', 'tunggakan', 'has_payment_spp', 'spps', 'mod', 'scholls', 'school', 'classes'), array('user' => $user));
    }   

    public function formulir()
    {
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
            $formulir = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('detail_payment_candidates', 'detail_payment_candidates.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'detail_payment_candidates.program_id')
            ->leftjoin('years', 'years.id', '=', 'detail_payment_candidates.thn_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'detail_payment_candidates.candidates_id')
            ->orderBy('detail_payment_candidates.tgl_bayar', 'desc')
            ->select(
                'detail_payment_candidates.*',
                'siswas.*',
                'sekolahs.nama_sekolah',
                'years.tahun',
                'programs.nama as nm_prog',
                'payment_types.name as nm_payment'
                )
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('detail_payment_candidates.payment_id', '=', 1)
            ->where('detail_payment_candidates.tgl_bayar', '=', $dataDate)
            ->paginate(25);
            return view('admin.laporan.formulir',array('formulir'=>$formulir, 'user' => $user));
        }else{
                $formulir = DetailPaymentCandidate::orderBy('detail_payment_candidates.created_at', 'desc')
                ->leftJoin('siswas', 'siswas.id', '=', 'detail_payment_candidates.candidates_id')
                ->leftJoin('sekolahs', 'sekolahs.id', '=', 'detail_payment_candidates.sekolah_id')
                ->leftJoin('years', 'years.id', '=', 'detail_payment_candidates.thn_id')
                ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
                ->leftjoin('costs', 'costs.id', '=', 'detail_payment_candidates.cost_id')
                ->leftJoin('programs', 'programs.id', '=', 'detail_payment_candidates.program_id')
                ->where('detail_payment_candidates.payment_id', '=', 1)
                ->where('detail_payment_candidates.tgl_bayar', '=', $dataDate)
                ->select(
                        'detail_payment_candidates.*',
                        'siswas.*',
                        'sekolahs.nama_sekolah',
                        'years.tahun',
                        'programs.nama as nm_prog',
                        'payment_types.name as nm_payment',
                        'costs.name as nm_cost'
                        )
                ->paginate(25);
                return view('admin.laporan.formulir',array('formulir'=>$formulir, 'user' => $user));
        }
    }
    
    public function daftar()
    {
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
        $daftar = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
        ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
        ->leftjoin('detail_payment_candidates', 'detail_payment_candidates.sekolah_id', '=', 'user_sekolahs.sekolah_id')
        ->leftjoin('programs', 'programs.id', '=', 'detail_payment_candidates.program_id')
        ->leftjoin('years', 'years.id', '=', 'detail_payment_candidates.thn_id')
        ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
        ->leftjoin('siswas', 'siswas.id', '=', 'detail_payment_candidates.candidates_id')
        ->orderBy('detail_payment_candidates.tgl_bayar', 'desc')
        ->select(
            'detail_payment_candidates.*',
            'siswas.*',
            'sekolahs.nama_sekolah',
            'years.tahun',
            'programs.nama as nm_prog',
            'payment_types.name as nm_payment'
            )
        ->where('user_sekolahs.user_id', '=', $id_user)
        ->where('detail_payment_candidates.payment_id', '=', 2)
        ->where('detail_payment_candidates.tgl_bayar', '=', $dataDate)
        ->paginate(25);
        return view('admin.laporan.daftar',array('daftar'=>$daftar, 'user' => $user));
        }else{
            $daftar = DetailPaymentCandidate::orderBy('detail_payment_candidates.created_at', 'desc')
            ->leftJoin('siswas', 'siswas.id', '=', 'detail_payment_candidates.candidates_id')
            ->leftJoin('sekolahs', 'sekolahs.id', '=', 'detail_payment_candidates.sekolah_id')
            ->leftJoin('years', 'years.id', '=', 'detail_payment_candidates.thn_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->leftjoin('costs', 'costs.id', '=', 'detail_payment_candidates.cost_id')
            ->leftJoin('programs', 'programs.id', '=', 'detail_payment_candidates.program_id')
            ->where('detail_payment_candidates.payment_id', '=', 2)
            ->where('detail_payment_candidates.tgl_bayar', '=', $dataDate)
            ->select(
                    'detail_payment_candidates.*',
                    'siswas.*',
                    'sekolahs.nama_sekolah',
                    'years.tahun',
                    'programs.nama as nm_prog',
                    'payment_types.name as nm_payment',
                    'costs.name as nm_cost'
                    )
            ->paginate(25);
            return view('admin.laporan.daftar',array('daftar'=>$daftar, 'user' => $user));
        }
    }

    public function spp()
    {
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
            $spp = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->rightjoin('pembayaran_siswa_aktifs', 'pembayaran_siswa_aktifs.npm', '=', 'siswas.npm')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'pembayaran_siswa_aktifs.payment_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'siswas.id as id_siswa',
                    'siswas.npm',
                    'siswas.nama_siswa',
                    'sekolahs.nama_sekolah',
                    'pembayaran_siswa_aktifs.tgl_bayar',
                    'pembayaran_siswa_aktifs.amount',
                    'pembayaran_siswa_aktifs.ket',
                    'pembayaran_siswa_aktifs.payment_id',
                    'pembayaran_siswa_aktifs.semester',
                    'payment_types.name as nm_payment'
                    )
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('pembayaran_siswa_aktifs.payment_id', '=', 3)
            ->where('pembayaran_siswa_aktifs.tgl_bayar', '=', $dataDate)
            ->paginate(25);
            return view('admin.laporan.spp', array('spp'=>$spp, 'user' => $user));
        }else{
            $spp = PembayaranSiswaAktif::leftjoin('sekolahs', 'sekolahs.id', '=', 'pembayaran_siswa_aktifs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'pembayaran_siswa_aktifs.candidate_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'pembayaran_siswa_aktifs.payment_id')
            ->orderBy('pembayaran_siswa_aktifs.created_at', 'desc')
            ->select(
                    'siswas.id as id_siswa',
                    'siswas.npm',
                    'siswas.nama_siswa',
                    'sekolahs.nama_sekolah',
                    'pembayaran_siswa_aktifs.tgl_bayar',
                    'pembayaran_siswa_aktifs.amount',
                    'pembayaran_siswa_aktifs.ket',
                    'pembayaran_siswa_aktifs.payment_id',
                    'pembayaran_siswa_aktifs.semester',
                    'payment_types.name as nm_payment'
                    )
            ->where('pembayaran_siswa_aktifs.payment_id', '=', 3)
            ->where('pembayaran_siswa_aktifs.tgl_bayar', '=', $dataDate)
            ->paginate(25);
            return view('admin.laporan.spp', array('spp'=>$spp, 'user' => $user));
        }
    }   

    public function export()
    {
        return Excel::download(new FormulirHarianExport, 'Formulir_Harian.xlsx');
    }

    public function export_daftar()
    {
        return Excel::download(new DaftarExport, 'Daftar_Ulang.xlsx');
    }

    public function export_spp()
    {
        return Excel::download(new SppHarianExport, 'Spp_Harian.xlsx');
    }

    public function export_rekap()
    {
        return Excel::download(new RekapExport, 'Rekap.xlsx');
    }

    public function invoice()
    {   
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
            $formulir = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('detail_payment_candidates', 'detail_payment_candidates.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'detail_payment_candidates.program_id')
            ->leftjoin('years', 'years.id', '=', 'detail_payment_candidates.thn_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'detail_payment_candidates.candidates_id')
            ->orderBy('detail_payment_candidates.tgl_bayar', 'desc')
            ->select(
                'detail_payment_candidates.*',
                'siswas.*',
                'sekolahs.nama_sekolah',
                'years.tahun',
                'programs.nama as nm_prog',
                'payment_types.name as nm_payment'
                )
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('detail_payment_candidates.payment_id', '=', 1)
            ->where('detail_payment_candidates.tgl_bayar', '=', $dataDate)
            ->paginate(25);
            return view('admin.laporan.invoice_laporan',array('formulir'=>$formulir, 'user' => $user));
        }else{
            $formulir = DetailPaymentCandidate::orderBy('detail_payment_candidates.created_at', 'desc')
            ->leftJoin('siswas', 'siswas.id', '=', 'detail_payment_candidates.candidates_id')
            ->leftJoin('sekolahs', 'sekolahs.id', '=', 'detail_payment_candidates.sekolah_id')
            ->leftJoin('years', 'years.id', '=', 'detail_payment_candidates.thn_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->leftjoin('costs', 'costs.id', '=', 'detail_payment_candidates.cost_id')
            ->leftJoin('programs', 'programs.id', '=', 'detail_payment_candidates.program_id')
            ->where('detail_payment_candidates.payment_id', '=', 1)
            ->where('detail_payment_candidates.tgl_bayar', '=', $dataDate)
            ->select(
                    'detail_payment_candidates.*',
                    'siswas.*',
                    'sekolahs.nama_sekolah',
                    'years.tahun',
                    'programs.nama as nm_prog',
                    'payment_types.name as nm_payment',
                    'costs.name as nm_cost'
                    )
            ->paginate(25);
            return view('admin.laporan.invoice_laporan',array('formulir'=>$formulir, 'user' => $user));
        }
    }

     public function invoice_daftar()
    {   
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
            $formulir = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('detail_payment_candidates', 'detail_payment_candidates.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'detail_payment_candidates.program_id')
            ->leftjoin('years', 'years.id', '=', 'detail_payment_candidates.thn_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'detail_payment_candidates.candidates_id')
            ->orderBy('detail_payment_candidates.tgl_bayar', 'desc')
            ->select(
                'detail_payment_candidates.*',
                'siswas.*',
                'sekolahs.nama_sekolah',
                'years.tahun',
                'programs.nama as nm_prog',
                'payment_types.name as nm_payment'
                )
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('detail_payment_candidates.payment_id', '=', 2)
            ->where('detail_payment_candidates.tgl_bayar', '=', $dataDate)
            ->paginate(25);
            return view('admin.laporan.invoice_daftar_ulang',array('formulir'=>$formulir, 'user' => $user));
        }else{
            $formulir = DetailPaymentCandidate::orderBy('detail_payment_candidates.created_at', 'desc')
            ->leftJoin('siswas', 'siswas.id', '=', 'detail_payment_candidates.candidates_id')
            ->leftJoin('sekolahs', 'sekolahs.id', '=', 'detail_payment_candidates.sekolah_id')
            ->leftJoin('years', 'years.id', '=', 'detail_payment_candidates.thn_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->leftjoin('costs', 'costs.id', '=', 'detail_payment_candidates.cost_id')
            ->leftJoin('programs', 'programs.id', '=', 'detail_payment_candidates.program_id')
            ->where('detail_payment_candidates.payment_id', '=', 2)
            ->where('detail_payment_candidates.tgl_bayar', '=', $dataDate)
            ->select(
                    'detail_payment_candidates.*',
                    'siswas.*',
                    'sekolahs.nama_sekolah',
                    'years.tahun',
                    'programs.nama as nm_prog',
                    'payment_types.name as nm_payment',
                    'costs.name as nm_cost'
                    )
            ->paginate(25);
            return view('admin.laporan.invoice_daftar_ulang',array('formulir'=>$formulir, 'user' => $user));
        }
    }

    public function invoice_spp()
    {   
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
            $spp = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->rightjoin('pembayaran_siswa_aktifs', 'pembayaran_siswa_aktifs.candidate_id', '=', 'siswas.id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'pembayaran_siswa_aktifs.payment_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'siswas.id as id_siswa',
                    'siswas.npm',
                    'siswas.nama_siswa',
                    'sekolahs.nama_sekolah',
                    'pembayaran_siswa_aktifs.tgl_bayar',
                    'pembayaran_siswa_aktifs.amount',
                    'pembayaran_siswa_aktifs.ket',
                    'pembayaran_siswa_aktifs.payment_id',
                    'pembayaran_siswa_aktifs.semester',
                    'payment_types.name as nm_payment'
                    )
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('pembayaran_siswa_aktifs.payment_id', '=', 3)
            ->where('pembayaran_siswa_aktifs.tgl_bayar', '=', $dataDate)
            ->paginate(25);
            return view('admin.laporan.invoice_spp',array('spp'=>$spp, 'user' => $user));
        }else{
           $spp = PembayaranSiswaAktif::leftjoin('sekolahs', 'sekolahs.id', '=', 'pembayaran_siswa_aktifs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'pembayaran_siswa_aktifs.candidate_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'pembayaran_siswa_aktifs.payment_id')
            ->orderBy('pembayaran_siswa_aktifs.created_at', 'desc')
            ->select(
                    'siswas.id as id_siswa',
                    'siswas.npm',
                    'siswas.nama_siswa',
                    'sekolahs.nama_sekolah',
                    'pembayaran_siswa_aktifs.tgl_bayar',
                    'pembayaran_siswa_aktifs.amount',
                    'pembayaran_siswa_aktifs.ket',
                    'pembayaran_siswa_aktifs.payment_id',
                    'pembayaran_siswa_aktifs.semester',
                    'payment_types.name as nm_payment'
                    )
            ->where('pembayaran_siswa_aktifs.payment_id', '=', 3)
            ->where('pembayaran_siswa_aktifs.tgl_bayar', '=', $dataDate)
            ->paginate(25);
            return view('admin.laporan.invoice_spp',array('spp'=>$spp, 'user' => $user));
        }
    }

    public function invoice_rekap(Request $request)
    {   
        $user = Auth::user();
        $id_user = Auth::user()->id;
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();
        $dataDate = date('Y-m-d');

        $search = $request->get('search');
        // echo "<pre>";
        // print_r($search);
        // echo "</pre>";
        // exit();

        if($login->group!='Admin')
        {
            // $rekap = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            // ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            // ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            // ->leftjoin('reports', 'reports.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            // ->leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            // ->select(
            //     'sekolahs.nama_sekolah', 
            //     DB::raw('SUM(form) as total_formulir'), 
            //     DB::raw('SUM(register) as total_register'), 
            //     DB::raw('SUM(has_payment_spp) as total_spp'), 
            //     DB::raw('COUNT(reports.sekolah_id) as jumlah_transaksi'), 
            //     DB::raw('SUM( (semester*tunggakans.spp)-tunggakans.payment_amount ) as tunggakan'))
            // ->groupBy('sekolahs.nama_sekolah')
            // ->where('user_sekolahs.user_id', '=', $id_user)
            // ->get();

            // $tunggakan = DB::table('user_sekolahs')
            // ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            // ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            // ->select(
            //     'sekolahs.nama_sekolah', 
            //     DB::raw('COUNT(tunggakans.sekolah_id) as jumlah_siswa'), 
            //     DB::raw('SUM(leftovers) as tunggakan'))
            // ->groupBy('sekolahs.nama_sekolah')
            // ->get();

            $rekap = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('reports', 'reports.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'sekolahs.id')
            ->where('reports.last_date_payment', '=', $dataDate)
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('SUM(form) as total_formulir'), 
                DB::raw('SUM(register) as total_register'), 
                DB::raw('SUM(has_payment_spp) as total_spp'), 
                DB::raw('COUNT(reports.sekolah_id) as jumlah_transaksi'),
                DB::raw('SUM(leftovers) as tunggakan'))
            ->groupBy('sekolahs.nama_sekolah')
            ->where('reports.last_date_payment','=',$search)
            ->get();

            $tunggakan = DB::table('user_sekolahs')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('COUNT(tunggakans.sekolah_id) as jumlah_siswa'), 
                DB::raw('SUM(leftovers) as tunggakan'))
            ->groupBy('sekolahs.nama_sekolah')
            ->get();
        }else{
            $rekap = DB::table('reports')
            ->leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->rightjoin('tunggakans', 'tunggakans.candidates_id', '=', 'reports.candidate_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('SUM(form) as total_formulir'), 
                DB::raw('SUM(register) as total_register'), 
                DB::raw('SUM(has_payment_spp) as total_spp'), 
                DB::raw('COUNT(reports.sekolah_id) as jumlah_transaksi'), 
                DB::raw('COUNT(tunggakans.sekolah_id) as jumlah_siswa'), 
                DB::raw('SUM(leftovers) as tunggakan'))
            ->groupBy('sekolahs.nama_sekolah')
            ->where('reports.last_date_payment','=',$search)
            ->get();

            $tunggakan = DB::table('tunggakans')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'tunggakans.sekolah_id')
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('COUNT(tunggakans.sekolah_id) as jumlah_siswa'), 
                DB::raw('SUM(leftovers) as tunggakan'))
            ->groupBy('sekolahs.nama_sekolah')
            // ->where('reports.last_date_payment','=',$search)
            ->get();
        }
        $jumlah = 0;
        $formulirs = 0;
        $pendaftarans = 0;
        $pendidikans = 0;
        $tunggkans = 0;
        $total_tunggakans = 0;
        $must_pay = 0;
        $jumlah_formulir = 0;
        $jumlah_register = 0;
        $jumlah_spp = 0;
        $total_seluruh = 0;
        $jumlah_tunggakan = 0;

        foreach ($rekap as $key => $formulir) {
            $jumlah_formulir += $formulir->total_formulir ;
            $formulirs = $jumlah_formulir;
        }

        foreach ($rekap as $key => $pendaftaran) {
            $jumlah_register += $pendaftaran->total_register ;
            $pendaftarans = $jumlah_register;
        }

        foreach ($rekap as $key => $pendidikan) {
            $jumlah_spp += $pendidikan->total_spp ;
            $pendidikans = $jumlah_spp;
        }

        foreach ($rekap as $key => $hutang) {
            $jumlah_tunggakan += $hutang->tunggakan ;
            $tunggkans = $jumlah_tunggakan;
        }

        $total_seluruh = $formulirs + $pendaftarans + $pendidikans;
        return view('admin.laporan.invoice_rekap',compact('rekap', 'tunggakan', 'formulirs', 'pendaftarans', 'pendidikans', 'tunggkans', 'total_seluruh'), array('user' => $user));
    }


    public function invoice_all_siswa(Request $request)
    {   
        $user = Auth::user();
        $id_user = Auth::user()->id;
        $dataDate = date('Y-m-d');
        $search = $request->get('search');
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();
        if($login->group=='Admin')
        {
            $all_siswa = Report::leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.candidates_id', '=', 'reports.candidate_id')
            ->orderBy('reports.last_date_payment', 'desc')
            ->where('reports.last_date_payment','=',$search)
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.kelas',
                    'siswas.semester',
                    'reports.form',
                    'reports.register',
                    'tunggakans.spp as spp_on_tunggakans',
                    'tunggakans.payment_amount as amount_on_tunggakans',
                    'reports.has_payment_spp',
                    'sekolahs.nama_sekolah'
            )
            ->get();
        }else{
            $all_siswa = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('reports', 'reports.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->leftjoin('tunggakans', 'tunggakans.candidates_id', '=', 'reports.candidate_id')
            ->orderBy('reports.last_date_payment', 'desc')
            ->where('reports.last_date_payment', '=', $search)
            ->select(
                'siswas.nama_siswa',
                'siswas.npm',
                'siswas.kelas',
                'siswas.semester',
                'reports.form',
                'reports.register',
                'reports.has_payment_spp',
                'tunggakans.spp',
                'tunggakans.payment_amount',
                'sekolahs.nama_sekolah'
            )
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->get();
        }

        $formulirs = 0;
        $pendaftarans = 0;
        $pendidikans = 0;
        $tunggakans = 0;
        $total_seluruh = 0;
        $jumlah_formulir = 0;
        $jumlah_register = 0;
        $jumlah_spp = 0;
        $jumlah_tunggakan = 0;

        foreach ($all_siswa as $key => $formulir) {
            $jumlah_formulir += $formulir->form ;
            $formulirs = $jumlah_formulir;
        }

        foreach ($all_siswa as $key => $pendaftaran) {
            $jumlah_register += $pendaftaran->register ;
            $pendaftarans = $jumlah_register;
        }

        foreach ($all_siswa as $key => $pendidikan) {
            $jumlah_spp += $pendidikan->has_payment_spp ;
            $pendidikans = $jumlah_spp;
        }

        foreach ($all_siswa as $key => $hutang) {
            $jumlah_tunggakan += ($hutang->spp_on_tunggakans * $hutang->semester) - $hutang->amount_on_tunggakans ;
            $tunggakans = $jumlah_tunggakan;
        }

        $total_seluruh = $formulirs + $pendaftarans + $pendidikans;
        return view('admin.laporan.invoice_siswa',compact('total_seluruh', 'all_siswa', 'formulirs', 'pendaftarans', 'pendidikans', 'tunggakans'), array('user' => $user));
    }

     public function invoice_tunggakan(Request $request)
    {   
        $user = Auth::user();
        $id_user = Auth::user()->id;
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();

        $school = Input::get('school');
        $class 	= Input::get('class');

        if($login->group=='Admin')
        {
		if($school && $class){
            $tunggakan = Tunggakan::leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'tunggakans.sekolah_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('siswas.sekolah_id', $school)
            ->where('siswas.kelas', $class)
            ->orderBy('tunggakans.updated_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
		}else if($school){
            $tunggakan = Tunggakan::leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'tunggakans.sekolah_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('siswas.sekolah_id', $school)
            ->orderBy('tunggakans.updated_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
		}else if($class){
            $tunggakan = Tunggakan::leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'tunggakans.sekolah_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('siswas.kelas', $class)
            ->orderBy('tunggakans.updated_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
		}else{
            $tunggakan = Tunggakan::leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'tunggakans.sekolah_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->orderBy('tunggakans.updated_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
        }
    }else{
        if($school && $class){
            $tunggakan = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('siswas.sekolah_id', $school)
            ->where('siswas.kelas', $class)
            ->orderBy('tunggakans.created_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
		}else if($school){
            $tunggakan = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('siswas.sekolah_id', $school)
            ->orderBy('tunggakans.created_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
		}else if($class){
            $tunggakan = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('siswas.kelas', $class)
            ->orderBy('tunggakans.created_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
		}else{
            $tunggakan = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->orderBy('tunggakans.created_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
        }
    }

    if($login->group=='Admin')
        {
            if($school && $class){
                $has_payment_spp = DB::table('tunggakans')
                ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('siswas.status', '=', "Aktif")
                ->where('siswas.sekolah_id', $school)
                ->where('siswas.kelas', $class)
                ->sum('tunggakans.payment_amount');
            }else if($school){
                $has_payment_spp = DB::table('tunggakans')
                ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('siswas.status', '=', "Aktif")
                ->where('siswas.sekolah_id', $school)
                ->sum('tunggakans.payment_amount');
            }else if($class){
                $has_payment_spp = DB::table('tunggakans')
                ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('siswas.status', '=', "Aktif")
                ->where('siswas.kelas', $class)
                ->sum('tunggakans.payment_amount');
            }else{
                $has_payment_spp = DB::table('tunggakans')
                ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('siswas.status', '=', "Aktif")
                ->sum('tunggakans.payment_amount');
            }
        }else{
            if($school && $class){
                $has_payment_spp = DB::table('user_sekolahs')
                ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
                ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('user_sekolahs.user_id', '=', $id_user)
                ->where('siswas.status', '=', "Aktif")
                ->where('siswas.sekolah_id', $school)
                ->where('siswas.kelas', $class)
                ->sum('tunggakans.payment_amount');
            }else if($school){
                $has_payment_spp = DB::table('user_sekolahs')
                ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
                ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('user_sekolahs.user_id', '=', $id_user)
                ->where('siswas.status', '=', "Aktif")
                ->where('siswas.sekolah_id', $school)
                ->sum('tunggakans.payment_amount');
            }else if($class){
                $has_payment_spp = DB::table('user_sekolahs')
                ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
                ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('user_sekolahs.user_id', '=', $id_user)
                ->where('siswas.status', '=', "Aktif")
                ->where('siswas.kelas', $class)
                ->sum('tunggakans.payment_amount');
            }else{
                $has_payment_spp = DB::table('user_sekolahs')
                ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
                ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('user_sekolahs.user_id', '=', $id_user)
                ->where('siswas.status', '=', "Aktif")
                ->sum('tunggakans.payment_amount');
            }
        }

        // hitung jumlah seluruh pembayaran spp yang sudah dibayar
        $jumlah_spp = 0;
        $jm_tunggakan = 0;
        $a = 0;
        $must_pay = 0;
        $spps=0;
        foreach ($tunggakan as $key => $spp) {
            $jumlah_spp += $spp->spp * $spp->semester ;
            $spps = $jumlah_spp;
        }
        foreach ($tunggakan as $key => $sub_tunggakan) {
            $a += $sub_tunggakan->spp * $sub_tunggakan->semester;
            $must_pay = $a;
        }
        $mod = $must_pay - $has_payment_spp;
        
        return view('admin.laporan.invoice_tunggakan',compact('tunggakan', 'has_payment_spp', 'spps', 'mod'), array('user' => $user));
    }
    
    // public function backup_invoice_tunggakan()
    // {   
    //     $user = Auth::user();
    //     $id_user = Auth::user()->id;
    //     $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
    //                 ->where('user_groups.user_id', Auth::id())
    //                 ->select('groups.name AS group')
    //                 ->first();
    //     $school = Input::get('school');
    //     $class 	= Input::get('class');

    //     if($login->group!='Admin')
    //     {
    //         $tunggakan = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
    //         ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
    //         ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
    //         ->where('tunggakans.arrears_status', '=', 2)
    //         ->where('siswas.status', '=', "Aktif")
    //         ->orderBy('tunggakans.created_at', 'desc')
    //         ->select(
    //                 'siswas.nama_siswa',
    //                 'siswas.npm',
    //                 'siswas.semester',
    //                 'tunggakans.spp',
    //                 'tunggakans.payment_amount',
    //                 'sekolahs.nama_sekolah'
    //         )
    //         ->paginate(25);
    //     }else{
    //         $tunggakan = Tunggakan::leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
    //         ->leftjoin('sekolahs', 'sekolahs.id', '=', 'tunggakans.sekolah_id')
    //         ->where('tunggakans.arrears_status', '=', 2)
    //         ->where('siswas.status', '=', "Aktif")
    //         ->orderBy('tunggakans.updated_at', 'desc')
    //         ->select(
    //                 'siswas.nama_siswa',
    //                 'siswas.npm',
    //                 'siswas.semester',
    //                 'tunggakans.spp',
    //                 'tunggakans.payment_amount',
    //                 'sekolahs.nama_sekolah'
    //         )
    //         ->get();
    //     }

    //     if($login->group=='Admin')
    //     {
    //     $has_payment_spp = DB::table('tunggakans')
    //     ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
    //     ->where('tunggakans.arrears_status', '=', 2)
    //     ->where('siswas.status', '=', "Aktif")
    //     ->sum('tunggakans.payment_amount');
    //     }else{
    //         $has_payment_spp = DB::table('user_sekolahs')
    //         ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
    //         ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
    //         ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
    //         ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
    //         ->where('tunggakans.arrears_status', '=', 2)
    //         ->where('user_sekolahs.user_id', '=', $id_user)
    //         ->where('siswas.status', '=', "Aktif")
    //         ->sum('tunggakans.payment_amount');
    //     }
    //     // hitung jumlah seluruh pembayaran spp yang sudah dibayar
    //     $jumlah_spp = 0;
    //     $jm_tunggakan = 0;
    //     $a = 0;
    //     $must_pay = 0;
    //     $spps=0;

    //     foreach ($tunggakan as $key => $spp) {
    //         $jumlah_spp += $spp->spp * $spp->semester ;
    //         $spps = $jumlah_spp;
    //     }

    //     foreach ($tunggakan as $key => $sub_tunggakan) {
    //         $a += $sub_tunggakan->spp * $sub_tunggakan->semester;
    //         $must_pay = $a;
    //     }

    //     $mod = $must_pay - $has_payment_spp;
    //     return view('admin.laporan.invoice_tunggakan',compact('tunggakan', 'has_payment_spp', 'spps', 'mod'), array('user' => $user));
    // }

    public function search_tunggakan(Request $request){
        $user = Auth::user();
        $id_user = Auth::user()->id;
        $search = $request->get('search');
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();

        if($login->group!='Admin')
        {
            $tunggakan = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('reports', 'reports.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->where('siswas.nama_siswa','LIKE','%'.$search.'%')
            ->orwhere('siswas.npm','LIKE','%'.$search.'%')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'reports.spp',
                    'reports.has_payment_spp',
                    'sekolahs.nama_sekolah'
            )
            ->where('reports.remainder', '=', 0)
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->paginate(25);
        }else{
            $tunggakan = Report::leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->where('reports.remainder', '=', 0)
            ->where('siswas.nama_siswa','LIKE','%'.$search.'%')
            ->orwhere('siswas.npm','LIKE','%'.$search.'%')
            ->orderBy('reports.last_date_payment', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'reports.spp',
                    'reports.has_payment_spp',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
        }

        // hitung jumlah seluruh pembayaran spp yang sudah dibayar
        $a = 0;
        $b = 0;
        $spps=0;
        $has_payment_spp=0;
        
        // hitung jumlah spp * semester, untuk nilai jumlah spp yang harus dibayar
        foreach ($tunggakan as $key => $spp) {
            $a += $spp->spp * $spp->semester ;
            $spps = $a;
        }

        // hitung jumlah spp yang harus dibayar
        foreach ($tunggakan as $key => $has_payment) {
            $b += $has_payment->has_payment_spp;
            $has_payment_spp = $b;
        }

        // hitung jumlah tunggakan
        $mod = $spps - $has_payment_spp;
        return view('admin.laporan.tunggakan',compact('tunggakan', 'has_payment_spp', 'spps', 'mod'), array('user' => $user));
    }

    public function search_all_siswa(Request $request){
        $user = Auth::user();
        $id_user = Auth::user()->id;
        $search = $request->get('search');
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();

        if($login->group=='Admin')
        {
            $all_siswa = Report::leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.npm', '=', 'reports.npm')
            ->orderBy('reports.last_date_payment', 'desc')
            ->where('reports.last_date_payment','=',$search)
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.kelas',
                    'siswas.semester',
                    'reports.form',
                    'reports.register',
                    'tunggakans.spp as spp_on_tunggakans',
                    'tunggakans.payment_amount as amount_on_tunggakans',
                    'reports.has_payment_spp',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);   
        }else{
            $all_siswa = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('reports', 'reports.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->leftjoin('tunggakans', 'tunggakans.candidates_id', '=', 'reports.candidate_id')
            ->where('reports.last_date_payment','=',$search)
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->select(
                'siswas.nama_siswa',
                'siswas.npm',
                'siswas.kelas',
                'siswas.semester',
                'reports.form',
                'reports.register',
                'reports.has_payment_spp',
                'tunggakans.spp',
                'tunggakans.payment_amount',
                'sekolahs.nama_sekolah'
            )
            ->paginate(25);
        }

        $formulirs = 0;
        $pendaftarans = 0;
        $pendidikans = 0;
        $tunggakans = 0;
        $jumlah_formulir = 0;
        $jumlah_register = 0;
        $jumlah_spp = 0;
        $jumlah_tunggakan = 0;

        foreach ($all_siswa as $key => $formulir) {
            $jumlah_formulir += $formulir->form ;
            $formulirs = $jumlah_formulir;
        }

        foreach ($all_siswa as $key => $pendaftaran) {
            $jumlah_register += $pendaftaran->register ;
            $pendaftarans = $jumlah_register;
        }

        foreach ($all_siswa as $key => $pendidikan) {
            $jumlah_spp += $pendidikan->has_payment_spp ;
            $pendidikans = $jumlah_spp;
        }

        foreach ($all_siswa as $key => $hutang) {
            $jumlah_tunggakan += ($hutang->spp_on_tunggakans * $hutang->semester) - $hutang->amount_on_tunggakans ;
            $tunggakans = $jumlah_tunggakan;
        }

        $total_seluruh = $formulirs + $pendaftarans + $pendidikans;
        return view('admin.laporan.harian',compact('formulirs', 'pendaftarans', 'pendidikans', 'tunggakans', 'total_seluruh', 'all_siswa', 'search'), array('user' => $user));
    }

    public function search_rekap(Request $request){
        $user = Auth::user();
        $id_user = Auth::user()->id;
        $search = $request->get('search');
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();
        $date = date('Y-m-d');
        // echo "<pre>";
        // print_r($search);
        // echo "</pre>";
        // exit();

        if($login->group=='Admin')
        {
            $rekap = DB::table('reports')
            ->leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->rightjoin('tunggakans', 'tunggakans.candidates_id', '=', 'reports.candidate_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->where('reports.last_date_payment','=',$search)
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('SUM(form) as total_formulir'), 
                DB::raw('SUM(register) as total_register'), 
                DB::raw('SUM(has_payment_spp) as total_spp'), 
                DB::raw('COUNT(reports.sekolah_id) as jumlah_transaksi'), 
                DB::raw('COUNT(tunggakans.sekolah_id) as jumlah_siswa'), 
                DB::raw('SUM(leftovers) as tunggakan'))
            ->groupBy('sekolahs.nama_sekolah')
            ->paginate(25);

            $tunggakan = DB::table('tunggakans')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'tunggakans.sekolah_id')
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('COUNT(tunggakans.sekolah_id) as jumlah_siswa'), 
                DB::raw('SUM(leftovers) as tunggakan'))
            ->groupBy('sekolahs.nama_sekolah')
            ->paginate(25);
        }else{
            $rekap = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('reports', 'reports.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'reports.candidate_id')
            ->where('reports.last_date_payment','=',$search)
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('SUM(form) as total_formulir'), 
                DB::raw('SUM(register) as total_register'), 
                DB::raw('SUM(has_payment_spp) as total_spp'), 
                DB::raw('COUNT(reports.sekolah_id) as jumlah_transaksi'), 
                DB::raw('SUM( (semester*tunggakans.spp)-tunggakans.payment_amount ) as tunggakan'))
            ->groupBy('sekolahs.nama_sekolah')
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->paginate(25);

            $tunggakan = DB::table('user_sekolahs')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->select(
                'sekolahs.nama_sekolah', 
                DB::raw('COUNT(tunggakans.sekolah_id) as jumlah_siswa'), 
                DB::raw('SUM(leftovers) as tunggakan'))
            ->groupBy('sekolahs.nama_sekolah')
            ->paginate(25);
        }

        $jumlah = 0;
        $formulirs = 0;
        $pendaftarans = 0;
        $pendidikans = 0;
        $tunggkans = 0;
        $total_tunggakans = 0;
        $must_pay = 0;
        $jumlah_formulir = 0;
        $jumlah_register = 0;
        $jumlah_spp = 0;
        $total_seluruh = 0;
        $jumlah_tunggakan = 0;

        foreach ($rekap as $key => $formulir) {
            $jumlah_formulir += $formulir->total_formulir ;
            $formulirs = $jumlah_formulir;
        }

        foreach ($rekap as $key => $pendaftaran) {
            $jumlah_register += $pendaftaran->total_register ;
            $pendaftarans = $jumlah_register;
        }

        foreach ($rekap as $key => $pendidikan) {
            $jumlah_spp += $pendidikan->total_spp ;
            $pendidikans = $jumlah_spp;
        }

        foreach ($rekap as $key => $hutang) {
            $jumlah_tunggakan += $hutang->tunggakan ;
            $tunggkans = $jumlah_tunggakan;
        }

        $total_seluruh = $formulirs + $pendaftarans + $pendidikans;

        return view('admin.laporan.rekap',compact('formulirs', 'pendaftarans', 'pendidikans', 'tunggkans', 'total_seluruh', 'rekap', 'tunggakan', 'search'), array('user' => $user));
    }

    public function filter_tunggakan(Request $request)
    {
    $user = Auth::user();
    $id_user = Auth::user()->id;
    $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                ->where('user_groups.user_id', Auth::id())
                ->select('groups.name AS group')
                ->first();

    $school     = Input::get('school');
    $classes 	= Input::get('class');
    $class 	    = Input::get('class');
        
	if($login->group=='Admin')
        {
		if($school && $class){
            $tunggakan = Tunggakan::leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'tunggakans.sekolah_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('siswas.sekolah_id', $school)
            ->where('siswas.kelas', $class)
            ->orderBy('tunggakans.updated_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
		}else if($school){
            $tunggakan = Tunggakan::leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'tunggakans.sekolah_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('siswas.sekolah_id', $school)
            ->orderBy('tunggakans.updated_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
		}else if($class){
            $tunggakan = Tunggakan::leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'tunggakans.sekolah_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('siswas.kelas', $class)
            ->orderBy('tunggakans.updated_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
		}else{
            $tunggakan = Tunggakan::leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->leftjoin('sekolahs', 'sekolahs.id', '=', 'tunggakans.sekolah_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->orderBy('tunggakans.updated_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
        }
    }else{
        if($school && $class){
            $tunggakan = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('siswas.sekolah_id', $school)
            ->where('siswas.kelas', $class)
            ->orderBy('tunggakans.created_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
		}else if($school){
            $tunggakan = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('siswas.sekolah_id', $school)
            ->orderBy('tunggakans.created_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
		}else if($class){
            $tunggakan = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('siswas.kelas', $class)
            ->orderBy('tunggakans.created_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
		}else{
            $tunggakan = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
            ->where('tunggakans.arrears_status', '=', 2)
            ->where('siswas.status', '=', "Aktif")
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->orderBy('tunggakans.created_at', 'desc')
            ->select(
                    'siswas.nama_siswa',
                    'siswas.npm',
                    'siswas.semester',
                    'tunggakans.spp',
                    'tunggakans.payment_amount',
                    'sekolahs.nama_sekolah'
            )
            ->paginate(25);
        }
    }

    if($login->group=='Admin')
        {
            if($school && $class){
                $has_payment_spp = DB::table('tunggakans')
                ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('siswas.status', '=', "Aktif")
                ->where('siswas.sekolah_id', $school)
                ->where('siswas.kelas', $class)
                ->sum('tunggakans.payment_amount');
            }else if($school){
                $has_payment_spp = DB::table('tunggakans')
                ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('siswas.status', '=', "Aktif")
                ->where('siswas.sekolah_id', $school)
                ->sum('tunggakans.payment_amount');
            }else if($class){
                $has_payment_spp = DB::table('tunggakans')
                ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('siswas.status', '=', "Aktif")
                ->where('siswas.kelas', $class)
                ->sum('tunggakans.payment_amount');
            }else{
                $has_payment_spp = DB::table('tunggakans')
                ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('siswas.status', '=', "Aktif")
                ->sum('tunggakans.payment_amount');
            }
        }else{
            if($school && $class){
                $has_payment_spp = DB::table('user_sekolahs')
                ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
                ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('user_sekolahs.user_id', '=', $id_user)
                ->where('siswas.status', '=', "Aktif")
                ->where('siswas.sekolah_id', $school)
                ->where('siswas.kelas', $class)
                ->sum('tunggakans.payment_amount');
            }else if($school){
                $has_payment_spp = DB::table('user_sekolahs')
                ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
                ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('user_sekolahs.user_id', '=', $id_user)
                ->where('siswas.status', '=', "Aktif")
                ->where('siswas.sekolah_id', $school)
                ->sum('tunggakans.payment_amount');
            }else if($class){
                $has_payment_spp = DB::table('user_sekolahs')
                ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
                ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('user_sekolahs.user_id', '=', $id_user)
                ->where('siswas.status', '=', "Aktif")
                ->where('siswas.kelas', $class)
                ->sum('tunggakans.payment_amount');
            }else{
                $has_payment_spp = DB::table('user_sekolahs')
                ->leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
                ->leftjoin('tunggakans', 'tunggakans.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
                ->where('tunggakans.arrears_status', '=', 2)
                ->where('user_sekolahs.user_id', '=', $id_user)
                ->where('siswas.status', '=', "Aktif")
                ->sum('tunggakans.payment_amount');
            }
        }

        // hitung jumlah seluruh pembayaran spp yang sudah dibayar
        $jumlah_spp = 0;
        $jm_tunggakan = 0;
        $a = 0;
        $must_pay = 0;
        $spps=0;
        foreach ($tunggakan as $key => $spp) {
            $jumlah_spp += $spp->spp * $spp->semester ;
            $spps = $jumlah_spp;
        }
        foreach ($tunggakan as $key => $sub_tunggakan) {
            $a += $sub_tunggakan->spp * $sub_tunggakan->semester;
            $must_pay = $a;
        }
        $mod = $must_pay - $has_payment_spp;

        // Filter
        $scholls = Sekolah::orderBy('created_at', 'desc')
        ->get();
        $class = Siswa::distinct()->whereNotNull('kelas')->get(['kelas']);
    return view('admin.laporan.tunggakan',compact('class', 'tunggakan', 'has_payment_spp', 'spps', 'mod', 'scholls', 'school', 'classes'), array('user' => $user));
    }
}