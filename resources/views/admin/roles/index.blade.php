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
    <h3 class="page-title">List Role</h3>
  </div>
</div>

<div class="row">
  <div class="col">
    <div class="card card-small mb-5">
      <div class="card-header border-bottom">
        <div class="pull-left">
			<div class='form-group clearfix'>
				<!-- <div class='col-md-5'>
					<div class="input-group custom-search-form pull-left">
			        	<div class='pull-left col-md-2'>
							<a href="{{URL::to('/roles/create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a>
						</div>  
					</div>       
		      	</div> -->
		    </div>
		  </div>
		{!! Form::open(['method'=>'GET','url'=>'/search-roles','role'=>'search'])  !!}
		<div class="col-md-6 input-group mb-1 px-0">
					<a href="{{URL::to('/roles/create')}}" style="margin:2px;" class="button button-primary"><i class="fa fa-plus-circle"></i></a>
       	 	<input name="search" type="text" style="margin:2px;" class="form-control" placeholder="Masukan Nama Sekolah" aria-label="Masukan Nama Role" aria-describedby="basic-addon2">
        	<div class="input-group-append">
        		<button class="button button-white" style="margin:2px;" type="submit">Search</button>
        	</div>
    	</div>
		{!! Form::close() !!}
      
      <div class="card-body p-0 pb-3 text-center">
        <table class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="border-0" style="width:5%;">No</th>
              <th scope="col" class="border-0">Role</th>
              <th scope="col" class="border-0" style="width:15%;">Actions</th>
            </tr>
          </thead>
          <tstatus>
		   @foreach($roles as $i=>$role)
		     	<tr>
		     	 <td>{{ ($roles->currentpage()-1) * $roles->perpage() + $i + 1 }}</td>
		         <td> {{ $role->name }} </td>
		         <td>
					<a class="btn btn-warning btn-sm" href='{{URL::action("admin\RoleController@edit",array($role->id))}}'><i class="fa fa-edit fa-xs" style="color: white"></i></a>
					<a class="btn btn-info btn-sm" href='{{URL::action("admin\RoleController@show",array($role->id))}}'><i class="fa fa-eye fa-xs"></i></a>
					<form class="btn btn-danger btn-sm" id="delete_role{{$role->id}}" onclick="return confirm('Apakah anda yakin untuk menghapus data ini?')" action='{{URL::action("admin\RoleController@destroy",array($role->id))}}' method="POST">
						<input type="hidden" name="_method" value="delete">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<a href="#" onclick="document.getElementById('delete_role{{$role->id}}').submit();"><i class="fa fa-trash fa-xs" style="color: white"></i></a>
					</form>
				</td>	         
		     	</tr>
			   @endforeach
		</tstatus>
        </table>
				{!! $roles->render() !!}
      </div>
    </div>
  </div>
</div>
@endsection