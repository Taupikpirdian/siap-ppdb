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
    <h3 class="page-title">List Program</h3>
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
		{!! Form::open(['method'=>'GET','url'=>'/searchprograms','role'=>'search'])  !!}
		<div class=" col-md-5 input-group mb-1 px-0">
				@if (Auth::user()->hasAnyRole('Create Sekolah'))
				<a href="{{URL::to('programs/create')}}" style="margin:2px;" class="button button-primary"><i class="fa fa-plus-circle"></i></a>
				@endif
       	 <input name="search" type="text" style="margin:2px;" class="form-control" placeholder="Masukan Program" aria-label="Masukan Program" aria-describedby="basic-addon2">
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
              <th scope="col" class="border-0">Program</th>
              <th scope="col" class="border-0" style="width:90px;">Actions</th>
            </tr>
          </thead>
          <tstatus>
		   @foreach($programs as $i=>$program)
		     	<tr>
		     	 <td>{{ ($programs->currentpage()-1) * $programs->perpage() + $i + 1 }}</td>
		         <td> {{ $program->nama }} </td>
		         <td>
				@if (Auth::user()->hasAnyRole('Edit Sekolah'))
					<a class="btn btn-warning btn-sm" href='{{URL::action("admin\ProgramController@edit",array($program->id))}}'><i class="fa fa-edit fa-xs" style="color: white"></i></a>
				@endif
				@if (Auth::user()->hasAnyRole('Delete Sekolah'))
					<form class="btn btn-danger btn-sm" id="delete_programs{{$program->id}}" action='{{URL::action("admin\ProgramController@destroy",array($program->id))}}' method="POST">
						<input type="hidden" name="_method" value="delete">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<a href="#" onclick="document.getElementById('delete_programs{{$program->id}}').submit();"><i class="fa fa-trash fa-xs" style="color: white"></i></a>
					</form>
				@endif
				</td>	         
		     	</tr>
			   @endforeach
		</tstatus>
        </table>
				{!! $programs->render() !!}
      </div>
    </div>
  </div>
</div>
@endsection