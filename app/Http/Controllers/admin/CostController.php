<?php

namespace App\Http\Controllers\admin;

use Auth;
use View;
use Image;
use App\Cost;
use App\Sekolah;
use App\Year;
use App\Program;
use App\PaymentType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class CostController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $biaya = Cost::leftJoin('sekolahs', 'sekolahs.id', '=', 'costs.sekolah_id')
        ->leftJoin('years', 'years.id', '=', 'costs.thn_id')
        ->leftJoin('programs', 'programs.id', '=', 'costs.program_id')
        ->leftJoin('payment_types', 'payment_types.id', '=', 'costs.payment_id')
        ->orderBy('created_at', 'desc')
        ->select(
                    'costs.*',
                    'sekolahs.nama_sekolah',
                    'years.tahun',
                    'programs.nama as nm_prog',
                    'payment_types.name as nm_payment'
                )
        ->paginate(25);

        return view('admin.bayar.list',array('biaya'=>$biaya, 'user' => $user));
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
        $payments=PaymentType::pluck('name', 'id');
        
        return View::make('admin.bayar.create',compact('scholls', 'years', 'progs', 'payments'), array('user' => $user));
    }

    public function store(Request $request)
    {
        // store
        $biaya= new Cost;
        $biaya->sekolah_id      = Input::get('sekolah_id');
        $biaya->thn_id          = Input::get('thn_id');
        $biaya->program_id      = Input::get('program_id');
        $biaya->payment_id      = Input::get('payment_id');
        $biaya->name            = Input::get('name');
        $biaya->save();
        // redirect
        return Redirect::action('admin\CostController@index')->with('flash-store','Data berhasil ditambahkan.');
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
        $biaya = Cost::where('id', $id)->firstOrFail();   
        return view('admin.bayar.show', compact('biaya'),array('user' => $user));
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
        $year=Year::pluck('tahun', 'id');
        $prog=Program::pluck('nama', 'id');
        $payment=PaymentType::pluck('name', 'id');
        $biaya = Cost::where('id', $id)->firstOrFail();   
        return view('admin.bayar.edit', compact('biaya', 'payment', 'scholl', 'year', 'prog'), array('user' => $user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $biaya = Cost::findOrFail($id); 
        $biaya->sekolah_id      = Input::get('sekolah_id');
        $biaya->thn_id          = Input::get('thn_id');
        $biaya->program_id      = Input::get('program_id');
        $biaya->payment_id      = Input::get('payment_id');
        $biaya->name            = Input::get('name');
        $biaya->save();

        return Redirect::action('admin\CostController@index', compact('biaya'))->with('flash-update','Data berhasil diubah.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $biaya = Cost::where('id', $id)->firstOrFail();
        $biaya->delete();
        return Redirect::action('admin\CostController@index')->with('flash-destroy','Data berhasil dihapus.');
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
        $biaya = Cost::where('name','LIKE','%'.$search.'%')->paginate(25);
        return view('admin.bayar.list', compact('biaya'),array('user' => $user));
    }

    // public function ajax_getsekolah_tahun(Request $request)
    // { 
    //     $jenis=Cost::pluck('name', 'id');
    //     $jenis->prepend('Pilih Biaya', '');
    //     $jenis = Cost::where('payment_id','=',$request->get('payment_id'))->get();
    //     return response()->json($jenis);
    // }
}