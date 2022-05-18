<?php

namespace App\Http\Controllers\admin;

use DB;
use Auth;
use View;
use Image;
use File;
use Alert;
use Validator;
use App\Biaya;	
use App\JenisBayar;  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class BiayaController extends Controller
{
  public function index()
  {   
    $user = Auth::user();

    $biaya = Biaya::orderBy('created_at', 'desc')
            ->leftjoin('jenis_bayars', 'jenis_bayars.id', '=', 'biayas.id_kode')
            ->select(
                      'biayas.*',
                      'jenis_bayars.kode_bayar'
                    )
            ->paginate(10);
    return view('admin.biaya.list',array('biaya'=>$biaya, 'user' => $user));
  }

  public function create()
  {
    $jenis_bayar=JenisBayar::pluck('kode_bayar', 'id');
    $jenis_bayar->prepend('Pilih Kode Bayar', '');
    $biaya = Biaya::orderBy('created_at','asc')->get();

    return View::make('admin.biaya.create', compact('biaya', 'jenis_bayar'));
  }

  public function store(Request $request)
  {
    $rules = [
      'id_kode'     =>'|required',
      'thn'         =>'|required',
      'jumlah'      =>'|required',
    ];

    $messages = [
      'required'  => 'Input :attribute harus diisi!',
    ];

    Validator::make($request->all(), $rules, $messages)->validate();
    
    $biaya = new Biaya;
    $biaya->id_kode         = Input::get('id_kode');
    $biaya->thn             = Input::get('thn');
    $biaya->jumlah          = Input::get('jumlah');
    $biaya->save();
    
    return Redirect::action('admin\BiayaController@index')->with('flash-store','Data berhasil ditambahkan.');
  }

  public function show($id)
  {
    $biaya = Biaya::findOrFail($id);
    return view('admin.biaya.show',[ 'biaya' => $biaya]);
  }

  public function edit($id)
  {
    $jenis_bayars=JenisBayar::pluck('kode_bayar', 'id');
    $jenis_bayars->prepend('Pilih Kode Bayar', '');
    $biaya = Biaya::findOrFail($id);
    return view('admin.biaya.edit', array('biaya' => $biaya, 'jenis_bayars' => $jenis_bayars));
  }

  public function update(Request $request, $id)
  {
     $rules = [
      'id_kode'     =>'|required',
      'thn'         =>'|required',
      'jumlah'      =>'|required',
    ];

    $messages = [
      'required'  => 'Input :attribute harus diisi!',
    ];

    Validator::make($request->all(), $rules, $messages)->validate();

    $biaya = Biaya::findOrFail($id);
    $biaya->id_kode         = Input::get('id_kode');
    $biaya->thn             = Input::get('thn');
    $biaya->jumlah          = Input::get('jumlah');
    $biaya->save();

    return Redirect::action('admin\BiayaController@index')->with('flash-update','Data berhasil diubah.');
  }

  public function destroy($id)
  {
    $biaya = Biaya::findOrFail($id);
    $biaya->delete();

    return Redirect::action('admin\BiayaController@index')->with('flash-destroy','Data berhasil dihapus.');
  }

  public function search(Request $request)

  {
    $search = $request->get('search');
    $biaya = Biaya::where('jumlah','LIKE','%'.$search.'%')->get();

    return view('admin.biaya.list', compact('biaya'));
  }
}
