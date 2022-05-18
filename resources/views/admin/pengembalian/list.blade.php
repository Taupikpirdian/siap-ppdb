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
    <h3 class="page-title">List Uang Keluar</h3>
  </div>
</div>

<div class="row">
  <div class="col">
    <div class="card card-small mb-5">
      <div class="card-header border-bottom">
        <div class="pull-left">
			<div class='form-group clearfix'>
				<div class='col-md-5'>
		      	</div>
		    </div>
		  </div>
		{!! Form::open(['method'=>'GET','url'=>'/search_uang_keluar','role'=>'search'])  !!}
		<div class=" col-md-5 input-group mb-1 px-0">
       	 <input name="search" type="text" style="margin:2px;" class="form-control" placeholder="Masukan Nama" aria-label="Masukan Nama" aria-describedby="basic-addon2">
        	<div class="input-group-append">
        		<button class="button button-white" style="margin:2px;" type="submit">Search</button>
      			<div class="input-group-append">
      			<a title="Download Excel" href="{{ route('export.pengembalian',['type'=>'xls']) }}" class="btn button-primary btn-sm" style="margin:2px;" target="_blank"><i class="fa fa-download"></i></a>
      			</div>
      			<div class="input-group-append">
          		<a title="Print " class="btn button-warning btn-sm" target="_blank"  style="margin:2px;"   href='{{URL::action("admin\UangKeluarController@Pengembalian")}}'><i class="fa fa-print" style="color: white"></i></a>
        		</div>
        	</div>
        	
    	</div>
		{!! Form::close() !!}
      
      <div class="card-body p-0 pb-3 text-center">
        <table class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="border-0" style="width:10px;">No</th>
              <th scope="col" class="border-0">Nama</th>
              <th scope="col" class="border-0">Jenis Pembayaran</th>
              <th scope="col" class="border-0">Sekolah</th>
              <th scope="col" class="border-0">Tanggal Pengembalian</th>
              <th scope="col" class="border-0">Jumlah</th>
              <th scope="col" class="border-0">Kembali</th>
              <th scope="col" class="border-0" style="width:90px;">Actions</th>
            </tr>
          </thead>
          <tstatus>
			   @foreach($uang_keluar as $i=>$uang_keluars)
		     	<tr>
		     	 <td>{{ ($uang_keluar->currentpage()-1) * $uang_keluar->perpage() + $i + 1 }}</td>
		         <td> {{ $uang_keluars->nama_siswa }} </td>
		         <td> {{ $uang_keluars->name }} </td>
		         <td> {{ $uang_keluars->nama_sekolah }} </td>
		         <td> {{ Carbon\Carbon::parse($uang_keluars->tgl_pengembalian)->formatLocalized('%d %B %Y')}} </td>
		         <td> Rp. {{ number_format($uang_keluars->jumlah) }}</td>
		         <td> Rp. {{ number_format($uang_keluars->denda) }}</td>
		         <td>
					<form class="btn btn-danger btn-sm" id="delete_uang_keluars{{$uang_keluars->id}}" action='{{URL::action("admin\UangKeluarController@destroy",array($uang_keluars->id))}}' method="POST">
						<input type="hidden" name="_method" value="delete">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<a href="#" onclick="document.getElementById('delete_uang_keluars{{$uang_keluars->id}}').submit();"><i class="fa fa-trash fa-xs" style="color: white"></i></a>
					</form>
				</td>	         
		     	</tr>
			   @endforeach
		</tstatus>
        </table>
				{!! $uang_keluar->render() !!}
      </div>
    </div>
  </div>
</div>
@endsection