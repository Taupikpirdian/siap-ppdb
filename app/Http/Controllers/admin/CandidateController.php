<?php

namespace App\Http\Controllers\admin;

use Auth;
use View;
use Image;
use Excel;
use App\Sekolah;
use App\Report;
use App\UserSekolah;
use App\Group;
use App\Siswa;
use App\Year;
use App\Program;
use App\PaymentType;
use App\Cost;
use App\Candidate;
use App\DetailPaymentCandidate;
use App\Tunggakan;
use App\Exports\CandidateExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class CandidateController extends Controller
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

            $candidate = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('siswas', 'siswas.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'user_sekolahs.*',
                    'programs.nama as nm_prog',
                    'years.tahun',
                    'sekolahs.*',
                    'siswas.*'
                    )
            ->where('user_sekolahs.user_id', '=', $id_user)
            ->where('status', '=', "Calon")
            ->paginate(25);
            return view('admin.candidate.list',array('candidate'=>$candidate, 'user' => $user));
        }else{
            $user = Auth::user();
            $candidate = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
            ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->orderBy('siswas.created_at', 'desc')
            ->select(
                    'years.tahun',
                    'sekolahs.*',
                    'programs.nama as nm_prog',
                    'siswas.*'
                )
            ->where('status', '=', "Calon")
            ->paginate(25);
            return view('admin.candidate.list',array('candidate'=>$candidate, 'user' => $user));
        }
    }   

    public function invoice($id)
	{   
		$user = Auth::user();
        $id_user = Auth::user()->id;
        $name_user = Auth::user()->name;
        $candidate = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
            ->leftjoin('detail_payment_candidates', 'detail_payment_candidates.candidates_id', '=', 'siswas.id')
            ->leftjoin('costs', 'costs.id', '=', 'detail_payment_candidates.cost_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->leftJoin('programs', 'programs.id', '=', 'siswas.program_id')
            ->orderBy('siswas.created_at', 'asc')
            ->where('detail_payment_candidates.id', $id)
            ->select(   
                'sekolahs.nama_sekolah',
                'sekolahs.invoice_no',
                'detail_payment_candidates.cost_id as cost_name',
                'programs.nama as program_nama',
                'payment_types.name as payment_name',
                'payment_types.alias',
                'siswas.*',
                'detail_payment_candidates.*'
            )
            ->firstOrFail();
		return view('admin.candidate.invoice', compact('name_user') , array('candidate'=>$candidate, 'user' => $user));
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $scholls=Sekolah::pluck('nama_sekolah', 'id');
        $scholls->prepend('Pilih Nama Sekolah', '');
        $years=Year::pluck('tahun', 'id');
        $progs=Program::pluck('nama', 'id');
        $payments=PaymentType::whereIn('id', ['1'])->pluck('name', 'id');
        return View::make('admin.candidate.create', compact('years',  'scholls', 'progs', 'payments'), array('user' => $user));
    }

    public function store(Request $request)
    {
        // store
        $candidate= new Siswa;
        $candidate->user_id        = Auth::user()->id;
        $candidate->sekolah_id     = Input::get('sekolah_id');
        $candidate->thn_id         = Input::get('thn_id');
        $candidate->program_id     = Input::get('program_id');
        $candidate->nama_siswa     = Input::get('nama');
        $candidate->tgl_lahir      = Input::get('tgl_lahir');
        $candidate->status         = "Calon";
        $candidate->payment_status = 1;
        $candidate->save();

        $sekolah = Sekolah::where('id', $candidate->sekolah_id)->firstOrFail();
        $sekolah->invoice_no = $sekolah->invoice_no + 1; 
        $sekolah->save();

        $candidate = Siswa::orderBy('created_at', 'desc')->firstOrFail();

        // echo "<pre>";
        // print_r($candidate->id);
        // echo "</pre>";
        // exit();
        
        $detailpaymentcandidate= new DetailPaymentCandidate;
        $detailpaymentcandidate->user_id          = Auth::user()->id;
        $detailpaymentcandidate->tgl_bayar        = Input::get('tgl_bayar');
        $detailpaymentcandidate->no_kwitansi      = $sekolah->invoice_no;
        $detailpaymentcandidate->candidates_id    = $candidate->id; // sesuai dengan id candidate
        $detailpaymentcandidate->payment_id       = Input::get('payment_id');
        $detailpaymentcandidate->cost_id          = Input::get('cost_id');
        $detailpaymentcandidate->sekolah_id       = Input::get('sekolah_id');
        $detailpaymentcandidate->thn_id           = Input::get('thn_id');
        $detailpaymentcandidate->program_id       = Input::get('program_id');

        $report= new Report;
        $report->candidate_id       = $candidate->id;
        $report->sekolah_id         = Input::get('sekolah_id');
        if ($detailpaymentcandidate->payment_id == 1) {
            $report->form           = $detailpaymentcandidate->cost_id;
        } else {
            $report->register       = $detailpaymentcandidate->cost_id;
        }
        $report->last_date_payment  = $detailpaymentcandidate->tgl_bayar;
        $report->save();

        $tunggakan= new Tunggakan;
        $tunggakan->candidates_id    = $candidate->id; // sesuai dengan id candidate
        $tunggakan->sekolah_id       = Input::get('sekolah_id');

        $tunggakan->save();
        $detailpaymentcandidate->save();
        // redirect
        return Redirect::action('admin\CandidateController@show', compact('candidate', 'sekolah', 'detailpaymentcandidate', 'report'))->with('flash-store','Data berhasil ditambahkan.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $user = Auth::user();
        $candidate = Siswa::leftJoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
        ->leftJoin('detail_payment_candidates', 'detail_payment_candidates.candidates_id', '=', 'siswas.id')
        ->leftJoin('years', 'years.id', '=', 'siswas.thn_id')
        ->select(
                  'years.tahun',
                  'sekolahs.*',
                  'siswas.*'
                 )
        ->where('siswas.id', $id)
        ->firstOrFail();

        $candidates = Siswa::orderBy('detail_payment_candidates.created_at', 'desc')
        ->leftJoin('detail_payment_candidates', 'detail_payment_candidates.candidates_id', '=', 'siswas.id')
        ->leftJoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
        ->leftJoin('years', 'years.id', '=', 'siswas.thn_id')
        ->leftJoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
        ->whereCandidatesId($id)
        ->select(
                  'sekolahs.nama_sekolah',
                  'years.tahun',
                  'detail_payment_candidates.*',
                  'siswas.*',   
                  'detail_payment_candidates.id as id_invoice',
                  'detail_payment_candidates.id as payment_id',
                  'payment_types.name as payment_name'
                 )
        ->get();

        $jumlah = 0;
        foreach ($candidates as $key => $value) {
            $jumlah += $value->cost_id;
            $value->amounts = $jumlah;
        }
        
        return view('admin.candidate.show', compact('candidate','candidates','value'),array('user' => $user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $user = Auth::user();
        $scholl=Sekolah::pluck('nama_sekolah', 'id');
        $scholl->prepend('Pilih Nama Sekolah', '');
        $year=Year::pluck('tahun', 'id');
        $year->prepend('Pilih Tahun Masuk', '');
        $prog=Program::pluck('nama', 'id');
        $prog->prepend('Pilih Program', '');
        $candidate = Siswa::leftJoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
        ->leftJoin('years', 'years.id', '=', 'siswas.thn_id')
        ->leftJoin('programs', 'programs.id', '=', 'siswas.program_id')
        ->orderBy('created_at', 'desc')
        ->select(
                    'siswas.*',
                    'sekolahs.nama_sekolah',
                    'years.tahun',
                    'programs.nama as nm_prog'
                )
        ->findOrFail($id);
        return view('admin.candidate.edit', compact('candidate', 'scholl', 'year', 'prog'),array('user' => $user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $candidate = Siswa::findOrFail($id);
        $candidate->user_id        = Auth::user()->id;
        $candidate->nama_siswa     = Input::get('nama_siswa');
        $candidate->tgl_lahir      = Input::get('tgl_lahir');
        $candidate->sekolah_id     = Input::get('sekolah_id');
        $candidate->thn_id         = Input::get('thn_id');
        $candidate->program_id     = Input::get('program_id');
        $candidate->save();
        return Redirect::action('admin\CandidateController@index', compact('candidate'))->with('flash-update','Data berhasil diubah.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $candidate = Candidate::where('id', $id)->firstOrFail();
        $candidate->delete();
        return Redirect::action('admin\CandidateController@index')->with('flash-destroy','Data berhasil dihapus.');
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
        $candidate = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
        ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
        ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
        ->orderBy('siswas.created_at', 'desc')
        ->select(
            'siswas.*',
            'years.tahun',
            'programs.nama as nm_prog',
            'sekolahs.nama_sekolah')
        ->where('nama_sekolah','LIKE','%'.$search.'%')
        ->where('status', '=', "Calon")
        ->paginate(25);
        return view('admin.candidate.list', compact('candidate'),array('user' => $user));
    }

    public function payments(){
      $year_id = Input::get('year_id');
      $payments = PaymentType::where('year_id', '=', $year_id)->get();
      return response()->json($payments);
    }

    public function amount(){
      $payment_id = Input::get('payment_id');
      $amount = Cost::where('payment_id', '=', $payment_id)->get();
      return response()->json($amount);
    }
    
    public function ajax_getsekolah_tahun(Request $request)
    {
      $cos = Cost::where('cost_id','=',$request->get('cost_id'))->get();

      return response()->json($cos);
    }

    public function exportFile($type){
        return Excel::download(new CandidateExport, 'calon.xlsx');
    }

    public function pay($id)
    {
        $user = Auth::user();
        $scholl=Sekolah::pluck('nama_sekolah', 'id');
        $scholl->prepend('Pilih Nama Sekolah', '');
        $year=Year::pluck('tahun', 'id');
        $prog=Program::pluck('nama', 'id');
        $prog->prepend('Pilih Program', '');
        $payment=PaymentType::whereIn('id', ['2'])->pluck('name', 'id');
        $costs=Cost::pluck('name', 'id');
        $costs->prepend('Pilih Biaya', '');
        
        $candidates = Siswa::orderBy('detail_payment_candidates.created_at', 'desc')
        ->leftJoin('detail_payment_candidates', 'detail_payment_candidates.candidates_id', '=', 'siswas.id')
        ->leftJoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
        ->leftJoin('years', 'years.id', '=', 'siswas.thn_id')
        ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
        ->leftjoin('costs', 'costs.id', '=', 'detail_payment_candidates.cost_id')
        ->leftJoin('programs', 'programs.id', '=', 'siswas.program_id')
        ->whereCandidatesId($id)
        ->select(
                  'sekolahs.nama_sekolah',
                  'years.tahun',
                  'programs.nama as nm_prog',
                  'payment_types.name as nm_payment',
                  'costs.name as nm_cost',
                  'detail_payment_candidates.*',
                  'siswas.*'
                 )
        ->get();   

        $candidate = Siswa::where('id', $id)->firstOrFail();
        return view('admin.candidate.payment', compact('candidate', 'candidates', 'scholl', 'year', 'prog', 'payment', 'costs'),array('user' => $user));
    }

    public function payment(Request $request)
    {
        // store
        $payment= new DetailPaymentCandidate;
        $payment->user_id             = Auth::user()->id;
        $payment->candidates_id       = Input::get('candidates_id');
        $payment->tgl_bayar           = Input::get('tgl_bayar');
        $payment->sekolah_id          = Input::get('sekolah_id');

        $candidate = Siswa::where('id', $payment->candidates_id)->firstOrFail();
        $candidate->payment_status    = 2; 
        $candidate->save();

        $sekolah = Sekolah::where('id', $payment->sekolah_id)->firstOrFail();
        $sekolah->invoice_no = $sekolah->invoice_no + 1; 
        $sekolah->save();

        $payment->no_kwitansi         = $sekolah->invoice_no;
        $payment->thn_id              = Input::get('thn_id');
        $payment->program_id          = Input::get('program_id');
        $payment->payment_id          = Input::get('payment_id');
        $payment->cost_id             = Input::get('cost_id');

        $report = Report::where('candidate_id', $payment->candidates_id)->firstOrFail();

        if ($report->last_date_payment == $payment->tgl_bayar) {
            $report->register       = $payment->cost_id;
        } else {
            $report_new= new Report;
            $report_new->sekolah_id          = Input::get('sekolah_id');
            $report_new->candidate_id        = Input::get('candidates_id');
            $report_new->register            = $payment->cost_id;
            $report_new->last_date_payment   = Input::get('tgl_bayar');
            $report_new->save();
        }

        $tunggakan = Tunggakan::where('candidates_id', $payment->candidates_id)->firstOrFail();
        $tunggakan->sekolah_id          = Input::get('sekolah_id');

        $report->save();
        $tunggakan->save();
        $payment->save();
        // redirect
        return Redirect::action('admin\CandidateController@show', ['id' => $candidate->id])->with('flash-store','Data berhasil diinput.');
    }

    public function approve(Request $request, $id)
    {
        // store
        $approve = Siswa::findOrFail($id);
        $approve->status              = 'Aktif';
        $approve->payment_status      = 3;
        $approve->semester            = 1;
        $approve->save();
        // redirect
        return Redirect::action('admin\CandidateController@index')->with('flash-approve','Siswa sudah aktif');
    }

    public function approve_on_show(Request $request)
    {
        // store
        $approve = Siswa::where('id', Input::get('candidate_id'))->firstOrFail();
        $approve->status                 = "Aktif";
        $approve->payment_status         = 3;
        $approve->semester               = 1;
        $approve->save();
        // redirect
        return Redirect::action('admin\CandidateController@index')->with('flash-approve','Siswa sudah aktif');
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $id_user = Auth::user()->id;
        $nama   = Input::get('nama');
		$month 	= Input::get('bulan');
		$year 	= Input::get('year');
	
		if($nama && $month && $year){
            $candidate = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
                ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
                ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
                ->orderBy('siswas.created_at', 'desc')
                ->select(
                        'years.tahun',
                        'sekolahs.*',
                        'programs.nama as nm_prog',
                        'siswas.*'
                    )
                ->where('siswas.nama_siswa','LIKE','%'.$nama.'%')
                ->whereYear('siswas.created_at', $year)
                ->whereMonth('siswas.created_at', '=' , $month)
                ->where('status', '=', "Calon")
                ->paginate(25);
		}else if($month && $year){
            $candidate = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
                ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
                ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
                ->orderBy('siswas.created_at', 'desc')
                ->select(
                        'years.tahun',
                        'programs.nama as nm_prog',
                        'sekolahs.*',
                        'siswas.*'
                    )
                ->whereYear('siswas.created_at', $year)
                ->whereMonth('siswas.created_at', '=' , $month)
                ->where('status', '=', "Calon")
                ->paginate(25);
		}else if($nama && $month){
            $candidate = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
                ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
                ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
                ->orderBy('siswas.created_at', 'desc')
                ->select(
                        'years.tahun',
                        'programs.nama as nm_prog',
                        'sekolahs.*',
                        'siswas.*'
                    )
                ->where('siswas.nama_siswa','LIKE','%'.$nama.'%')
                ->whereMonth('siswas.created_at', '=' , $month)
                ->where('status', '=', "Calon")
                ->paginate(25);
		}else if($nama && $year){
            $candidate = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
                ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
                ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
                ->orderBy('siswas.created_at', 'desc')
                ->select(
                        'years.tahun',
                        'programs.nama as nm_prog',
                        'sekolahs.*',
                        'siswas.*'
                    )
                ->where('siswas.nama_siswa','LIKE','%'.$nama.'%')
                ->whereYear('siswas.created_at', $year)
                ->where('status', '=', "Calon")
                ->paginate(25);
		}else if($nama){
            $candidate = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
                ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
                ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
                ->orderBy('siswas.created_at', 'desc')
                ->select(
                        'years.tahun',
                        'programs.nama as nm_prog',
                        'sekolahs.*',
                        'siswas.*'
                    )
                ->where('siswas.nama_siswa','LIKE','%'.$nama.'%')
                ->where('status', '=', "Calon")
                ->paginate(25);
		}else if($month){
            $candidate = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
                ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
                ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
                ->orderBy('siswas.created_at', 'desc')
                ->select(
                        'years.tahun',
                        'programs.nama as nm_prog',
                        'sekolahs.*',
                        'siswas.*'
                    )
                ->whereMonth('siswas.created_at', '=' , $month)
                ->where('status', '=', "Calon")
                ->paginate(25);
		}else if($year){
            $candidate = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
                ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
                ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
                ->orderBy('siswas.created_at', 'desc')
                ->select(
                        'years.tahun',
                        'programs.nama as nm_prog',
                        'sekolahs.*',
                        'siswas.*'
                    )
                ->whereYear('siswas.created_at', $year)
                ->where('status', '=', "Calon")
                ->paginate(25);
		}else{
            $candidate = Siswa::leftjoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
                ->leftjoin('years', 'years.id', '=', 'siswas.thn_id')
                ->leftjoin('programs', 'programs.id', '=', 'siswas.program_id')
                ->orderBy('siswas.created_at', 'desc')
                ->select(
                        'years.tahun',
                        'programs.nama as nm_prog',
                        'sekolahs.*',
                        'siswas.*'
                    )
                ->where('status', '=', "Calon")
                ->paginate(25);
        }
        return view('admin.candidate.list',array('candidate'=>$candidate, 'user' => $user));
    }
}