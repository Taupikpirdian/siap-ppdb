<?php

namespace App\Http\Controllers\admin;

use Auth;
use DB;
use View;
use Session;
use App\Group;
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
use App\Approval;
use App\Report;
use App\Tunggakan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Imports\SppImport;
use Maatwebsite\Excel\Facades\Excel;

class SppController extends Controller
{
    public function index()
    {
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();

        if($login->group!='Admin')
        {
            $user = Auth::user();
            $id_user = Auth::user()->id;
            $spp = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->rightjoin('approvals', 'approvals.no_urut', '=', 'siswas.id')
            ->orderBy('approvals.created_at', 'desc')
            ->select(
                    'siswas.id as id_siswa',
                    'siswas.npm',
                    'siswas.nama_siswa',
                    'programs.nama as nm_prog',
                    'years.tahun as nm_year',
                    'sekolahs.nama_sekolah',
                    'approvals.no_urut',
                    'approvals.tgl_bayar',
                    'approvals.amount',
                    'approvals.ket',
                    'approvals.payment_id',
                    'approvals.semester',
                    'approvals.status_approvals',
                    'approvals.id'
                    )
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->paginate(25);
            return view('admin.spp.list', array('spp'=>$spp, 'user' => $user));
        }else{
            $user = Auth::user();
            $spp = Approval::leftjoin('sekolahs', 'sekolahs.id', '=', 'approvals.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'approvals.no_urut')
            ->orderBy('approvals.created_at', 'desc')
            ->select(
                    'siswas.id as id_siswa',
                    'siswas.npm',
                    'siswas.nama_siswa',
                    'sekolahs.nama_sekolah',
                    'approvals.no_urut',
                    'approvals.tgl_bayar',
                    'approvals.amount',
                    'approvals.ket',
                    'approvals.payment_id',
                    'approvals.semester',
                    'approvals.status_approvals',
                    'approvals.id'
                    )
            ->paginate(25);
            return view('admin.spp.list', array('spp'=>$spp, 'user' => $user));
        }
    }    

    public function import(Request $request) 
	{
		// validasi
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		// menangkap file excel
		$file = $request->file('file');

		// membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();

		// upload ke folder file_siswa di dalam folder public
		$file->move('file_spp',$nama_file);

		// import data
		Excel::import(new SppImport, public_path('/file_spp/'.$nama_file));

		// notifikasi dengan session
		Session::flash('sukses','Data Spp Berhasil Diimport!');

		// alihkan halaman kembali
		return redirect('/spp/index');
    }
    
    public function search(Request $request)
    {
        $search = $request->get('search');
        $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                    ->where('user_groups.user_id', Auth::id())
                    ->select('groups.name AS group')
                    ->first();

        if($login->group!='Admin')
        {
            $user = Auth::user();
            $id_user = Auth::user()->id;
            $spp = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
            ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->rightjoin('approvals', 'approvals.no_urut', '=', 'siswas.id')
            ->orderBy('user_sekolahs.created_at', 'desc')
            ->select(
                    'siswas.id as id_siswa',
                    'siswas.no_urut',
                    'siswas.npm',
                    'siswas.nama_siswa',
                    'sekolahs.nama_sekolah',
                    'approvals.tgl_bayar',
                    'approvals.amount',
                    'approvals.npm as npm_approve',
                    'approvals.ket',
                    'approvals.payment_id',
                    'approvals.status_approvals',
                    'approvals.semester'
                    )
            ->where('nama_siswa','LIKE','%'.$search.'%')
            ->orwhere('approvals.semester','LIKE','%'.$search.'%')
            ->orwhere('approvals.npm','LIKE','%'.$search.'%')
            ->paginate(25);
            return view('admin.spp.list', array('spp'=>$spp, 'user' => $user));
        }else{
            $user = Auth::user();
            $spp = Approval::leftjoin('sekolahs', 'sekolahs.id', '=', 'approvals.sekolah_id')
            ->leftjoin('siswas', 'siswas.id', '=', 'approvals.no_urut')
            ->orderBy('approvals.created_at', 'desc')
            ->select(
                    'siswas.id as id_siswa',
                    'siswas.no_urut',
                    'siswas.npm',
                    'siswas.nama_siswa',
                    'sekolahs.nama_sekolah',
                    'approvals.tgl_bayar',
                    'approvals.amount',
                    'approvals.ket',
                    'approvals.npm as npm_approve',
                    'approvals.status_approvals',
                    'approvals.payment_id',
                    'approvals.semester'
                    )
            ->where('nama_siswa','LIKE','%'.$search.'%')
            ->orwhere('approvals.semester','LIKE','%'.$search.'%')
            ->orwhere('approvals.npm','LIKE','%'.$search.'%')
            ->paginate(25);
            return view('admin.spp.list', array('spp'=>$spp, 'user' => $user));
        }
    }

    public function approve(Request $request, $id)
    {
        // store
        $user = Auth::user();
        // get id user
        $id_user = Auth::user()->id;
        // get data pembayaran yang di upload
        $approve = Approval::findOrFail($id);
        // get data siswa sesuai dengan data spp yang di upload
        $student = Siswa::orderBy('created_at', 'desc')
        ->where('siswas.id', $approve->no_urut)
        ->firstOrFail();

        $student_report = Report::leftjoin('siswas', 'siswas.npm', '=', 'reports.npm')
        ->where('reports.candidate_id', '=', $student->id)
        ->get();

        // membuat record baru di table pembayaran siswa aktif
        $pay = new PembayaranSiswaAktif;
        $pay->candidate_id    = $approve->no_urut;
        $pay->npm             = $approve->npm;
        $pay->user_id         = $id_user;
        $pay->tgl_bayar       = $approve->tgl_bayar;
        $pay->sekolah_id      = $approve->sekolah_id;

        // update invoice no
        $sekolah = Sekolah::where('id', $pay->sekolah_id)->firstOrFail();
        $sekolah->invoice_no = $sekolah->invoice_no + 1;

        $pay->no_kwitansi     = $sekolah->invoice_no;
        $pay->amount          = $approve->amount;
        $pay->ket             = $approve->ket;
        $pay->semester        = $approve->semester;
        $pay->payment_id      = $approve->payment_id;

        // dapatkan nilai spp yang harus di bayar per semester
        $costTotal = Cost::leftjoin('pembayaran_siswa_aktifs', 'pembayaran_siswa_aktifs.sekolah_id', '=', 'costs.sekolah_id')
        ->where('costs.sekolah_id', '=', $pay->sekolah_id)
        ->where('costs.thn_id', '=', $student->thn_id)
        ->where('costs.program_id', '=', $student->program_id)
        ->where('costs.payment_id', '=', $pay->payment_id)
        ->firstOrFail();

        $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
        ->get();

        $subjumlah = DB::table('pembayaran_siswa_aktifs')
        ->where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
        ->sum('amount');

        // kondisi jika pembayaran berlebihan
        if ($pay->semester == 1) {
            $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 1)
            ->sum('amount');
            $sum_with_input = $semester_spp + $pay->amount;
            if($sum_with_input > $costTotal->name){
                return Redirect::back()->withErrors(['Pembayaran melebihi jumlah seharusnya', 'The Message']);
            }
        } elseif($pay->semester == 2) {
            $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 2)
            ->sum('amount');
            $sum_with_input = $semester_spp + $pay->amount;
            if($sum_with_input > $costTotal->name){
                return Redirect::back()->withErrors(['Pembayaran melebihi jumlah seharusnya', 'The Message']);
            }
        } elseif($pay->semester == 3) {
            $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 3)
            ->sum('amount');
            $sum_with_input = $semester_spp + $pay->amount;
            if($sum_with_input > $costTotal->name){
                return Redirect::back()->withErrors(['Pembayaran melebihi jumlah seharusnya', 'The Message']);
            }
        } elseif($pay->semester == 4) {
            $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 4)
            ->sum('amount');
            $sum_with_input = $semester_spp + $pay->amount;
            if($sum_with_input > $costTotal->name){
                return Redirect::back()->withErrors(['Pembayaran melebihi jumlah seharusnya', 'The Message']);
            }
        } elseif($pay->semester == 5) {
            $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 5)
            ->sum('amount');
            $sum_with_input = $semester_spp + $pay->amount;
            if($sum_with_input > $costTotal->name){
                return Redirect::back()->withErrors(['Pembayaran melebihi jumlah seharusnya', 'The Message']);
            }
        } elseif($pay->semester == 6) {
            $semester_spp = PembayaranSiswaAktif::where('pembayaran_siswa_aktifs.candidate_id', '=', $pay->candidate_id)
            ->where('pembayaran_siswa_aktifs.semester', '=', 6)
            ->sum('amount');
            $sum_with_input = $semester_spp + $pay->amount;
            if($sum_with_input > $costTotal->name){
                return Redirect::back()->withErrors(['Pembayaran melebihi jumlah seharusnya', 'The Message']);
            }
        }
        // End

        // mengecek data report sesuai candidate id
        $spp_report_update = Report::orderBy('created_at', 'desc')
        ->where('candidate_id', $student->id)
        ->first();

        $spp_masuk = $subjumlah + $pay->amount;
        if (empty($spp_report_update)) {
        }else{
            $spp_update = $spp_report_update->has_payment_spp + $pay->amount;
        }
        $spp = $costTotal->name * $pay->semester;
        $remained = $spp - $spp_masuk;
        
        // #1 Ketika memiliki candidate_id dan payment diisi 3, work
        if ($student->id && $pay->payment_id == 3) {
            $report = Report::orderBy('reports.created_at', 'desc')
            ->where('candidate_id', $student->id)
            ->first();

            // Masuk ke table tunggakans
            $tunggakan = Tunggakan::where('candidates_id', $student->id)
            ->first();
            $tunggakan->candidates_id        = $approve->no_urut;
            $tunggakan->npm                  = $approve->npm;
            $tunggakan->spp                  = $costTotal->name;
            $tunggakan->payment_amount       = $tunggakan->payment_amount + $pay->amount;
            $tunggakan->leftovers            = ($student->semester*$costTotal->name) - $tunggakan->payment_amount;
            $tunggakan->save();
                      
            // Ketika tanggal pembayarannya sama
            if ($report->last_date_payment == $pay->tgl_bayar) {
                $report = Report::orderBy('reports.created_at', 'desc')
                ->where('candidate_id', $student->id)
                ->firstOrFail();
                // Jika pernah membayar di tanggal yang sama, maka menjumlah, jika tidak maka tidak menjumlah
                if ($report->has_payment_spp) {
                    // npm terus update sesuai candidate id yang sama, error
                    $report->npm                  = $approve->npm;
                    $report->candidate_id         = $approve->no_urut;
                    $report->has_payment_spp      = $spp_update;
                    $report->last_date_payment    = $pay->tgl_bayar;
                    $report->save();
                } else {
                    $report->npm                  = $approve->npm;
                    $report->candidate_id         = $approve->no_urut;
                    $report->has_payment_spp      = $pay->amount;
                    $report->last_date_payment    = $pay->tgl_bayar;
                    $report->save();
                }

            } else {
                $report_new = new Report;
                $report_new->candidate_id         = $approve->no_urut;
                $report_new->npm                  = $approve->npm;
                $report_new->has_payment_spp      = $pay->amount;
                $report_new->sekolah_id           = $pay->sekolah_id;
                $report_new->last_date_payment    = $pay->tgl_bayar;
                $report_new->save();  
            }
        // #2 Ketika no urut siswa bersangkutas di table report tidak ada, work
        } elseif ( $student_report->isEmpty() ) {
            // bukan
            $report_new = new Report;
            $report_new->candidate_id         = $approve->no_urut;
            $report_new->npm                  = $approve->npm;
            $report_new->has_payment_spp      = $pay->amount;
            $report_new->sekolah_id           = $pay->sekolah_id;
            $report_new->last_date_payment    = $pay->tgl_bayar;
            $report_new->save();

            $tunggakan = new Tunggakan;
            $tunggakan->candidates_id        = $approve->no_urut;
            $tunggakan->npm                  = $approve->npm;
            $tunggakan->spp                  = $costTotal->name;
            $tunggakan->sekolah_id           = $pay->sekolah_id;
            $tunggakan->payment_amount       = $tunggakan->payment_amount + $pay->amount;
            $tunggakan->leftovers            = ($student->semester*$costTotal->name) - $tunggakan->payment_amount;
            $tunggakan->save();            
        // #3 Ketika sudah ada npm siswa bersangkutan di table report dan payment diisi 3, work
        } elseif ( $student_report && $pay->payment_id == 3 ) {
            // Masuk ke table tunggakans
            $tunggakan = Tunggakan::where('candidates_id', $student->id)
            ->first();
            $tunggakan->npm                  = $approve->npm;
            $tunggakan->spp                  = $costTotal->name;
            $tunggakan->sekolah_id           = $pay->sekolah_id;
            $tunggakan->payment_amount       = $tunggakan->payment_amount + $pay->amount;
            $tunggakan->leftovers            = ($student->semester*$costTotal->name) - $tunggakan->payment_amount;
            $tunggakan->save();

            $report = Report::orderBy('created_at', 'desc')
            ->where('candidate_id', $student->id)
            ->firstOrFail();
            if ($report->last_date_payment == $pay->tgl_bayar) {
                $report->npm                  = $approve->npm;
                $report->has_payment_spp      = $spp_update;
                $report->sekolah_id           = $pay->sekolah_id;
                $report->last_date_payment    = $pay->tgl_bayar;
                $report->save();            
            } else {
                $report_new = new Report;
                $report_new->candidate_id         = $approve->no_urut;
                $report_new->npm                  = $approve->npm;
                $report_new->has_payment_spp      = $pay->amount;
                $report_new->sekolah_id           = $pay->sekolah_id;
                $report_new->last_date_payment    = $pay->tgl_bayar;
                $report_new->save();
            }
        }

        $status_tunggakan = Tunggakan::orderBy('tunggakans.created_at', 'desc')
        ->leftjoin('siswas', 'siswas.id', '=', 'tunggakans.candidates_id')
        ->where('tunggakans.candidates_id', $student->id)
        ->first();

        if ($status_tunggakan) {
            if ( ($status_tunggakan->payment_amount >= ($status_tunggakan->spp*$status_tunggakan->semester) ) ) {
                $tunggakan_on_table_tunggakan = Tunggakan::where('candidates_id', $student->id)
                ->first();
                $tunggakan_on_table_tunggakan->arrears_status = 1;
                $tunggakan_on_table_tunggakan->save();
            } else {
                $tunggakan_on_table_tunggakan = Tunggakan::where('candidates_id', $student->id)
                ->first();
                $tunggakan_on_table_tunggakan->arrears_status = 2;
                $tunggakan_on_table_tunggakan->save();
            }
        } else {
            $tunggakan_on_table_tunggakan = Tunggakan::where('candidates_id', $student->id)
            ->first();
            $tunggakan_on_table_tunggakan->arrears_status = 1;
            $tunggakan_on_table_tunggakan->save();
        }
        
        $npm_update = Report::orderBy('reports.created_at', 'desc')
        ->where('candidate_id', $student->id)
        ->get();
        // update npm
        foreach ($npm_update as $key => $value) {
            $value->npm                  = $approve->npm;
            $value->save();
        }

        // update status approve
        $approve = Approval::findOrFail($id);
        $approve->status_approvals      = 1;

        // Proses save
        $sekolah->save();
        $pay->save();
        $approve->save();
        // redirect
        return Redirect::action('admin\SppController@index')->with('flash-approve','Pembayaran disetujui');
    }

    public function disagree(Request $request, $id)
    {
        // store
        $approve = Approval::findOrFail($id);
        $approve->status_approvals      = 2;
        $approve->save();
        // redirect
        return Redirect::action('admin\SppController@index')->with('flash-disagree','Pembayaran tidak disetujui');
    }
}