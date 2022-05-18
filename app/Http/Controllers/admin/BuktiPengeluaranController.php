<?php

namespace App\Http\Controllers\admin;

use DB;
use Auth;
use View;
use Image;
use File;
use Alert;
use Validator;
use App\BuktiPengeluaran;
use App\Pengeluaran;	
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BuktiPengeluaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $buktipengeluaran = BuktiPengeluaran::orderBy('created_at', 'desc')->paginate(25);

        foreach ($buktipengeluaran as $key => $value) {
            $pengeluaran = Pengeluaran::findOrFail($value->id_pengeluaran);
            if ($pengeluaran->status_tuntas == 1) {
                $value->status_penyaluran = 1;
            }else{
                $value->status_penyaluran = 0;
            }
            $value->kode_pengeluaran    = $pengeluaran->kode_pengeluaran;
            $value->nama_pengeluaran    = $pengeluaran->nama_pengeluaran;
            $value->tgl_keluar          = $pengeluaran->tgl_keluar;
            $value->jumlah_pengeluarans = $pengeluaran->jumlah + $pengeluaran->giro;
            $nominal_tunai = str_replace([' ', '.', ','], '', $pengeluaran->jumlah);
            $nominal_giro = str_replace([' ', '.', ','], '', $pengeluaran->giro);
            $nominal_penyaluran = str_replace([' ', '.', ','], '', $value->jumlah_pengeluaran);

        //     if ($nominal_penerimaan + $nominal_giro == $nominal_penyaluran) {
        //         $value->status_penyaluran = 1;
        //     }elseif ($nominal_penerimaan + $nominal_giro < $nominal_penyaluran) {
        //         $value->status_penyaluran = 2;
        //     }else{
        //         $value->status_penyaluran = 0;
        //     }
        }

        return view('admin.bukti_pengeluaran.list',array('bukti'=>$buktipengeluaran, 'user' => $user));
    }   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $kode_pengeluarans    = Pengeluaran::whereNotIn('status_tuntas', ['1'])->pluck('kode_pengeluaran', 'id');
        $kode_pengeluarans->prepend('Pilih Jenis Pengeluaran', '');

        return View::make('admin.bukti_pengeluaran.create', array('user' => $user, 'kode_pengeluarans' => $kode_pengeluarans));
    }

    public function store(Request $request)
    {
		$rules = [
			'id_pengeluaran'       	=>'|required|',
			'jumlah_pengeluaran'    =>'|required|',
			'file'               	=>'|required|file',
		];

		$messages = [
			'required'    => 'Input :attribute harus diisi!',
		];

		Validator::make($request->all(), $rules, $messages)->validate();
        // store
        $buktipengeluaran= new BuktiPengeluaran;
		$buktipengeluaran->user_id      			= Auth::user()->id;
    	$buktipengeluaran->id_pengeluaran  			= Input::get('id_pengeluaran');
    	$buktipengeluaran->jumlah_pengeluaran      	= Input::get('jumlah_pengeluaran');
    	$buktipengeluaran->file                     = Input::get('file');
        $files = Input::file('file');
	    if(isset($files)){
			$fileName = str_random(10).'.'.$files->getClientOriginalExtension();
			$destinationPath = public_path('/files/file');
				if(!File::exists($destinationPath)){
				if(File::makeDirectory($destinationPath,0777,true)){
				    throw new \Exception("Unable to upload to invoices directory make sure it is read / writable.");  
				}
	      }
	      $files->move($destinationPath,$fileName);
	      $buktipengeluaran->file   = $fileName;
	    }
        $buktipengeluaran->save();

        /* 
            update to penerimaan
        */

        $jumlah_tuntas = 0;
        $bukti_pengeluaran = BuktiPengeluaran::where('id_pengeluaran', Input::get('id_pengeluaran'))->get();
        
        foreach ($bukti_pengeluaran as $key => $value) {
            $jumlah_tuntas += $value->jumlah_pengeluaran;
        }

        $pengeluaran = Pengeluaran::where('id', Input::get('id_pengeluaran'))->firstOrFail();

        if ($jumlah_tuntas >= ( $pengeluaran->jumlah + $pengeluaran->giro)) {
            $pengeluaran->status_tuntas = 1; 
            $pengeluaran->save();
        }

        // redirect
        return Redirect::action('admin\BuktiPengeluaranController@index')->with('flash-store','Data berhasil ditambahkan.');
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
        $bukti = BuktiPengeluaran::where('id', $id)->firstOrFail();   
        return view('admin.bukti_pengeluaran.show', compact('bukti'),array('user' => $user));
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
        $kode_pengeluarans    = Pengeluaran::pluck('kode_pengeluaran', 'id');
    	$kode_pengeluarans->prepend('Pilih Jenis Pengeluaran', '');
        $bukti = BuktiPengeluaran::where('id', $id)->firstOrFail();

        return view('admin.bukti_tuntas.edit', compact('bukti', 'kode_pengeluarans'),array('user' => $user , 'kode_pengeluarans' => $kode_pengeluarans));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
    	$rules = [
			'id_pengeluaran'            	=>'|required|',
			'jumlah_pengeluaran'            =>'|required|',
			'file'               	        =>'|required|file',
		];

		$messages = [
			'required'    => 'Input :attribute harus diisi!',
		];

		Validator::make($request->all(), $rules, $messages)->validate();

        $bukti = BuktiPengeluaran::findOrFail($id); 
		$bukti->user_id      			    = Auth::user()->id;
    	$bukti->id_pengeluaran      		= Input::get('id_pengeluaran');
    	$bukti->jumlah_pengeluaran      	= Input::get('jumlah_pengeluaran');
        $files = Input::file('file');
	    if(isset($files)){
			$fileName = str_random(10).'.'.$files->getClientOriginalExtension();
			$destinationPath = public_path('/files/pengeluaran');
				if(!File::exists($destinationPath)){
				if(File::makeDirectory($destinationPath,0777,true)){
				    throw new \Exception("Unable to upload to invoices directory make sure it is read / writable.");  
				}
	      }
	      $files->move($destinationPath,$fileName);
	      $bukti->file   = $fileName;
	    }
        $bukti->save();

        return Redirect::action('admin\BuktiPengeluaranController@index', compact('bukti'))->with('flash-update','Data berhasil diubah.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $buktipengeluaran = BuktiPengeluaran::where('id', $id)->firstOrFail();
        $buktipengeluaran->delete();
        return Redirect::action('admin\BuktiPengeluaranController@index')->with('flash-destroy','Data berhasil dihapus.');
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
        $bukti = BuktiPengeluaran::leftjoin('pengeluarans', 'pengeluarans.id', '=', 'bukti_pengeluarans.id_pengeluaran')
        ->orderBy('bukti_pengeluarans.created_at', 'desc')
        ->select(
            'pengeluarans.*',
            'bukti_pengeluarans.*')
        ->where('id_pengeluaran', 'LIKE','%'.$search.'%')
        ->orwhere('kode_pengeluaran','LIKE','%'.$search.'%')
        ->orwhere('nama_pengeluaran','LIKE','%'.$search.'%')
        ->paginate(25);
        foreach ($bukti as $key => $value) {
            $pengeluaran = Pengeluaran::findOrFail($value->id_pengeluaran);
            if ($pengeluaran->status_tuntas == 1) {
                $value->status_penyaluran = 1;
            }else{
                $value->status_penyaluran = 0;
            }
            $value->kode_pengeluaran    = $pengeluaran->kode_pengeluaran;
            $value->nama_pengeluaran    = $pengeluaran->nama_pengeluaran;
            $value->jumlah_pengeluarans = $pengeluaran->jumlah + $pengeluaran->giro;
            $nominal_tunai = str_replace([' ', '.', ','], '', $pengeluaran->jumlah);
            $nominal_giro = str_replace([' ', '.', ','], '', $pengeluaran->giro);
            $nominal_penyaluran = str_replace([' ', '.', ','], '', $value->jumlah_pengeluaran);
        }
        return view('admin.bukti_pengeluaran.list', compact('bukti'),array('user' => $user));
    }

    public function filter(Request $request)
    {
        // $rules = [
        // 'filter'   =>'|required',
        // 'bulan'    =>'|required',
        // 'year'     =>'|required',
        // ];

        // $messages = [
        //     'required'      => ' Status harus diisi!',
        //     'required'      => ' Bulan harus diisi!',
        //     'required'      => ' Form Tahun harus diisi!',
        // ];
        // Validator::make($request->all(), $rules, $messages)->validate();

        $user   = Auth::user();
        $status = Input::get('filter');
		$month 	= Input::get('bulan');
		$year 	= Input::get('year');
	
		if($status && $month && $year){
            $buktipengeluaran = BuktiPengeluaran::leftjoin('pengeluarans', 'pengeluarans.id', '=', 'bukti_pengeluarans.id_pengeluaran')
            ->orderBy('bukti_pengeluarans.created_at', 'asc')
            ->select(
                'pengeluarans.*',
                'bukti_pengeluarans.*')
            ->where('status_tuntas', $status)
            ->whereYear('tgl_keluar', $year)
            ->whereMonth('tgl_keluar', '=' , $month)
            ->paginate(25);
		}else if($month && $year){
            $buktipengeluaran = BuktiPengeluaran::leftjoin('pengeluarans', 'pengeluarans.id', '=', 'bukti_pengeluarans.id_pengeluaran')
            ->orderBy('bukti_pengeluarans.created_at', 'asc')
            ->select(
                'pengeluarans.*',
                'bukti_pengeluarans.*')
            ->whereYear('tgl_keluar', $year)
            ->whereMonth('tgl_keluar', '=' , $month)
            ->paginate(25);
		}else if($status && $month){
            $buktipengeluaran = BuktiPengeluaran::leftjoin('pengeluarans', 'pengeluarans.id', '=', 'bukti_pengeluarans.id_pengeluaran')
            ->orderBy('bukti_pengeluarans.created_at', 'asc')
            ->select(
                'pengeluarans.*',
                'bukti_pengeluarans.*')
            ->where('status_tuntas', $status)
            ->whereMonth('tgl_keluar', '=' , $month)
            ->paginate(25);
		}else if($status && $year){
            $buktipengeluaran = BuktiPengeluaran::leftjoin('pengeluarans', 'pengeluarans.id', '=', 'bukti_pengeluarans.id_pengeluaran')
            ->orderBy('bukti_pengeluarans.created_at', 'asc')
            ->select(
                'pengeluarans.*',
                'bukti_pengeluarans.*')
            ->where('status_tuntas', $status)
            ->whereYear('tgl_keluar', $year)
            ->paginate(25);
		}else if($status){
            $buktipengeluaran = BuktiPengeluaran::leftjoin('pengeluarans', 'pengeluarans.id', '=', 'bukti_pengeluarans.id_pengeluaran')
            ->orderBy('bukti_pengeluarans.created_at', 'asc')
            ->select(
                'pengeluarans.*',
                'bukti_pengeluarans.*')
            ->where('status_tuntas', $status)
            ->paginate(25);
		}else if($month){
            $buktipengeluaran = BuktiPengeluaran::leftjoin('pengeluarans', 'pengeluarans.id', '=', 'bukti_pengeluarans.id_pengeluaran')
            ->orderBy('bukti_pengeluarans.created_at', 'asc')
            ->select(
                'pengeluarans.*',
                'bukti_pengeluarans.*')
            ->whereMonth('tgl_keluar', '=' , $month)
            ->paginate(25);
		}else if($year){
            $buktipengeluaran = BuktiPengeluaran::leftjoin('pengeluarans', 'pengeluarans.id', '=', 'bukti_pengeluarans.id_pengeluaran')
            ->orderBy('bukti_pengeluarans.created_at', 'asc')
            ->select(
                'pengeluarans.*',
                'bukti_pengeluarans.*')
            ->whereYear('tgl_keluar', $year)
            ->paginate(25);
		}else{
            $buktipengeluaran = BuktiPengeluaran::leftjoin('pengeluarans', 'pengeluarans.id', '=', 'bukti_pengeluarans.id_pengeluaran')
            ->orderBy('bukti_pengeluarans.created_at', 'asc')
            ->select(
                'pengeluarans.*',
                'bukti_pengeluarans.*')
            ->paginate(25);
        }
        
        // Old Code
        // $buktipengeluaran = BuktiPengeluaran::leftjoin('pengeluarans', 'pengeluarans.id', '=', 'bukti_pengeluarans.id_pengeluaran')
        // ->orderBy('bukti_pengeluarans.created_at', 'asc')
        // ->select(
        //     'pengeluarans.*',
        //     'bukti_pengeluarans.*')
        // ->where('status_tuntas', $filter)
        // ->whereYear('tgl_terima', $year)
        // ->whereMonth('tgl_terima', '=' , $bulan)
        // ->paginate(25);

        foreach ($buktipengeluaran as $key => $value) {
            $pengeluaran = Pengeluaran::findOrFail($value->id_pengeluaran);
            if ($pengeluaran->status_tuntas == 1) {
                $value->status_penyaluran = 1;
            }else{
                $value->status_penyaluran = 0;
            }
            $value->kode_pengeluaran    = $pengeluaran->kode_pengeluaran;
            $value->nama_pengeluaran    = $pengeluaran->nama_pengeluaran;
            $value->jumlah_pengeluarans = $pengeluaran->jumlah + $pengeluaran->giro;
            $nominal_tunai = str_replace([' ', '.', ','], '', $pengeluaran->jumlah);
            $nominal_giro = str_replace([' ', '.', ','], '', $pengeluaran->giro);
            $nominal_penyaluran = str_replace([' ', '.', ','], '', $value->jumlah_pengeluaran);
        }
        return view('admin.bukti_pengeluaran.list',array('bukti'=>$buktipengeluaran, 'user' => $user));
    }

    public function get_data_pengeluaran(Request $request){ 
        $jumlah_tuntas = 0;
        $jumlah = 0;
        $pengeluaran = Pengeluaran::where('id', Input::get('id_pengeluaran'))->firstOrFail();
        $buktipengeluaran = BuktiPengeluaran::where('id_pengeluaran', Input::get('id_pengeluaran'))->get();
        
        foreach ($buktipengeluaran as $key => $value) {
            $jumlah_tuntas += $value->jumlah_pengeluaran;
        }
        
        if ($buktipengeluaran) {
            $jumlah = ($pengeluaran->jumlah + $pengeluaran->giro) - $jumlah_tuntas;
        }else{
            $jumlah = $pengeluaran->jumlah + $pengeluaran->giro;
        }

        $data = array('jumlah' => $jumlah);
    return response()->json($data);
    }
}
