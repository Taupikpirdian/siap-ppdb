<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
      $user = Auth::user();
      if (Auth::check()) {
        return view('admin.admin',array('user' => $user));
      }
        return view('auth.login');
    }
}
