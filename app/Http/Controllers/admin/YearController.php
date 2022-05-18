<?php

namespace App\Http\Controllers\admin;

use Auth;
use View;
use Image;
use App\Year;
use App\Sekolah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class YearController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $year = Year::orderBy('created_at', 'desc')->paginate(25);
        return view('admin.tahun.list',array('year'=>$year, 'user' => $user));
    }   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        return View::make('admin.tahun.create', array('user' => $user));
    }

    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit();
        // store
        $year= new Year;
        $year->tahun              = Input::get('tahun');
        $year->save();
        // redirect

        return Redirect::action('admin\YearController@index')->with('flash-store','Data berhasil ditambahkan.');
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
        $year = Year::where('id', $id)->firstOrFail();

        return view('admin.tahun.show', compact('year'),array('user' => $user));
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
        $year = Year::where('id', $id)->firstOrFail();
        return view('admin.tahun.edit', compact('year'),array('user' => $user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        //  echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit();
        $year = Year::findOrFail($id);
        $year->tahun              = Input::get('tahun');
        $year->save();

        return Redirect::action('admin\YearController@index', compact('year'))->with('flash-update','Data berhasil diubah.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $year = Year::where('id', $id)->firstOrFail();
        $year->delete();

        return Redirect::action('admin\YearController@index')->with('flash-destroy','Data berhasil dihapus.');
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
        $year = Year::where('tahun','LIKE','%'.$search.'%')->paginate(25);
        return view('admin.tahun.list', compact('year'),array('user' => $user));
    }

    // public function ajax_getsekolah_tahun(Request $request)
    // { 
    //     $tahun = Year::where('sekolah_id','=',$request->get('sekolah_id'))->get();

    //     return response()->json($tahun);
    // }
}