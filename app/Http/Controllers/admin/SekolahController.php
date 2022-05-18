<?php

namespace App\Http\Controllers\admin;

use Auth;
use DB;
use View;
use Image;
use App\Sekolah;
use App\Penerimaan;
use App\Siswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class SekolahController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $school = Sekolah::orderBy('created_at', 'desc')->paginate(25);
        $start_page= (($school->currentPage()-1) * 25) + 1;
        return view('admin.sekolah.list',array('school'=>$school, 'user' => $user, 'start_page'=>$start_page));
    }   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        return View::make('admin.sekolah.create', array('user' => $user));
    }

    public function store(Request $request)
    {
        
        // store
        $school= new Sekolah;
        $school->nama_sekolah      = Input::get('nama_sekolah');
        $school->save();
        // redirect
        return Redirect::action('admin\SekolahController@index')->with('flash-store','Data berhasil ditambahkan.');
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
        $school = Sekolah::where('id', $id)->firstOrFail();   
        return view('admin.sekolah.show', compact('school'),array('user' => $user));
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
        $school = Sekolah::where('id', $id)->firstOrFail();   
        return view('admin.sekolah.edit', compact('school'),array('user' => $user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $school = Sekolah::findOrFail($id); 
        $school->nama_sekolah      = Input::get('nama_sekolah');
        $school->save();

        return Redirect::action('admin\SekolahController@index', compact('school'))->with('flash-update','Data berhasil diubah.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {

        if(Auth::user()->hasAnyRole('Delete Sekolah')){
        $sekolah = Sekolah::findOrFail($id);
        $sekolahs = Siswa::orderBy('siswas.created_at','asc')
        ->leftJoin('sekolahs', 'sekolahs.id', '=', 'siswas.sekolah_id')
        ->leftJoin('penerimaans', 'penerimaans.id_sekolah', '=', 'sekolahs.id')
        ->whereSekolahId($id)
        ->get();
          // echo "<pre>";
          // print_r($group);
          // echo "</pre>";
          // exit();

        if(!$sekolahs->isEmpty()){
            return Redirect::action('admin\SekolahController@index')->with('flash-error','Data Sekolah tidak bisa dihapus karena sudah ada data yang mengacu pada data sekolah, Silakan menghapus data yang mengacu Data Sekolah terlebih dahulu.');  
         }else{
            $sekolah->delete();
            return Redirect::action('admin\SekolahController@index')->with('flash-success','Data Sekolah sudah berhasil dihapus.');
         }
        }else{
            return Redirect::action('admin\AdminController@memberfeed')->with('flash-error','ERROR PERMISSIONS.');
        }

        // $school = Sekolah::where('id', $id)->firstOrFail();
        // $school->delete();
        // return Redirect::action('admin\SekolahController@index')->with('flash-destroy','Data berhasil dihapus.');
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
        $school = Sekolah::where('nama_sekolah','LIKE','%'.$search.'%')->paginate(25);
        return view('admin.sekolah.list', compact('school'),array('user' => $user));
    }

    public function reset(Request $request)
    {
        DB::table('sekolahs')->update(['invoice_no'=>0]);
        return Redirect::action('admin\SekolahController@index')->with('flash-store','Data berhasil direset.');
    }
}