@extends('admin.admin')

@section('css')
{!! Html::style('css/popup.css') !!}
@endsection

@section('content')

@if ($message = Session::get('flash-store'))
	<div class="alert alert-success alert-block">
		<button type="button" style="color:#fff;" class="close" data-dismiss="alert">x</button>
		<strong style="font-family: Palatino; font-size: 14px">{{ $message }}</strong>
	</div>
@endif

@if ($message = Session::get('flash-error'))
	<div class="alert alert-danger alert-block">
		<button type="button" style="color:#fff;"  class="close" data-dismiss="alert">x</button>
		<strong style="font-family: Palatino; font-size: 14px">{{ $message }}</strong>
	</div>
@endif

@if ($message = Session::get('flash-success'))
	<div class="alert alert-success alert-block">
		<button type="button" style="color:#fff;"  class="close" data-dismiss="alert">x</button>
		<strong style="font-family: Palatino; font-size: 14px">{{ $message }}</strong>
	</div>
@endif

@if ($message = Session::get('flash-update'))
	<div class="alert alert-info alert-block">
		<button type="button" style="color:#fff;" class="close" data-dismiss="alert">x</button>
		<strong style="font-family: Palatino; font-size: 14px">{{ $message }}</strong>
	</div>
@endif

@if ($message = Session::get('flash-destroy'))
	<div class="alert alert-danger alert-block">
		<button type="button" style="color:#fff;" class="close" data-dismiss="alert">x</button>
		<strong style="font-family: Palatino; font-size: 14px">{{ $message }}</strong>
	</div>
@endif

@if ($message = Session::get('flash-approve'))
	<div class="alert alert-success alert-block">
		<button type="button" style="color:#fff;" class="close" data-dismiss="alert">x</button>
		<strong style="font-family: Palatino; font-size: 14px">{{ $message }}</strong>
	</div>
@endif

<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <h3 class="page-title">List Siswa</h3>
  </div>
</div>
<div class="row">
  <div class="col">
    <div class="card card-small mb-5">
      <div class="card-header border-bottom">
		{!! Form::open(['method'=>'GET','url'=>'/searchsiswa','role'=>'search'])  !!}
		<div class="col-md-6 input-group mb-1 px-0">
					@if (Auth::user()->hasAnyRole('Create Siswa'))
						<a title="Create" href="{{URL::to('siswa/create')}}" style="margin:2px;" class="button button-primary"><i class="fa fa-plus-circle"></i></a>
					@endif
					@if (Auth::user()->hasAnyRole('Tambah Semester'))
						<a href="#popup1" title="Tambah semester" style="margin:2px;" class="button button-danger"><i class="fa fa-plus"></i></a>
					@endif
		    <input name="search" type="text" class="form-control" style="margin:2px;" placeholder="Masukan Nama Siswa atau Sekolah" aria-label="Masukan Nama Siswa" aria-describedby="basic-addon2">
	        <div class="input-group-append ">
	        	<button class="button button-white" style="margin:2px;" type="submit">Search</button>
				<a title="Download" href="{{ route('export.siswa',['type'=>'xls']) }}" class="button button-primary" style="margin:2px;" target="_blank"><i class="fa fa-download"></i></a>
				<div class="input-group-append">
    				<a data-toggle="modal" data-target="#importExcel" title="Import" href="{{ route('export.siswa',['type'=>'xls']) }}" class="button button-primary" style="margin:2px;" target="_blank"><i class="fa fa-file-import"></i></a>
				</div>
	        </div>
    	</div>
		{!! Form::close() !!}

		 <!-- Import Excel -->
		 <div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form method="post" action="/siswa/import" enctype="multipart/form-data">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
						</div>
						<div class="modal-body">

							{{ csrf_field() }}

							<label>Pilih file excel</label>
							<div class="form-group">
								<input type="file" name="file" required="required">
							</div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Import</button>
						</div>
					</div>
				</form>
			</div>
		</div>

	    {{ Form::open(array('url' => 'siswa/reset', 'files' => true, 'method' => 'get')) }}
	    <div id="popup1" class="overlay">
			<div class="popup">
				<h2>Verifikasi</h2>
				<a class="close" href="#">&times;</a>
				<div class="content">
					Silahkan masukan kode untuk melanjutkan proses ini.
				</div>
		        <input name="kode" id="kode" type="text" class="form-control" style="margin:2px;" placeholder="Masukan Kode untuk Menambah Semester" aria-label="Masukan Kode untuk Menambah Semester" aria-describedby="basic-addon2">
		        <button class="button button-green" style="margin:2px;" type="submit">Proses</button>
			</div>
		</div>
		{!! Form::close() !!}

      <div class="card-body p-0 pb-3 text-center">
        <table class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="border-0" style="width:3px;">No</th>
              <th scope="col" class="border-0">Sekolah</th>
              <th scope="col" class="border-0">NPM</th>
              <th scope="col" class="border-0">Nama</th>
              <th scope="col" class="border-0">Program</th>
              <th scope="col" class="border-0">Tahun Masuk</th>
              <th scope="col" class="border-0" style="width:80px;">Kelas</th>
              <th scope="col" class="border-0">Semester</th>
              <th scope="col" class="border-0">No Urut</th>
              <th scope="col" class="border-0">Status</th>
              <th scope="col" class="border-0">Foto</th>
              <th scope="col" class="border-0" style="width:200px;">Actions</th>
            </tr>
          </thead>
          <tstatus>
		   @foreach($student as $i=>$students)
			    <div class="lightbox-target" id="{{{ $students->id }}}">
				   <img src="{{URL::asset('images/siswa/'.$students->foto)}}"/>
				   <a class="lightbox-close" href="#"></a>
				</div>
		     	<tr>
           		 <td>{{ ($student->currentpage()-1) * $student->perpage() + $i + 1 }}</td>
		         <td> {{ $students->nama_sekolah }} </td>
		         <td> {{ $students->npm }} </td>
		         <td> {{ $students->nama_siswa }} </td>
		         <td> {{ $students->nama }} </td>
		         <td> {{ $students->tahun }} </td>
		         <td> {{ $students->kelas }} {{ $students->subkelas }} </td>
		         <td> {{ $students->semester }} </td>
		         <td> {{ $students->id }} </td>
				 <td>
					@if($students->status == "Aktif")
						<span title="Aktif" class="btn btn-success btn-sm">{{ $students->status }}</span>
					@elseif($students->status == "Tidak Aktif")
						<span title="Calon"class="btn btn-danger btn-sm">Tidak Aktif</span>
					@else
					<span title="Belum Tuntas" class="btn btn-info btn-sm">{{ $students->status }}</span>
					@endif
				 </td>
		         <td>
			 		<a class="lightbox" href="#{{{ $students->id }}}">
					   <img style="width:30px; height: 30px" src="{{URL::asset('images/siswa/thumbs/'.$students->foto)}}"/>
					</a> 
				 </td>
		      	<td>
					<a title="Edit" class="btn btn-warning btn-sm" href='{{URL::action("admin\SiswaController@edit",array($students->id))}}'><i class="fa fa-edit fa-xs" style="color: white"></i></a>
					
					<a title="Detail" class="btn btn-info btn-sm" href='{{URL::action("admin\SiswaController@show",array($students->id))}}'><i class="fa fa-eye fa-xs"></i></a>

					<a title="Bayar" class="btn btn-success btn-sm" href='{{URL::action("admin\SiswaController@payment",array($students->id))}}'><i class="far fa-money-bill-alt"></i></a>

					@if( ($students->status == 'Tidak Aktif') )
					@elseif( ($students->semester > 1) )
					@else
					<a title="Pengembalian" class="btn button-primary btn-sm" href='{{URL::action("admin\UangKeluarController@create",array($students->id))}}'><i class="fas fa-undo"></i></a>
					@endif

					@if (Auth::user()->hasAnyRole('Delete Siswa'))
					<form class="btn btn-danger btn-sm" id="delete_siswa{{$students->id}}" action='{{URL::action("admin\SiswaController@destroy",array($students->id))}}' method="POST">
							<input type="hidden" name="_method" value="delete">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<a title="Delete" href="#" onclick="document.getElementById('delete_siswa{{$students->id}}').submit();"><i class="fa fa-trash fa-xs" style="color: white"></i></a>
					</form>
					@endif
					</td>	         
		     	</tr>
			   @endforeach
		</tstatus>
        </table>
{!! $student->render() !!}
      </div>
    </div>
  </div>
</div>
@endsection