<?php

namespace App\Http\Controllers\admin;

use DB;
use Auth;
use View;
use Image;
use File;
use Alert;
use Validator;
use App\BuktiTuntas;
use App\Penerimaan;	
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class BuktiTuntasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bukti = BuktiTuntas::leftjoin('penerimaans', 'penerimaans.id', '=', 'bukti_tuntas.id_kode')
        ->orderBy('bukti_tuntas.created_at', 'desc')
        ->select(
            'penerimaans.id',
            'penerimaans.user_id',
            'penerimaans.kode_penerimaan',
            'penerimaans.tgl_terima',
            'penerimaans.asal_penerimaan',
            'penerimaans.nama_penerimaan',
            'penerimaans.jumlah',
            'penerimaans.giro',
            'penerimaans.ket',
            'penerimaans.status_tuntas',
            'bukti_tuntas.user_id',
            'bukti_tuntas.jumlah_penyaluran',
            'bukti_tuntas.file',
            'bukti_tuntas.id_kode')
        ->paginate(25);

        foreach ($bukti as $key => $value) {
            $penerimaan = Penerimaan::findOrFail($value->id_kode);
            if ($penerimaan->status_tuntas == 1) {
                $value->status_penyaluran = 1;
            }else{
                $value->status_penyaluran = 0;
            }
            $value->kode_penerimaan = $penerimaan->kode_penerimaan;
            $value->nama_penerimaan = $penerimaan->nama_penerimaan;
            $value->jumlah_penerimaan = $penerimaan->jumlah + $penerimaan->giro;
            $nominal_penerimaan = str_replace([' ', '.', ','], '', $penerimaan->jumlah);
            $nominal_giro = str_replace([' ', '.', ','], '', $penerimaan->giro);
            $nominal_penyaluran = str_replace([' ', '.', ','], '', $value->jumlah_penyaluran);

        //     if ($nominal_penerimaan + $nominal_giro == $nominal_penyaluran) {
        //         $value->status_penyaluran = 1;
        //     }elseif ($nominal_penerimaan + $nominal_giro < $nominal_penyaluran) {
        //         $value->status_penyaluran = 2;
        //     }else{
        //         $value->status_penyaluran = 0;
        //     }
        }

        return view('admin.bukti_tuntas.list',array('bukti'=>$bukti, 'user' => $user));
    }   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $kode_penerima    = Penerimaan::whereNotIn('status_tuntas', ['1'])->pluck('kode_penerimaan', 'id');
        $kode_penerima->prepend('Pilih Jenis Penerimaan', '');

        return View::make('admin.bukti_tuntas.create', array('user' => $user, 'kode_penerima' => $kode_penerima));
    }

    public function store(Request $request)
    {
		$rules = [
			'id_kode'            	=>'|required|',
			'jumlah_penyaluran'     =>'|required|',
			'file'               	=>'|required|file',
		];

		$messages = [
			'required'    => 'Input :attribute harus diisi!',
		];

		Validator::make($request->all(), $rules, $messages)->validate();
        // store
        $bukti= new BuktiTuntas;
		$bukti->user_id      			= Auth::user()->id;
    	$bukti->id_kode      			= Input::get('id_kode');
    	$bukti->jumlah_penyaluran      	= Input::get('jumlah_penyaluran');
    	$bukti->file                    = Input::get('file');
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
	      $bukti->file   = $fileName;
	    }
        $bukti->save();

        /* 
            update to penerimaan
        */

        $jumlah_tuntas = 0;
        $bukti_tuntas = BuktiTuntas::where('id_kode', Input::get('id_kode'))->get();
        
        foreach ($bukti_tuntas as $key => $value) {
            $jumlah_tuntas += $value->jumlah_penyaluran;
        }

        $penerimaan = Penerimaan::where('id', Input::get('id_kode'))->firstOrFail();

        if ($jumlah_tuntas >= ( $penerimaan->jumlah + $penerimaan->giro)) {
            $penerimaan->status_tuntas = 1; 
            $penerimaan->save();
        }

        // echo "<pre>";
        // print_r($penerimaan);
        // echo "</pre>";
        // exit();

        // redirect
        return Redirect::action('admin\BuktiTuntasController@index')->with('flash-store','Data berhasil ditambahkan.');
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
        $bukti = BuktiTuntas::where('id', $id)->firstOrFail();   
        return view('admin.bukti_tuntas.show', compact('bukti'),array('user' => $user));
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
        $kode_penerimas    = Penerimaan::pluck('kode_penerimaan', 'id');
    	$kode_penerimas->prepend('Pilih Jenis Penerimaan', '');
        $bukti = BuktiTuntas::where('id', $id)->firstOrFail();

        return view('admin.bukti_tuntas.edit', compact('bukti', 'kode_penerimas'),array('user' => $user , 'kode_penerimas' => $kode_penerimas));
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
			'id_kode'            	=>'|required|',
			'jumlah_penyaluran'     =>'|required|',
			'file'               	=>'|required|file',
		];

		$messages = [
			'required'    => 'Input :attribute harus diisi!',
		];

		Validator::make($request->all(), $rules, $messages)->validate();

        $bukti = BuktiTuntas::findOrFail($id); 
		$bukti->user_id      			= Auth::user()->id;
    	$bukti->id_kode      			= Input::get('id_kode');
    	$bukti->jumlah_penyaluran      	= Input::get('jumlah_penyaluran');
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
	      $bukti->file   = $fileName;
	    }
        $bukti->save();

        return Redirect::action('admin\BuktiTuntasController@index', compact('bukti'))->with('flash-update','Data berhasil diubah.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $bukti = BuktiTuntas::where('id', $id)->firstOrFail();
        $bukti->delete();
        return Redirect::action('admin\BuktiTuntasController@index')->with('flash-destroy','Data berhasil dihapus.');
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
        $bukti = BuktiTuntas::leftjoin('penerimaans', 'penerimaans.id', '=', 'bukti_tuntas.id_kode')
        ->orderBy('bukti_tuntas.created_at', 'desc')
        ->where('kode_penerimaan','LIKE','%'.$search.'%')
        ->orwhere('nama_penerimaan','LIKE','%'.$search.'%')
        ->orwhere('asal_penerimaan','LIKE','%'.$search.'%')
        ->select(
            'penerimaans.id',
            'penerimaans.user_id',
            'penerimaans.kode_penerimaan',
            'penerimaans.tgl_terima',
            'penerimaans.asal_penerimaan',
            'penerimaans.nama_penerimaan',
            'penerimaans.jumlah',
            'penerimaans.giro',
            'penerimaans.ket',
            'penerimaans.status_tuntas',
            'bukti_tuntas.user_id',
            'bukti_tuntas.jumlah_penyaluran',
            'bukti_tuntas.file',
            'bukti_tuntas.id_kode')
        ->paginate(25);

        foreach ($bukti as $key => $value) {
            $penerimaan = Penerimaan::findOrFail($value->id_kode);
            if ($penerimaan->status_tuntas == 1) {
                $value->status_penyaluran = 1;
            }else{
                $value->status_penyaluran = 0;
            }
            $value->kode_penerimaan = $penerimaan->kode_penerimaan;
            $value->nama_penerimaan = $penerimaan->nama_penerimaan;
            $value->jumlah_penerimaan = $penerimaan->jumlah + $penerimaan->giro;
            $nominal_penerimaan = str_replace([' ', '.', ','], '', $penerimaan->jumlah);
            $nominal_giro = str_replace([' ', '.', ','], '', $penerimaan->giro);
            $nominal_penyaluran = str_replace([' ', '.', ','], '', $value->jumlah_penyaluran);
        }
        return view('admin.bukti_tuntas.list', compact('bukti'),array('user' => $user));
    }

    public function filter(Request $request)
    {
        // $rules = [
        //     'filter'   =>'|required',
        //     'bulan'    =>'|required',
        //     'year'     =>'|required',
        // ];
    
        //     $messages = [
        //         'required'      => ' Status harus diisi!',
        //         'required'      => ' Bulan harus diisi!',
        //         'required'      => ' Form Tahun harus diisi!',
        // ];

        // Validator::make($request->all(), $rules, $messages)->validate();

        $user = Auth::user();
        
        // Old Code
        // $bukti = BuktiTuntas::leftjoin('penerimaans', 'penerimaans.id', '=', 'bukti_tuntas.id_kode')
        // ->orderBy('bukti_tuntas.created_at', 'asc')
        // ->select(
        //     'penerimaans.*',
        //     'bukti_tuntas.*')
        // ->where('status_tuntas', $filter)
        // ->whereYear('tgl_terima', $year)
        // ->whereMonth('tgl_terima', '=' , $bulan)
        // // ->orwhere('penerimaans.created_at', $day)
        // ->paginate(25);

        $status = Input::get('filter');
		$month 	= Input::get('bulan');
		$year 	= Input::get('year');
	
		if($status && $month && $year){
            $bukti = BuktiTuntas::leftjoin('penerimaans', 'penerimaans.id', '=', 'bukti_tuntas.id_kode')
            ->orderBy('bukti_tuntas.created_at', 'asc')
            ->select(
                'penerimaans.*',
                'bukti_tuntas.*')
            ->where('status_tuntas', $status)
            ->whereYear('tgl_terima', $year)
            ->whereMonth('tgl_terima', '=' , $month)
            ->paginate(25);
		}else if($month && $year){
            $bukti = BuktiTuntas::leftjoin('penerimaans', 'penerimaans.id', '=', 'bukti_tuntas.id_kode')
            ->orderBy('bukti_tuntas.created_at', 'asc')
            ->select(
                'penerimaans.*',
                'bukti_tuntas.*')
            ->whereYear('tgl_terima', $year)
            ->whereMonth('tgl_terima', '=' , $month)
            ->paginate(25);
		}else if($status && $month){
            $bukti = BuktiTuntas::leftjoin('penerimaans', 'penerimaans.id', '=', 'bukti_tuntas.id_kode')
            ->orderBy('bukti_tuntas.created_at', 'asc')
            ->select(
                'penerimaans.*',
                'bukti_tuntas.*')
            ->where('status_tuntas', $status)
            ->whereMonth('tgl_terima', '=' , $month)
            ->paginate(25);
		}else if($status && $year){
            $bukti = BuktiTuntas::leftjoin('penerimaans', 'penerimaans.id', '=', 'bukti_tuntas.id_kode')
            ->orderBy('bukti_tuntas.created_at', 'asc')
            ->select(
                'penerimaans.*',
                'bukti_tuntas.*')
            ->where('status_tuntas', $status)
            ->whereYear('tgl_terima', $year)
            ->paginate(25);
		}else if($status){
            $bukti = BuktiTuntas::leftjoin('penerimaans', 'penerimaans.id', '=', 'bukti_tuntas.id_kode')
            ->orderBy('bukti_tuntas.created_at', 'asc')
            ->select(
                'penerimaans.*',
                'bukti_tuntas.*')
            ->where('status_tuntas', $status)
            ->paginate(25);
		}else if($month){
            $bukti = BuktiTuntas::leftjoin('penerimaans', 'penerimaans.id', '=', 'bukti_tuntas.id_kode')
            ->orderBy('bukti_tuntas.created_at', 'asc')
            ->select(
                'penerimaans.*',
                'bukti_tuntas.*')
            ->whereMonth('tgl_terima', '=' , $month)
            ->paginate(25);
		}else if($year){
            $bukti = BuktiTuntas::leftjoin('penerimaans', 'penerimaans.id', '=', 'bukti_tuntas.id_kode')
            ->orderBy('bukti_tuntas.created_at', 'asc')
            ->select(
                'penerimaans.*',
                'bukti_tuntas.*')
            ->whereYear('tgl_terima', $year)
            ->paginate(25);
		}else{
            $bukti = BuktiTuntas::leftjoin('penerimaans', 'penerimaans.id', '=', 'bukti_tuntas.id_kode')
            ->orderBy('bukti_tuntas.created_at', 'asc')
            ->select(
                'penerimaans.*',
                'bukti_tuntas.*')
            ->paginate(25);
		}

        foreach ($bukti as $key => $value) {
            $penerimaan = Penerimaan::findOrFail($value->id_kode);
            if ($penerimaan->status_tuntas == 1) {
                $value->status_penyaluran = 1;
            }else{
                $value->status_penyaluran = 0;
            }
            $value->kode_penerimaan = $penerimaan->kode_penerimaan;
            $value->nama_penerimaan = $penerimaan->nama_penerimaan;
            $value->jumlah_penerimaan = $penerimaan->jumlah + $penerimaan->giro;
            $nominal_penerimaan = str_replace([' ', '.', ','], '', $penerimaan->jumlah);
            $nominal_giro = str_replace([' ', '.', ','], '', $penerimaan->giro);
            $nominal_penyaluran = str_replace([' ', '.', ','], '', $value->jumlah_penyaluran);
        }

        return view('admin.bukti_tuntas.list', compact('bukti'));
    }

    public function get_data_penerimaan(Request $request){ 
        $jumlah_tuntas = 0;
        $jumlah = 0;
        $penerimaan = Penerimaan::where('id', Input::get('id_kode'))->firstOrFail();
        $bukti_tuntas = BuktiTuntas::where('id_kode', Input::get('id_kode'))->get();
        
        foreach ($bukti_tuntas as $key => $value) {
            $jumlah_tuntas += $value->jumlah_penyaluran;
        }
        
        if ($bukti_tuntas) {
            $jumlah = ($penerimaan->jumlah + $penerimaan->giro) - $jumlah_tuntas;
        }else{
            $jumlah = $penerimaan->jumlah + $penerimaan->giro;
        }

        $data = array('jumlah' => $jumlah);
    return response()->json($data);
    }
}
