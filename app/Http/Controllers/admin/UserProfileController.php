<?php

namespace App\Http\Controllers\admin;

use DB;
use View;
use Auth;
use Image;
use File;
use Alert;
use Validator;
use App\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class UserProfileController extends Controller
{
    public function index()
  {   
    
    /*if(Auth::user()->hasAnyRole('List Edit profile')){*/
    $user = Auth::user();
    $user_profile = UserProfile::leftJoin('users', 'users.id', '=', 'user_profiles.user_id')
                                ->leftJoin('user_groups', 'user_groups.user_id', '=', 'users.id')
                                ->orderBy('user_profiles.created_at', 'desc')
                                ->select(
                                          'users.name',
                                          'users.id',
                                          'user_profiles.address',
                                          'user_profiles.birth_date',
                                          'user_profiles.place_birth',
                                          'user_profiles.phone',
                                          'user_profiles.user_status_id',
                                          'user_profiles.bio'
                                  )
                                ->paginate(25);
    return view('admin.user-profile.list',array('user_profile'=>$user_profile, 'user' => $user));
     /*}else{
      return Redirect::action('admin\UserProfileController@index')->with('flash-error','ERROR PERMISSIONS.');
    }*/

  }

  public function create()
  {

    if(Auth::user()->hasAnyRole('List create profile')){
    $user_profile = UserProfile::orderBy('created_at','asc')->get();

    return View::make('admin.user-profile.create', compact('user_profile'));
    }else{
      return Redirect::action('admin\UserProfileController@index')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function store(Request $request)
  {
    $rules = [
      'user_id'           =>'|required',
      'address'           =>'|required',
      'birth_date'        =>'|required',
      'place_birth'       =>'|required',
      'phone'             =>'|required',
      'user_status_id'    =>'|required',
      'bio'               =>'|required',
    ];

    $messages = [
      'required'    => 'Input :attribute harus diisi!',
    ];

    Validator::make($request->all(), $rules, $messages)->validate();
    
    // echo "<pre>";
    // print_r($request->all());
    // echo "</pre>";
    // exit();
    $user_profile = new UserProfile;
    $user_profile->user_id        	= Input::get('user_id');
    $user_profile->address     			= Input::get('address');
    $user_profile->birth_date  	 		= Input::get('date');
    $user_profile->place_birth   		= Input::get('place_birth');
    $user_profile->phone   					= Input::get('phone');
    $user_profile->user_status_id   = Input::get('user_status_id');
    $user_profile->bio   						= Input::get('bio');
    $user_profile->save();
    
    Alert::success('Anda telah berhasil menambahkan testimony !', 'Terimaksih !')->persistent('Close');  
    return Redirect::action('admin\UserProfileController@index');
  }

  public function show($id)
  {
      if(Auth::user()->hasAnyRole('List show profile')){
      $user_profile = UserProfile::findOrFail($id);
      
      return view('admin.user-profile.show',array('user_profile' => $user_profile));
      }else{
      return Redirect::action('admin\UserProfileController@index')->with('flash-error','ERROR PERMISSIONS.');
    }
  }

  public function edit($id)
  {
    $user_profile = UserProfile::findOrFail($id);
    
    return view('admin.user-profile.edit',array('user_profile' => $user_profile));
  }

  public function update(Request $request, $id)
  {
    $rules = [
      'user_id'       		=>'|required',
      'address'     			=>'|required',
      'birth_date'       	=>'|required',
      'place_birth'       =>'|required',
      'phone'   					=>'|required',
      'user_status_id'    =>'|required',
      'bio'       				=>'|required',
    ];

    $messages = [
      'required'    => 'Input :attribute harus diisi!',
    ];

    Validator::make($request->all(), $rules, $messages)->validate();

    $user_profile = UserProfile::findOrFail($id);
    $user_profile->user_id        	= Input::get('user_id');
    $user_profile->address     			= Input::get('address');
    $user_profile->birth_date  	 		= Input::get('birth_date');
    $user_profile->place_birth   		= Input::get('place_birth');
    $user_profile->phone   					= Input::get('phone');
    $user_profile->user_status_id   = Input::get('user_status_id');
    $user_profile->bio   						= Input::get('bio');
    $user_profile->save();

    Alert::success('Anda telah berhasil mengedit testimony !', 'Terimaksih !')->persistent('Close');  
    return Redirect::action('admin\UserProfileController@index');
  }

  public function destroy($id)
  {
    $user_profile = UserProfile::findOrFail($id);
    $user_profile->delete();
    
    Alert::success('Anda telah berhasil menghapus testimony !', 'Terimaksih !')->persistent('Close');  
    return Redirect::action('admin\UserProfileController@index')->with('flash-success','The user has been deleted.');
  }

  public function search(Request $request)
  {
    $search = $request->get('search');
    $user_profile = UserProfile::where('nama','LIKE','%'.$search.'%')->paginate(25);

    return view('admin.user-profile.list', compact('user_profile'));
  }
}
