<?php

namespace App\Http\Controllers\admin;

use PDF;
use Auth;
use View;
use Image;
use File;
use Alert;
use Validator;
use App\Pengeluaran;
use App\Sekolah;
use App\BuktiPengeluaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class PengeluaranController extends Controller
{
    public function index()
	{   
		$user = Auth::user();
		$luaran = Pengeluaran::orderBy('created_at', 'desc')
				->leftJoin('sekolahs', 'sekolahs.id', '=', 'pengeluarans.id_sekolah')
                ->select(
                          	'pengeluarans.*',
                          	'sekolahs.nama_sekolah'
                        )
				->paginate(10);
		return view('admin.pengeluaran.list',array('luaran'=>$luaran, 'user' => $user));
	}

	public function invoice($id)
	{   
		$user = Auth::user();
		$luaran = Pengeluaran::orderBy('created_at', 'asc')->findOrFail($id);
		return view('admin.pengeluaran.invoice',array('luaran'=>$luaran, 'user' => $user));
	}

	public function create()
	{
    	$school=Sekolah::pluck('nama_sekolah', 'id');
        $school->prepend('Pilih Sekolah', '');
        // $luaran = Pengeluaran::where('id', $id)->firstOrFail();   

		return View::make('admin.pengeluaran.create', compact( 'school'));
	}

	public function store(Request $request)
	{
		$rules = [
			'id_sekolah'    		=>'|required',
			'kode_pengeluaran'    	=>'|required',
			'tgl_keluar'      		=>'|required',
			'asal_pengeluaran'     	=>'|required',
			'nama_pengeluaran'     	=>'|required',
			'jumlah'      			=>'|required',
		];

		$messages = [
			'required'  => 'Input :attribute harus diisi!',
		];

		Validator::make($request->all(), $rules, $messages)->validate();

		$luaran = new Pengeluaran;
		$luaran->user_id         		= Auth::user()->id;
		$luaran->id_sekolah      		= Input::get('id_sekolah');
		$luaran->kode_pengeluaran      	= Input::get('kode_pengeluaran');
		$luaran->tgl_keluar        		= Input::get('tgl_keluar');
		$luaran->asal_pengeluaran     	= Input::get('asal_pengeluaran');
		$luaran->nama_pengeluaran      	= Input::get('nama_pengeluaran');
		$luaran->jumlah        			= Input::get('jumlah');
		$luaran->giro        			= Input::get('giro');
		$luaran->no_giro       			= Input::get('no_giro');
		$luaran->ket        			= Input::get('ket');
		$luaran->terbilang    			= Input::get('terbilang');
		$luaran->status_tuntas   	    = 2;
		$luaran->save();

	return Redirect::action('admin\PengeluaranController@index')->with('flash-store','Data berhasil ditambahkan.');
	}

	public function show($id)
	{
		$luaran = Pengeluaran::findOrFail($id);
		$buktipengeluaran = BuktiPengeluaran::leftjoin('pengeluarans', 'pengeluarans.id', '=', 'bukti_pengeluarans.id_pengeluaran')
        ->orderBy('bukti_pengeluarans.created_at', 'desc')
        ->select(
            'pengeluarans.*',
            'bukti_pengeluarans.*')
        ->whereIdPengeluaran($id)
        ->get();

		$jumlah_tuntas = 0;
		$total = 0;
		$sisa = 0;
        foreach ($buktipengeluaran as $key => $value) {
            $jumlah_tuntas += $value->jumlah_pengeluaran;
            $total = $value->jumlah + $value->giro;
            $value->sisa = $total - $jumlah_tuntas;
        }

        // echo "<pre>";
        // print_r($buktipengeluaran);
        // echo "</pre>";
        // exit();

		return view('admin.pengeluaran.show',compact('value', 'buktipengeluaran'), [ 'luaran' => $luaran]);
	}

	public function edit($id)
	{
		$school=Sekolah::pluck('nama_sekolah', 'id');
        $school->prepend('Pilih Sekolah', '');
		$luaran = Pengeluaran::findOrFail($id);
		// echo "<pre>";
		// print_r($luaran);
		// echo "</pre>";
		// exit();

		return view('admin.pengeluaran.edit', array('luaran' => $luaran, 'school' => $school));
	}

	public function update(Request $request, $id)
	{
		$rules = [
			'id_sekolah'      		=>'|required',
			'kode_pengeluaran'     	=>'|required',
			'tgl_keluar'      		=>'|required',
			'asal_pengeluaran'     	=>'|required',
			'nama_pengeluaran'     	=>'|required',
			'jumlah'      			=>'|required',
		];

		$messages = [
			'required'  => 'Input :attribute harus diisi!',
		];

		Validator::make($request->all(), $rules, $messages)->validate();

		$luaran = Pengeluaran::findOrFail($id);
		$luaran->user_id         		= Auth::user()->id;
		$luaran->id_sekolah      		= Input::get('id_sekolah');
		$luaran->kode_pengeluaran      	= Input::get('kode_pengeluaran');
		$luaran->tgl_keluar        		= Input::get('tgl_keluar');
		$luaran->asal_pengeluaran     	= Input::get('asal_pengeluaran');
		$luaran->nama_pengeluaran      	= Input::get('nama_pengeluaran');
		$luaran->jumlah        			= Input::get('jumlah');
		$luaran->giro        			= Input::get('giro');
		$luaran->no_giro       			= Input::get('no_giro');
		$luaran->ket        			= Input::get('ket');
		$luaran->terbilang    			= Input::get('terbilang');
		$luaran->save();

		return Redirect::action('admin\PengeluaranController@index')->with('flash-update','Data berhasil diubah.');
	}

	public function destroy($id)
	{

		if(Auth::user()->hasAnyRole('Delete Pengeluaran')){
		$pengeluaran 	= pengeluaran::findOrFail($id);
		$pengeluarans 	= BuktiPengeluaran::orderBy('bukti_pengeluarans.created_at','asc')
	  	->leftJoin('pengeluarans', 'pengeluarans.id', '=', 'bukti_pengeluarans.id_pengeluaran')
	  	->whereIdPengeluaran($id)
	  	->get();
	      // echo "<pre>";
	      // print_r($group);
	      // echo "</pre>";
	      // exit();

	  	if(!$pengeluarans->isEmpty()){
	      	return Redirect::action('admin\PengeluaranController@index')->with('flash-error','Pengeluaran tidak bisa dihapus karena sudah diacu di Data Pertanggung Jawaban, Silakan menghapus data yang mengacu Pengeluaran terlebih dahulu.');  
	 	 }else{
	      	$pengeluaran->delete();
	      	return Redirect::action('admin\PengeluaranController@index')->with('flash-success','Pengeluaran sudah berhasil dihapus.');
	 	 }
		}else{
	  		return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
		}

		// $luaran = Pengeluaran::findOrFail($id);
		// $luaran->delete();

		// return Redirect::action('admin\PengeluaranController@index')->with('flash-destroy','Data berhasil dihapus.');
	}

	public function search(Request $request)
	{
		$search = $request->get('search');
		$luaran = Pengeluaran::orderBy('created_at', 'desc')
				->leftJoin('sekolahs', 'sekolahs.id', '=', 'pengeluarans.id_sekolah')
                ->select(
                          	'pengeluarans.*',
                          	'sekolahs.nama_sekolah'
                        )
		->where('nama_pengeluaran','LIKE','%'.$search.'%')
		->orwhere('nama_sekolah','LIKE','%'.$search.'%')
		->orwhere('asal_pengeluaran','LIKE','%'.$search.'%')
		->orwhere('kode_pengeluaran','LIKE','%'.$search.'%')
		->paginate(25);

		return view('admin.pengeluaran.list', compact('luaran'));
	}

	public function filter(Request $request)
	{
		// $rules = [
        //     'filter'   =>'|required',
        //     'bulan'    =>'|required',
        //     'year'     =>'|required',
        // ];

        // $messages = [
        //     'required'      => ' Status harus diisi!',
        //     'required'      => ' Bulan harus diisi!',
        //     'required'      => ' Form Tahun harus diisi!',
        // ];
		// Validator::make($request->all(), $rules, $messages)->validate();
		
		$status = Input::get('filter');
		$month 	= Input::get('bulan');
		$year 	= Input::get('year');
	
		if($status && $month && $year){
			$luaran = Pengeluaran::orderBy('created_at', 'asc')
				->leftJoin('sekolahs', 'sekolahs.id', '=', 'pengeluarans.id_sekolah')
				->select(
					'pengeluarans.*',
					'sekolahs.nama_sekolah'
				)
				->where('status_tuntas', $status)
				->whereYear('pengeluarans.tgl_keluar', $year)
				->whereMonth('pengeluarans.tgl_keluar', '=' , $month)
				->paginate(25);
		}else if($month && $year){
			$luaran = Pengeluaran::orderBy('created_at', 'asc')
				->leftJoin('sekolahs', 'sekolahs.id', '=', 'pengeluarans.id_sekolah')
				->select(
					'pengeluarans.*',
					'sekolahs.nama_sekolah'
				)
				->whereYear('pengeluarans.tgl_keluar', $year)
				->whereMonth('pengeluarans.tgl_keluar', '=' , $month)
				->paginate(25);
		}else if($status && $month){
			$luaran = Pengeluaran::orderBy('created_at', 'asc')
				->leftJoin('sekolahs', 'sekolahs.id', '=', 'pengeluarans.id_sekolah')
				->select(
					'pengeluarans.*',
					'sekolahs.nama_sekolah'
				)
				->where('status_tuntas', $status)
				->whereMonth('pengeluarans.tgl_keluar', '=' , $month)
				->paginate(25);
		}else if($status && $year){
			$luaran = Pengeluaran::orderBy('created_at', 'asc')
			->leftJoin('sekolahs', 'sekolahs.id', '=', 'pengeluarans.id_sekolah')
			->select(
				'pengeluarans.*',
				'sekolahs.nama_sekolah'
			)
			->where('status_tuntas', $status)
			->whereYear('pengeluarans.tgl_keluar', $year)
			->paginate(25);
		}else if($status){
			$luaran = Pengeluaran::orderBy('created_at', 'asc')
			->leftJoin('sekolahs', 'sekolahs.id', '=', 'pengeluarans.id_sekolah')
			->select(
				'pengeluarans.*',
				'sekolahs.nama_sekolah'
			)
			->where('status_tuntas', $status)
			->paginate(25);
		}else if($month){
			$luaran = Pengeluaran::orderBy('created_at', 'asc')
				->leftJoin('sekolahs', 'sekolahs.id', '=', 'pengeluarans.id_sekolah')
				->select(
					'pengeluarans.*',
					'sekolahs.nama_sekolah'
				)
				->whereMonth('pengeluarans.tgl_keluar', '=' , $month)
				->paginate(25);
		}else if($year){
			$luaran = Pengeluaran::orderBy('created_at', 'asc')
				->leftJoin('sekolahs', 'sekolahs.id', '=', 'pengeluarans.id_sekolah')
				->select(
					'pengeluarans.*',
					'sekolahs.nama_sekolah'
				)
				->whereYear('pengeluarans.tgl_keluar', $year)
				->paginate(25);
		}else{
			$luaran = Pengeluaran::orderBy('created_at', 'asc')
				->leftJoin('sekolahs', 'sekolahs.id', '=', 'pengeluarans.id_sekolah')
				->select(
					'pengeluarans.*',
					'sekolahs.nama_sekolah'
				)
				->paginate(25);
		}
		
		// Old Code
		// $luaran = Pengeluaran::orderBy('created_at', 'asc')
        //                	->leftJoin('sekolahs', 'sekolahs.id', '=', 'pengeluarans.id_sekolah')
        //                 ->select(
        //                           'pengeluarans.*',
        //                           'sekolahs.nama_sekolah'
        //                          )
        // ->where('status_tuntas', $filter)
        // ->whereYear('pengeluarans.tgl_keluar', $year)
        // ->whereMonth('pengeluarans.tgl_keluar', '=' , $bulan)
		// ->paginate(25);
		return view('admin.pengeluaran.list', compact('luaran'));
	}
}
