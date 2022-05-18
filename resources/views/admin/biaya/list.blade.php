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
    <h3 class="page-title">List Biaya</h3>
  </div>
</div>

<div class="row">
  <div class="col">
    <div class="card card-small mb-5">
      <div class="card-header border-bottom">
        <div class="pull-left">
			<div class='form-group clearfix'>
				<div class='col-md-5'>
					<div class="input-group custom-search-form pull-left">
   					@if (Auth::user()->hasAnyRole('Create Biaya'))
						<div class='pull-left col-md-2'>
							<a href="{{URL::to('biaya/create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a>
						</div>  
					@endif
					</div>       
		      	</div>
		    </div>
		  </div>
		{!! Form::open(['method'=>'GET','url'=>'/searchbiaya','role'=>'search'])  !!}
		<div class="input-group mb-3">
       	 <input name="search" type="text" class="form-control" placeholder="Masukan Nama Jenis Bayar" aria-label="Masukan Nama Jenis Bayar" aria-describedby="basic-addon2">
        	<div class="input-group-append">
        		<button class="btn btn-white" type="submit">Search</button>
        	</div>
    	</div>
		{!! Form::close() !!}
      
      <div class="card-body p-0 pb-3 text-center">
        <table class="table table table-bordered table-striped table-hover table-condensed tfix mb-1">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="border-0">No</th>
              <th scope="col" class="border-0">Tahun</th>
              <th scope="col" class="border-0">Kode Bayar</th>
              <th scope="col" class="border-0">Jumlah</th>
              <th scope="col" class="border-0">Actions</th>
            </tr>
          </thead>
          <tstatus>
		   @foreach($biaya as $i=>$biayas)
		     	<tr>
		     	 <td>{{ ($biaya->currentpage()-1) * $biaya->perpage() + $i + 1 }}</td>
		         <td> {{ $biayas->thn }} </td>
		         <td> {{ $biayas->kode_bayar }} </td>
		         <td> {{ $biayas->jumlah }} </td>
		         <td>
				@if (Auth::user()->hasAnyRole('Edit Biaya'))
					<a class="btn btn-warning" href='{{URL::action("admin\BiayaController@edit",array($biayas->id))}}'><i class="fa fa-edit" style="color: white"></i></a>
				@endif
				@if (Auth::user()->hasAnyRole('Delete Biaya'))
					<form class="btn btn-danger" id="delete_siswa{{$biayas->id}}" action='{{URL::action("admin\BiayaController@destroy",array($biayas->id))}}' method="POST">
						<input type="hidden" name="_method" value="delete">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<a href="#" onclick="document.getElementById('delete_siswa{{$biayas->id}}').submit();"><i class="fa fa-trash" style="color: white"></i></a>
					</form>
				@endif
				</td>	         
		     	</tr>
			   @endforeach
		</tstatus>
        </table>
		{!! $biaya->render() !!}
      </div>
    </div>
  </div>
</div>
@endsection