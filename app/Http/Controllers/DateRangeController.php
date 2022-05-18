<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DetailPaymentCandidate;
use App\Candidates;
use App\UserSekolah;
use App\PembayaranSiswaAktif;
use DB;
use Auth;
use App\Group;

class DateRangeController extends Controller
{
    function fetch_data(Request $request)
    {
    $user = Auth::user();
    // Mengambil id login
    $id_user = Auth::user()->id;
    // Mengambil tanggal sekarang
    $dataDate = date('Y-m-d');
    // Mengambil nama group yang login
    $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                ->where('user_groups.user_id', Auth::id())
                ->select('groups.name AS group')
                ->first();
    
     if($request->ajax())
     {
      if($request->from_date != '' && $request->to_date != '')
      {
        if($login->group=='Admin')
        {
            $data = DetailPaymentCandidate::orderBy('detail_payment_candidates.tgl_bayar', 'desc')
            ->leftJoin('siswas', 'siswas.id', '=', 'detail_payment_candidates.candidates_id')
            ->leftJoin('sekolahs', 'sekolahs.id', '=', 'detail_payment_candidates.sekolah_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->where('detail_payment_candidates.payment_id', '=', 1)
            ->whereBetween('tgl_bayar', array($request->from_date, $request->to_date))
            ->select(
                    'detail_payment_candidates.*',
                    'siswas.nama_siswa',
                    'sekolahs.nama_sekolah as sekolah',
                    'payment_types.name as payment'
                )
            ->get();
            echo json_encode($data);
        }else{
            $data = UserSekolah::orderBy('detail_payment_candidates.tgl_bayar', 'desc')
            ->leftJoin('detail_payment_candidates', 'detail_payment_candidates.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftJoin('siswas', 'siswas.id', '=', 'detail_payment_candidates.candidates_id')
            ->leftJoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftJoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->where('detail_payment_candidates.payment_id', '=', 1)
            ->whereBetween('tgl_bayar', array($request->from_date, $request->to_date))
            ->select(
                    'detail_payment_candidates.*',
                    'siswas.nama_siswa',
                    'sekolahs.nama_sekolah as sekolah',
                    'payment_types.name as payment'
                )
            ->get();
            echo json_encode($data);
        }
      }else{
        return Redirect::action('admin\LaporanController@formulir');
      }
    }
  }

  function fetch_data_register(Request $request)
    {
    $user = Auth::user();
    // Mengambil id login
    $id_user = Auth::user()->id;
    // Mengambil tanggal sekarang
    $dataDate = date('Y-m-d');
    // Mengambil nama group yang login
    $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                ->where('user_groups.user_id', Auth::id())
                ->select('groups.name AS group')
                ->first();
    
     if($request->ajax())
     {
      if($request->from_date != '' && $request->to_date != '')
      {
        if($login->group=='Admin')
        {
            $data = DetailPaymentCandidate::orderBy('detail_payment_candidates.tgl_bayar', 'desc')
            ->leftJoin('siswas', 'siswas.id', '=', 'detail_payment_candidates.candidates_id')
            ->leftJoin('sekolahs', 'sekolahs.id', '=', 'detail_payment_candidates.sekolah_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->where('detail_payment_candidates.payment_id', '=', 2)
            ->whereBetween('tgl_bayar', array($request->from_date, $request->to_date))
            ->select(
                    'detail_payment_candidates.*',
                    'siswas.nama_siswa as siswa',
                    'sekolahs.nama_sekolah as sekolah',
                    'payment_types.name as payment'
                )
            ->get();
            echo json_encode($data);
        }else{
            $data = UserSekolah::orderBy('detail_payment_candidates.tgl_bayar', 'desc')
            ->leftJoin('detail_payment_candidates', 'detail_payment_candidates.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftJoin('siswas', 'siswas.id', '=', 'detail_payment_candidates.candidates_id')
            ->leftJoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftJoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->where('detail_payment_candidates.payment_id', '=', 2)
            ->whereBetween('tgl_bayar', array($request->from_date, $request->to_date))
            ->select(
                    'detail_payment_candidates.*',
                    'siswas.nama_siswa as siswa',
                    'sekolahs.nama_sekolah as sekolah',
                    'payment_types.name as payment'
                )
            ->get();
            echo json_encode($data);
        }
      }else{
        return Redirect::action('admin\LaporanController@daftar');
      }
    }
  }

  function fetch_data_spp(Request $request)
    {
    $user = Auth::user();
    // Mengambil id login
    $id_user = Auth::user()->id;
    // Mengambil tanggal sekarang
    $dataDate = date('Y-m-d');
    // Mengambil nama group yang login
    $login = Group::join('user_groups','user_groups.group_id','=','groups.id')
                ->where('user_groups.user_id', Auth::id())
                ->select('groups.name AS group')
                ->first();
    
     if($request->ajax())
     {
      if($request->from_date != '' && $request->to_date != '')
      {
        if($login->group=='Admin')
        {
            $data = PembayaranSiswaAktif::orderBy('pembayaran_siswa_aktifs.tgl_bayar', 'desc')
            ->leftJoin('siswas', 'siswas.id', '=', 'pembayaran_siswa_aktifs.candidate_id')
            ->leftJoin('sekolahs', 'sekolahs.id', '=', 'pembayaran_siswa_aktifs.sekolah_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'pembayaran_siswa_aktifs.payment_id')
            ->where('pembayaran_siswa_aktifs.payment_id', '=', 3)
            ->whereBetween('tgl_bayar', array($request->from_date, $request->to_date))
            ->select(
                    'pembayaran_siswa_aktifs.tgl_bayar',
                    'pembayaran_siswa_aktifs.semester',
                    'pembayaran_siswa_aktifs.amount',
                    'pembayaran_siswa_aktifs.ket',
                    'siswas.nama_siswa as siswa',
                    'sekolahs.nama_sekolah as sekolah',
                    'payment_types.name as payment'
                )
            ->get();
            echo json_encode($data);
        }else{
            $data = UserSekolah::orderBy('detail_payment_candidates.tgl_bayar', 'desc')
            ->leftJoin('detail_payment_candidates', 'detail_payment_candidates.sekolah_id', '=', 'user_sekolahs.sekolah_id')
            ->leftJoin('siswas', 'siswas.id', '=', 'detail_payment_candidates.candidates_id')
            ->leftJoin('sekolahs', 'sekolahs.id', '=', 'user_sekolahs.sekolah_id')
            ->leftJoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->where('detail_payment_candidates.payment_id', '=', 3)
            ->whereBetween('tgl_bayar', array($request->from_date, $request->to_date))
            ->select(
                    'detail_payment_candidates.*',
                    'siswas.nama_siswa as siswa',
                    'sekolahs.nama_sekolah as sekolah',
                    'payment_types.name as payment'
                )
            ->get();
            echo json_encode($data);
        }
      }else{
        return Redirect::action('admin\LaporanController@spp');
      }
    }
  }

}

