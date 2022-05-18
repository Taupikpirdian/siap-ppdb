<?php

namespace App\Http\Controllers\admin;

use Auth;
use View;
use Image;
use App\Sekolah;
use App\Year;
use App\Program;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class ProgramController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $programs = Program::orderBy('created_at', 'desc')->paginate(25);
        return view('admin.program.list',array('programs'=>$programs, 'user' => $user));
    }   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        return View::make('admin.program.create', array('user' => $user));
    }

    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit();
        // store
        $programs= new Program;
        $programs->nama         = Input::get('nama');
        $programs->save();
        // redirect
        return Redirect::action('admin\ProgramController@index')->with('flash-store','Data berhasil ditambahkan.');
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
        $programs = Program::where('id', $id)->firstOrFail();   
        return view('admin.program.show', compact('programs'),array('user' => $user));
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
        $programs = Program::where('id', $id)->firstOrFail();   
        return view('admin.program.edit', compact('programs'),array('user' => $user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $programs = Program::findOrFail($id); 
        $programs->nama         = Input::get('nama');
        $programs->save();

        return Redirect::action('admin\ProgramController@index', compact('programs'))->with('flash-update','Data berhasil diubah.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $programs = Program::where('id', $id)->firstOrFail();
        $programs->delete();
        return Redirect::action('admin\ProgramController@index')->with('flash-destroy','Data berhasil dihapus.');
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
        $programs = Program::where('program','LIKE','%'.$search.'%')->paginate(25);
        return view('admin.program.list', compact('programs'),array('user' => $user));
    }

    // public function ajax_getsekolah_tahun(Request $request)
    // { 
    //     $tahuns = Program::where('thn_id','=',$request->get('thn_id'))->get();
    //     return response()->json($tahuns);
    // }
}
