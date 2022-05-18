<?php

namespace App\Http\Controllers\admin;

use Auth;
use View;
use Image;
use App\PaymentType;
use App\Year;
use App\Program;
use App\Sekolah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class PaymentTypeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $payment = PaymentType::orderBy('created_at', 'desc')->paginate(25);
        return view('admin.jenisbayar.list',array('payment'=>$payment, 'user' => $user));
    }   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        return View::make('admin.jenisbayar.create', array('user' => $user));
    }

    public function store(Request $request)
    {
        
        // store
        $payment= new PaymentType;
        $payment->name            = Input::get('name');
        $payment->alias           = Input::get('alias');
        $payment->save();
        // redirect
        return Redirect::action('admin\PaymentTypeController@index')->with('flash-store','Data berhasil ditambahkan.');
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
        $payment = PaymentType::where('id', $id)->firstOrFail();   
        return view('admin.jenisbayar.show', compact('payment'),array('user' => $user));
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
        $payment = PaymentType::where('id', $id)->firstOrFail();
        return view('admin.jenisbayar.edit', compact('payment'),array('user' => $user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $payment = PaymentType::findOrFail($id); 
        $payment->name            = Input::get('name');
        $payment->alias           = Input::get('alias');
        $payment->save();

        return Redirect::action('admin\PaymentTypeController@index', compact('payment'))->with('flash-update','Data berhasil diubah.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $payment = PaymentType::where('id', $id)->firstOrFail();
        $payment->delete();
        return Redirect::action('admin\PaymentTypeController@index')->with('flash-destroy','Data berhasil dihapus.');
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
        $payment = PaymentType::where('name','LIKE','%'.$search.'%')->paginate(25);
        return view('admin.jenisbayar.list', compact('payment'),array('user' => $user));
    }

    // public function ajax_getsekolah_tahun(Request $request)
    // { 
    //     $bayar = PaymentType::where('program_id','=',$request->get('program_id'))->get();

    //     return response()->json($bayar);
    // }
}