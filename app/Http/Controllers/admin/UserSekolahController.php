<?php

namespace App\Http\Controllers\admin;

use DB;
use Auth;
use View;
use Image;
use File;
use Alert;
use Validator;
use App\UserSekolah;	
use App\User; 
use App\Sekolah; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class UserSekolahController extends Controller
{
  public function index()
  {   
    $user = Auth::user();
    $user_sekolah = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
    ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
    ->orderBy('user_sekolahs.created_at', 'desc')
    ->select(
            'users.*',
            'sekolahs.*',
            'user_sekolahs.*'
            )
    ->paginate(25);

    return view('admin.user_sekolah.list',array('user_sekolah'=>$user_sekolah, 'user' => $user));

  }

  public function create()
  {
    $user = Auth::user();
    $users=User::pluck('name', 'id');
    $users->prepend('Pilih User', '');

    $school=Sekolah::pluck('nama_sekolah', 'id');
    $school->prepend('Pilih Sekolah', '');
    return View::make('admin.user_sekolah.create', compact('users', 'school'));
  }

  public function store(Request $request)
  {
    $user = Auth::user();
    $user_sekolah = new UserSekolah;
    $user_sekolah->user_id           = Input::get('user_id');
    $user_sekolah->sekolah_id        = Input::get('sekolah_id');
    $user_sekolah->save();
    
    return Redirect::action('admin\UserSekolahController@index');
  }

  public function show($id)
  {
    $user_sekolah = UserSekolah::findOrFail($id);
    return view('admin.user_sekolah.show',[ 'user_sekolah' => $user_sekolah]);
  }

  public function edit($id)
  {
    $user_sekolah = UserSekolah::findOrFail($id);
    $users=User::pluck('name', 'id');
    $users->prepend('Pilih User', '');

    $school=Sekolah::pluck('nama_sekolah', 'id');
    $school->prepend('Pilih Sekolah', '');

    return view('admin.user_sekolah.edit',compact('users', 'school'), array('user_sekolah' => $user_sekolah));
  }

  public function update(Request $request, $id)
  {
    $user_sekolah = UserSekolah::findOrFail($id);
    $user_sekolah->user_id           = Input::get('user_id');
    $user_sekolah->sekolah_id        = Input::get('sekolah_id');
    $user_sekolah->save();

    return Redirect::action('admin\UserSekolahController@index');
  }

  public function destroy($id)
  {
    $user_sekolah = UserSekolah::findOrFail($id);
    $user_sekolah->delete();

    return Redirect::action('admin\UserSekolahController@index')->with('flash-success','The user has been deleted.');
  }

  public function search(Request $request)

  {
    $search = $request->get('search');
    $user_sekolah = UserSekolah::leftjoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
    ->leftjoin('users', 'users.id', '=', 'user_sekolahs.user_id')
    ->orderBy('user_sekolahs.created_at', 'desc')
    ->select(
            'users.*',
            'sekolahs.*',
            'user_sekolahs.*'
            )
    ->where('name','LIKE','%'.$search.'%')
    ->orwhere('nama_sekolah','LIKE','%'.$search.'%')
    ->paginate(25);

    return view('admin.user_sekolah.list', compact('user_sekolah'));
  }
}
