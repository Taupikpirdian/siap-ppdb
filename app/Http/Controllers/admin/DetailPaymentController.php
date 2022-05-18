<?php

namespace App\Http\Controllers\admin;

use Auth;
use View;
use Image;
use Excel;
use App\Sekolah;
use App\UserSekolah;
use App\Group;
use App\Siswa;
use App\Year;
use App\Program;
use App\PaymentType;
use App\Cost;
use App\Candidate;
use App\DetailPaymentCandidate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class DetailPaymentController extends Controller
{
	public function index()
    {
        $user = Auth::user();
        $detail_payments = DetailPaymentCandidate::orderBy('detail_payment_candidates.created_at', 'desc')
        ->leftJoin('candidates', 'candidates.id', '=', 'detail_payment_candidates.candidates_id')
        ->leftJoin('sekolahs', 'sekolahs.id', '=', 'detail_payment_candidates.sekolah_id')
        ->leftJoin('years', 'years.id', '=', 'detail_payment_candidates.thn_id')
        ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
        ->leftjoin('costs', 'costs.id', '=', 'detail_payment_candidates.cost_id')
        ->leftJoin('programs', 'programs.id', '=', 'detail_payment_candidates.program_id')
        ->select(
                  'candidates.*',
                  'sekolahs.nama_sekolah',
                  'years.tahun',
                  'programs.nama as nm_prog',
                  'payment_types.name as nm_payment',
                  'costs.name as nm_cost',
                  'detail_payment_candidates.*'
                 )
        ->paginate(25);
        return view('admin.detail-payment.list',array('detail_payments'=>$detail_payments, 'user' => $user));
    }   

    public function show($id)
    {
        $user = Auth::user();
        $detail_payments = DetailPaymentCandidate::where('id', $id)->firstOrFail();

        return view('admin.detail-payment.show', compact('detail_payments'),array('user' => $user));
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
        $scholl->prepend('Pilih Nama Sekolah', '');
        $year=Year::pluck('tahun', 'id');
        $year->prepend('Pilih Tahun', '');
        $prog=Program::pluck('nama', 'id');
        $prog->prepend('Pilih Program', '');
        $payment=PaymentType::pluck('name', 'id');
        $payment->prepend('Pilih Jenis Pembayaran', '');
        $costs=Cost::pluck('name', 'id');
        $costs->prepend('Pilih Biaya', '');
        $detail_payments = DetailPaymentCandidate::where('id', $id)->firstOrFail();
        return view('admin.detail-payment.edit', compact('detail_payments', 'detail_payment', 'scholl', 'year', 'prog', 'payment', 'costs'),array('user' => $user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $detail_payments = DetailPaymentCandidate::findOrFail($id);
        $detail_payments->tgl_bayar         = Input::get('tgl_bayar');
        $detail_payments->no_kwitansi       = Input::get('no_kwitansi');
        $detail_payments->payment_id        = Input::get('payment_id');
        $detail_payments->cost_id           = Input::get('cost_id');
        // echo "<pre>";
        // print_r($detail_payments);
        // echo "</pre>";
        // exit();

        $detail_payments->save();

        return Redirect::action('admin\DetailPaymentController@index', compact('detail_payments'))->with('flash-update','Data berhasil diubah.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $detail_payments = DetailPaymentCandidate::where('id', $id)->firstOrFail();
        $detail_payments->delete();

        return Redirect::action('admin\DetailPaymentController@index')->with('flash-destroy','Data berhasil dihapus.');
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
        $detail_payments = DetailPaymentCandidate::leftJoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
        ->leftJoin('costs', 'costs.id', '=', 'detail_payment_candidates.cost_id')
        ->leftJoin('candidates', 'candidates.id', '=', 'detail_payment_candidates.candidates_id')
        ->orderBy('detail_payment_candidates.created_at', 'desc')
        ->select(
                    'detail_payment_candidates.*',
                    'candidates.*',
                    'payment_types.name as nm_payment',
                    'costs.name as nm_biaya'
                )
        ->whereYear('detail_payment_candidates.created_at','LIKE','%'.$search.'%')
        ->orwhere('nama','LIKE','%'.$search.'%')
        ->paginate(25);

        return view('admin.detail-payment.list', compact('detail_payments'),array('user' => $user));
    }

    public function ajax_getsekolah_tahun(Request $request)
    {
      $biaya = DetailPaymentCandidate::where('cost_id','=',$request->get('cost_id'))->get();

      return response()->json($biaya);
    }

    public function invoice($id)
	{   
		$user = Auth::user();
        $id_user = Auth::user()->id;
        $name_user = Auth::user()->name;
        $candidate = Candidate::leftjoin('sekolahs', 'sekolahs.id', '=', 'candidates.sekolah_id')
            ->leftjoin('detail_payment_candidates', 'detail_payment_candidates.candidates_id', '=', 'candidates.id')
            ->leftjoin('costs', 'costs.id', '=', 'detail_payment_candidates.cost_id')
            ->leftjoin('payment_types', 'payment_types.id', '=', 'detail_payment_candidates.payment_id')
            ->leftJoin('programs', 'programs.id', '=', 'candidates.program_id')
            ->orderBy('candidates.created_at', 'asc')
            ->where('detail_payment_candidates.id', $id)
            ->select(   
                'sekolahs.nama_sekolah',
                'sekolahs.invoice_no',
                'detail_payment_candidates.cost_id as cost_name',
                'programs.nama as program_nama',
                'payment_types.alias',
                'payment_types.name as payment_name',
                'candidates.*',
                'detail_payment_candidates.*'
            )
            ->firstOrFail();
		return view('admin.detail-payment.invoice', compact('name_user') , array('candidate'=>$candidate, 'user' => $user));
	}
}
