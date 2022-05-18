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
    <h3 class="page-title">List Data Calon Siswa</h3>
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
		{!! Form::open(['method'=>'GET','url'=>'/searchcalon','role'=>'search'])  !!}
		<div class=" col-md-5 input-group mb-1 px-0">
       	 <input name="search" type="text" style="margin:2px;" class="form-control" placeholder="Masukan Nama Sekolah" aria-label="Masukan Nama Sekolah" aria-describedby="basic-addon2">
        	<div class="input-group-append">
        		<button class="button button-white" style="margin:2px;" type="submit">Search</button>
	        	<a title="Buka Filter" id="show" style="margin:2px;" class="button button-primary"><i class="fa fa-filter" style="color: white"></i></a>
        	</div>
    	</div>
		{!! Form::close() !!}

		    <div id="filter" class='col-md-12 px-0' style="margin-bottom: 40px; margin-left: 2.5%">

            {!! Form::open(['method'=>'GET','url'=>'filtercalon','role'=>'filter'])  !!}
              <div class="col-md-11 mb-3 px-0" style="width: 100%;">
                  <div class="input-group-append" style="float: left; margin-right: 5px">
<!--                     <a title="Export" href="{{ route('export.calon',['type'=>'xls']) }}" class="button button-white" style="position: relative; right: 22px; top: 2px"><i class="fa fa-download"></i></a> -->
                  </div>

                  <div class="col-md-3" style="position: relative; right: 3.5%; top: 2px; float: left;">
                      <input name="nama" type="text" class="form-control" placeholder="Nama Calon Siswa" aria-label="Masukan Tahun" aria-describedby="basic-addon2">
                  </div>

                  <div class="col-md-2" style="position: relative; right: 6%; top: 2px; float: left;">
                      <select name="bulan" id="month" class="form-control">
                        <!-- Menampilkan data dari tabel merk untuk dijadikan list dropdown -->
                          <option value="" disabled selected>Pilih Bulan</option>
                          <option value="1" >Januari</option>
                          <option value="2" >Februari</option>
                          <option value="3" >Maret</option>
                          <option value="4" >April</option>
                          <option value="5" >Mei</option>
                          <option value="6" >Juni</option>
                          <option value="7" >Juli</option>
                          <option value="8" >Agustus</option>
                          <option value="9" >September</option>
                          <option value="10" >Oktober</option>
                          <option value="11" >November</option>
                          <option value="12" >Desember</option>
                      </select>
                  </div>

                  <div class="col-md-2" style="position: relative; right: 8.5%; top: 2px; float: left;">
                      <input name="year" type="text" class="form-control" placeholder="Tahun" aria-label="Masukan Tahun" aria-describedby="basic-addon2">
                  </div>

                  <div class="input-group-append"  style="float: left; margin-right: 0px">
                    <button class="button button-white" style="position: relative; right: 90.5%; top: 2px" type="submit">Filter</button>
                    <a id="hide" title="Tutup" class="button button-white" style="position: relative; right: 86%; top: 2px"><i class="fa fa-filter"></i></a>
                  </div>
            </div>
         </div>
            {!! Form::close() !!}  
      
      <div class="card-body p-0 pb-3 text-center">
        <table class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="border-0" style="width:10px;">No</th>
              <th scope="col" class="border-0">Tahun Masuk</th>
              <th scope="col" class="border-0">Sekolah</th>
              <th scope="col" class="border-0">Program</th>
              <th scope="col" class="border-0">Nama</th>
              <th scope="col" class="border-0">Tanggal Lahir</th>
              <th scope="col" class="border-0">Tanggal Daftar</th>
              <th scope="col" class="border-0">Status</th>
              <th scope="col" class="border-0" style="width:16%;">Actions</th>
            </tr>
          </thead>
          <tstatus>
		   @foreach($candidate as $i=>$candidates)
		     	<tr>
		     	 <td>{{ ($candidate->currentpage()-1) * $candidate->perpage() + $i + 1 }}</td>
             <td> {{ $candidates->tahun }} </td>
             <td> {{ $candidates->nama_sekolah }} </td>
		         <td> {{ $candidates->nm_prog }} </td>
		         <td> {{ $candidates->nama_siswa }} </td>
		         <td> {{Carbon\Carbon::parse($candidates->tgl_lahir)->formatLocalized('%d %B %Y')}} </td>
             <td> {{Carbon\Carbon::parse($candidates->created_at)->formatLocalized('%d %B %Y')}} </td>
		         <td> 
                  <span title="Calon"class="btn btn-danger btn-sm">{{ $candidates->status }}</span>
             </td>
		         <td>

                @if (Auth::user()->hasAnyRole('Details Calon'))
                  <a title="Detail" class="btn btn-info btn-sm" href='{{URL::action("admin\CalonController@show",array($candidates->id))}}'><i class="fas fa-eye fa-xs"></i></a>
                @endif

    				</td>	         
		     	</tr>
			   @endforeach
		</tstatus>
        </table>
				{!! $candidate->render() !!}
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
  //Pertama sembunyikan elemen class gambar
  $('#filter').hide();        
  //Ketika elemen class tampil di klik maka elemen class gambar tampil
  $('#show').click(function(){
      $('#filter').show();
  });
  //Ketika elemen class sembunyi di klik maka elemen class gambar sembunyi
  $('#hide').click(function(){
      //Sembunyikan elemen class gambar
      $('#filter').hide();        
  });
</script>
@endsection