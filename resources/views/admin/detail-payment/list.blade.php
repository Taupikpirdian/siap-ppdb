@extends('admin.admin')

@section('content')

@if ($message = Session::get('flash-store'))
	<div class="alert alert-success alert-block">
		<button type="button" class="close" data-dismiss="alert">x</button>
		<strong>{{ $message }}</strong>
	</div>
@endif

@if ($message = Session::get('flash-update'))
	<div class="alert alert-info alert-block">
		<button type="button" class="close" data-dismiss="alert">x</button>
		<strong>{{ $message }}</strong>
	</div>
@endif

@if ($message = Session::get('flash-destroy'))
	<div class="alert alert-danger alert-block">
		<button type="button" class="close" data-dismiss="alert">x</button>
		<strong>{{ $message }}</strong>
	</div>
@endif

@if ($message = Session::get('flash-approve'))
	<div class="alert alert-success alert-block">
		<button type="button" class="close" data-dismiss="alert">x</button>
		<strong>{{ $message }}</strong>
	</div>
@endif

<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <h3 class="page-title">List Detail Pembayaran PPDB</h3>
  </div>
</div>

<div class="row">
  <div class="col">
    <div class="card card-small mb-5">
      <div class="card-header border-bottom">
        <div class="pull-left">
			<div class='form-group clearfix'>
				<div class='col-md-5'>
					<!-- <div class="input-group custom-search-form pull-left">
   					@if (Auth::user()->hasAnyRole('Create Sekolah'))
						<div class='pull-left col-md-2'>
							<a href="{{URL::to('sekolah/create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a>
						</div>  
						@endif
					</div>        -->
		      	</div>
		    </div>
		  </div>
		{!! Form::open(['method'=>'GET','url'=>'/searchdetail_payments','role'=>'search'])  !!}
		<div class=" col-md-5 input-group mb-1 px-0">
			@if (Auth::user()->hasAnyRole('Create detail_payments'))
			<a href="{{URL::to('detail_payments/create')}}" style="margin:2px;" class="button button-primary"><i class="fa fa-plus-circle"></i></a>
			@endif
       	 	<input name="search" type="text" style="margin:2px;" class="form-control" placeholder="Cari Nama atau tahun" aria-label="Cari Nama atau Tahun" aria-describedby="basic-addon2">
        	<div class="input-group-append">
        		<button class="button button-white" style="margin:2px;" type="submit">Search</button>
        	</div>
    	</div>
		{!! Form::close() !!}
      
      <div class="card-body p-0 pb-3 text-center">
        <table class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="border-0" style="width:10px;">No</th>
              <th scope="col" class="border-0">Tanggal Bayar</th>
              <th scope="col" class="border-0">Nama</th>
              <th scope="col" class="border-0">Jenis Pembayaran</th>
              <th scope="col" class="border-0">Biaya</th>
              <th scope="col" class="border-0" style="width:120px;">Actions</th>
            </tr>
          </thead>
          <tstatus>
		   @foreach($detail_payments as $i=>$detail_payment)
		     	<tr>
		     	 <td>{{ ($detail_payments->currentpage()-1) * $detail_payments->perpage() + $i + 1 }}</td>
		         <td> {{ $detail_payment->tgl_bayar }} </td>
		         <td> {{ $detail_payment->nama }} </td>
		         <td> {{ $detail_payment->nm_payment }} </td>
		         <td> Rp. {{{ number_format($detail_payment->cost_id) }}} </td>
		         <td>
				@if (Auth::user()->hasAnyRole('Edit Sekolah'))
					<a title="Edit" class="btn btn-warning btn-sm" href='{{URL::action("admin\DetailPaymentController@edit",array($detail_payment->id))}}'><i class="fa fa-edit fa-xs" style="color: white"></i></a>
				@endif
				@if (Auth::user()->hasAnyRole('Delete Sekolah'))
					<form class="btn btn-danger btn-sm" id="delete_siswa{{$detail_payment->id}}" action='{{URL::action("admin\DetailPaymentController@destroy",array($detail_payment->id))}}' method="POST">
						<input type="hidden" name="_method" value="delete">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<a title="Delete" href="#" onclick="document.getElementById('delete_siswa{{$detail_payment->id}}').submit();"><i class="fa fa-trash fa-xs" style="color: white"></i></a>
					</form>
				@endif
					<a title="Print Bukti Pembayaran" class="btn btn-warning btn-sm" target="_blank" href='{{URL::action("admin\DetailPaymentController@invoice",array($detail_payment->id))}}'><i class="fa fa-print fa-xs" style="color: white"></i></a>
				</td>	         
		     	</tr>
			   @endforeach
		</tstatus>
        </table>
				{!! $detail_payments->render() !!}
      </div>
    </div>
  </div>
</div>
@endsection