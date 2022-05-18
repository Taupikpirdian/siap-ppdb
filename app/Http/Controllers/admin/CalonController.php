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

class CalonController extends Controller
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
            return view('admin.calon.list',array('candidate'=>$candidate, 'user' => $user));
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
            return view('admin.calon.list',array('candidate'=>$candidate, 'user' => $user));
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
		return view('admin.calon.invoice', compact('name_user') , array('candidate'=>$candidate, 'user' => $user));
	}

    
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
        
        return view('admin.calon.show', compact('candidate','candidates','value'),array('user' => $user));
    }

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
        return view('admin.calon.list', compact('candidate'),array('user' => $user));
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
        return view('admin.calon.list',array('candidate'=>$candidate, 'user' => $user));
    }
}