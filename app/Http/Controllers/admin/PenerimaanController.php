<?php

namespace App\Http\Controllers\admin;

use DB;
use Auth;
use View;
use Image;
use File;
use Alert;
use Validator;
use PDF;
use App\Penerimaan;	
use App\JenisPenerimaan;	
use App\Sekolah;
use App\BuktiTuntas;
use DataTables;	
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class PenerimaanController extends Controller
{
	// public function getdata(){
	// 	$penerimaans = Penerimaan::all();
	// 	return Datatables::of($penerimaans)
	// 	->addColumn('action', function ($penerimaans) {
	// 		return 	'<a title="Pertanggung Jawaban" 	class="btn btn-primary btn-xs"	style="margin:2px;" <a href="/bukti/create/"><i class="fa fa-edit fa-md"></i></a>'.
	// 				'<a title="Detail" 	class="btn btn-primary btn-xs"	style="margin:2px;" <a href="/penerimaans/show/'.$penerimaans->id.'"><i class="fa fa-eye fa-md"></i></a>'.
	// 				'<a title="invoice" class="btn btn-warning btn-xs	style="margin:2px;" <a href="/penerimaans/invoice/'.$penerimaans->id.'"><i class="fa fa-print" style="color: white"></i></a>';
	// 	})
	// 	->editColumn('tgl_terima', function ($penerimaans) {
	// 		return $penerimaans->tgl_terima->format('d-m-Y');
	// 	})
		
    //     ->rawColumns(['action'])
	// 	->addIndexColumn()
	// 	->make(true);
    // }

	public function index()
	{   
		$user = Auth::user();
	   	if(Auth::user()->hasAnyRole('List Penerimaan')){
			$penerimaans = Penerimaan::orderBy('created_at', 'desc')
	                       	->leftJoin('sekolahs', 'sekolahs.id', '=', 'penerimaans.id_sekolah')
	                        ->select(
	                                  'penerimaans.*',
	                                  'sekolahs.nama_sekolah'
	                                 )
							->paginate(25);
			return view('admin.penerimaan.list',array('penerimaans'=>$penerimaans, 'user' => $user));
	    }else{
	      	return view('admin.404');
	    }
	}

	public function invoice($id)
	{   
		$user = Auth::user();
		$penerimaans = Penerimaan::orderBy('created_at', 'asc')->findOrFail($id);
		return view('admin.penerimaan.invoice',array('penerimaans'=>$penerimaans, 'user' => $user));
	}

	public function create()
	{
	   	if(Auth::user()->hasAnyRole('Create Penerimaan')){
			$sekolah = Sekolah::pluck('nama_sekolah', 'id');
	    	$sekolah->prepend('Pilih Sekolah', '');
			return View::make('admin.penerimaan.create', compact('sekolah'));
		}else{
	      	return view('admin.404');
	    }
	}

	public function store(Request $request)
	{
		$rules = [
			'id_sekolah'        	=>'|required',
			'kode_penerimaan'      	=>'|required',
			'tgl_terima'      		=>'|required',
			'asal_penerimaan'      	=>'|required',
			'nama_penerimaan'      	=>'|required',
			'jumlah'      			=>'|required',
			'giro'      			=>'|required',
		];

		$messages = [
			'required'  => 'Input :attribute harus diisi!',
		];

		Validator::make($request->all(), $rules, $messages)->validate();

		$penerimaans = new Penerimaan;
		$penerimaans->user_id         		= Auth::user()->id;
		$penerimaans->id_sekolah       		= Input::get('id_sekolah');
		$penerimaans->kode_penerimaan       = Input::get('kode_penerimaan');
		$penerimaans->tgl_terima        	= Input::get('tgl_terima');
		$penerimaans->asal_penerimaan       = Input::get('asal_penerimaan');
		$penerimaans->nama_penerimaan       = Input::get('nama_penerimaan');
		$penerimaans->jumlah        		= Input::get('jumlah');
		$penerimaans->giro        			= Input::get('giro');
		$penerimaans->ket        			= Input::get('ket');
		$penerimaans->terbilang    			= Input::get('terbilang');
		$penerimaans->status_tuntas        	= 2;
		$penerimaans->save();

	return Redirect::action('admin\PenerimaanController@index')->with('flash-store','Data berhasil ditambahkan.');
	}

	public function show($id)
	{
	   	if(Auth::user()->hasAnyRole('Details Penerimaan')){
			$penerimaans = Penerimaan::findOrFail($id);

			$penerimaan = Penerimaan::orderBy('penerimaans.created_at', 'desc')
	       	->leftJoin('bukti_tuntas', 'bukti_tuntas.id_kode', '=', 'penerimaans.id')
	        ->whereIdKode($id)
	        ->select(
	                  'penerimaans.*',
	                  'bukti_tuntas.*'
	                 )
			->get();

			$jumlah_tuntas = 0;
			$total = 0;
			$sisa = 0;
			$value = 0;
	        foreach ($penerimaan as $key => $value) {
	            $jumlah_tuntas += $value->jumlah_penyaluran;
	            $total = $value->jumlah + $value->giro;
	            $value->sisa = $total - $jumlah_tuntas;
	        }

	        // echo "<pre>";
	        // print_r($value);
	        // echo "</pre>";
	        // exit();

			return view('admin.penerimaan.show',compact('penerimaan', 'value'), [ 'penerimaans' => $penerimaans]);
		}else{
	      	return view('admin.404');
	    }
	}

	public function edit($id)
	{
	   	if(Auth::user()->hasAnyRole('Edit Penerimaan')){
			$jenis_penerimaans    = JenisPenerimaan::pluck('nama', 'id');
	    	$jenis_penerimaans->prepend('Pilih Jenis Penerimaan', '');
	    	$sekolahs    = Sekolah::pluck('nama_sekolah', 'id');
	    	$sekolahs->prepend('Pilih Sekolah', '');
	    	$sekolah = Sekolah::pluck('nama_sekolah', 'id');
	    	$sekolah->prepend('Pilih Sekolah', '');
			$penerimaans = Penerimaan::findOrFail($id);
			// echo "<pre>";
			// print_r($penerimaans);
			// echo "</pre>";
			// exit();

			return view('admin.penerimaan.edit', compact('sekolah'), array('penerimaans' => $penerimaans, 'jenis_penerimaans' => $jenis_penerimaans, 'sekolahs' => $sekolahs));
		}else{
	      	return view('admin.404');
	    }
	}

	public function update(Request $request, $id)
	{
		$rules = [
			'id_sekolah'      		=>'|required',
			'kode_penerimaan'      	=>'|required',
			'tgl_terima'      		=>'|required',
			'asal_penerimaan'      	=>'|required',
			'nama_penerimaan'      	=>'|required',
			'jumlah'      			=>'|required',
		];

		$messages = [
			'required'  => 'Input :attribute harus diisi!',
		];

		Validator::make($request->all(), $rules, $messages)->validate();

		$penerimaans = Penerimaan::findOrFail($id);
		$penerimaans->user_id         		= Auth::user()->id;
		$penerimaans->id_sekolah       		= Input::get('id_sekolah');
		$penerimaans->kode_penerimaan       = Input::get('kode_penerimaan');
		$penerimaans->tgl_terima        	= Input::get('tgl_terima');
		$penerimaans->asal_penerimaan       = Input::get('asal_penerimaan');
		$penerimaans->nama_penerimaan       = Input::get('nama_penerimaan');
		$penerimaans->jumlah        		= Input::get('jumlah');
		$penerimaans->ket        			= Input::get('ket');
		$penerimaans->terbilang    			= Input::get('terbilang');
		$penerimaans->save();

		return Redirect::action('admin\PenerimaanController@index')->with('flash-update','Data berhasil diubah.');
	}

	public function destroy($id)
	{
	   	if(Auth::user()->hasAnyRole('Delete Penerimaan')){
			$penerimaan_role = Penerimaan::findOrFail($id);
			$penerimaans = BuktiTuntas::orderBy('bukti_tuntas.created_at','asc')
	      	->leftJoin('penerimaans', 'penerimaans.id', '=', 'bukti_tuntas.id_kode')
	      	->whereIdKode($id)
	      	->get();

	      	if(!$penerimaans->isEmpty()){
	          	return Redirect::action('admin\PenerimaanController@index')->with('flash-error','Penerimaan tidak bisa dihapus karena sudah diacu di Data Pertanggung Jawaban, Silakan menghapus data yang mengacu Penerimaan terlebih dahulu.');  
	     	 }else{
	          	$penerimaan_role->delete();
	          	return Redirect::action('admin\PenerimaanController@index')->with('flash-success','Penerimaan sudah berhasil dihapus.');
	     	 }
	    	}else{
	      		return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
	    	}
		// 	$penerimaans->delete();

		// 	return Redirect::action('admin\PenerimaanController@index')->with('flash-destroy','The user has been deleted.');
		// }else{
	 //      	return view('admin.404');
	 //    }
		
	}

	public function search(Request $request)
	{
	   	if(Auth::user()->hasAnyRole('Search Penerimaan')){
			$search = $request->get('search');
			$penerimaans = Penerimaan::orderBy('created_at', 'desc')
	                       	->leftJoin('sekolahs', 'sekolahs.id', '=', 'penerimaans.id_sekolah')
	                        ->select(
	                                  'penerimaans.*',
	                                  'sekolahs.nama_sekolah'
	                                 )
	        ->where('kode_penerimaan','LIKE','%'.$search.'%')
	        ->orwhere('asal_penerimaan','LIKE','%'.$search.'%')
	        ->orwhere('nama_penerimaan','LIKE','%'.$search.'%')
	        ->orwhere('nama_sekolah','LIKE','%'.$search.'%')
			->paginate(25);

			return view('admin.penerimaan.list', compact('penerimaans'));
		}else{
	      	return view('admin.404');
	    }
	}

	public function filter(Request $request)
	{
		$status = Input::get('filter');
		$month 	= Input::get('bulan');
		$year 	= Input::get('year');
	
		if($status && $month && $year){
			$penerimaans = Penerimaan::orderBy('created_at', 'asc')
			->leftJoin('sekolahs', 'sekolahs.id', '=', 'penerimaans.id_sekolah')
			->select(
							'penerimaans.*',
							'sekolahs.nama_sekolah'
							)
			->where('status_tuntas', $status)
			->whereYear('penerimaans.tgl_terima', $year)
			->whereMonth('penerimaans.tgl_terima', '=' , $month)
			->paginate(25);
		}else if($month && $year){
			$penerimaans = Penerimaan::orderBy('created_at', 'asc')
			->leftJoin('sekolahs', 'sekolahs.id', '=', 'penerimaans.id_sekolah')
			->select(
							'penerimaans.*',
							'sekolahs.nama_sekolah'
							)
			->whereYear('penerimaans.tgl_terima', $year)
			->whereMonth('penerimaans.tgl_terima', '=' , $month)
			->paginate(25);
		}else if($status && $month){
			$penerimaans = Penerimaan::orderBy('created_at', 'asc')
			->leftJoin('sekolahs', 'sekolahs.id', '=', 'penerimaans.id_sekolah')
			->select(
							'penerimaans.*',
							'sekolahs.nama_sekolah'
							)
			->where('status_tuntas', $status)
			->whereMonth('penerimaans.tgl_terima', '=' , $month)
			->paginate(25);
		}else if($status && $year){
			$penerimaans = Penerimaan::orderBy('created_at', 'asc')
			->leftJoin('sekolahs', 'sekolahs.id', '=', 'penerimaans.id_sekolah')
			->select(
							'penerimaans.*',
							'sekolahs.nama_sekolah'
							)
			->where('status_tuntas', $status)
			->whereYear('penerimaans.tgl_terima', $year)
			->paginate(25);
		}else if($status){
			$penerimaans = Penerimaan::orderBy('created_at', 'asc')
			->leftJoin('sekolahs', 'sekolahs.id', '=', 'penerimaans.id_sekolah')
			->select(
							'penerimaans.*',
							'sekolahs.nama_sekolah'
							)
			->where('status_tuntas', $status)
			->paginate(25);
		}else if($month){
			$penerimaans = Penerimaan::orderBy('created_at', 'asc')
			->leftJoin('sekolahs', 'sekolahs.id', '=', 'penerimaans.id_sekolah')
			->select(
							'penerimaans.*',
							'sekolahs.nama_sekolah'
							)
			->whereMonth('penerimaans.tgl_terima', '=' , $month)
			->paginate(25);
		}else if($year){
			$penerimaans = Penerimaan::orderBy('created_at', 'asc')
			->leftJoin('sekolahs', 'sekolahs.id', '=', 'penerimaans.id_sekolah')
			->select(
							'penerimaans.*',
							'sekolahs.nama_sekolah'
							)
			->whereYear('penerimaans.tgl_terima', $year)
			->paginate(25);
		}else{
			$penerimaans = Penerimaan::orderBy('created_at', 'asc')
			->leftJoin('sekolahs', 'sekolahs.id', '=', 'penerimaans.id_sekolah')
			->select(
							'penerimaans.*',
							'sekolahs.nama_sekolah'
							)
			->paginate(25);
		}

		// Old Code
		// $penerimaans = Penerimaan::orderBy('created_at', 'asc')
		// ->leftJoin('sekolahs', 'sekolahs.id', '=', 'penerimaans.id_sekolah')
		// ->select(
		// 				'penerimaans.*',
		// 				'sekolahs.nama_sekolah'
		// 				)
		// ->where('status_tuntas', $filter)
		// ->whereYear('penerimaans.tgl_terima', $year)
		// ->whereMonth('penerimaans.tgl_terima', '=' , $bulan)
		// ->paginate(25);
		return view('admin.penerimaan.list', compact('penerimaans'));
	}

	public function cetak_pdf()
	{
		$penerimaans = Penerimaan::all();

		$pdf = PDF::loadview('admin/penerimaan/invoice',['penerimaans'=>$penerimaans]);
		return $pdf->stream('invoice.pdf');
	}

	// Belum work

	public function get_data_penerimaan(Request $request){ 

		if($_POST["query"] != '')
		{
			$search_array = explode(",", $_POST["query"]);
			$search_text = "'" . implode("', '", $search_array) . "'";
			$query = "
			SELECT * FROM tbl_customer 
			WHERE Country IN (".$search_text.") 
			ORDER BY CustomerID DESC
			";
		}
		else
		{
			$query = "SELECT * FROM tbl_customer ORDER BY CustomerID DESC";
		}

		$statement = $connect->prepare($query);

		$statement->execute();

		$result = $statement->fetchAll();

		$total_row = $statement->rowCount();

		$output = '';

		if($total_row > 0)
		{
			foreach($result as $row)
			{
				$output .= '
				<tr>
					<td>'.$row["CustomerName"].'</td>
					<td>'.$row["Address"].'</td>
					<td>'.$row["City"].'</td>
					<td>'.$row["PostalCode"].'</td>
					<td>'.$row["Country"].'</td>
				</tr>
				';
			}
		}
		else
		{
			$output .= '
			<tr>
				<td colspan="5" align="center">No Data Found</td>
			</tr>
			';
		}

		echo $output;
        $data = array('jumlah' => $jumlah);
    	return response()->json($data);
    }
}
