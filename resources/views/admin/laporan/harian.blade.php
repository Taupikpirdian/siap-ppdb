@extends('admin.admin')
@section('content')

<div class="page-header row no-gutters py-4">
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
  
    <div align="center">
			<img style="width:10%" src="{{URL::asset('invoice/image/logo.png')}}">
      <br>
      <br>
        <b>YAYASAN PENDIDIKAN</b>
      <br>
        <b>Jl .....</b>
      <br>
        <b>DAFTAR PENERIMAAN HARIAN</b>
    </div>
    <br>

    {!! Form::open(['method'=>'GET','url'=>'/search_all_siswa','role'=>'search'])  !!}
		<div class=" col-md-5 input-group mb-1 px-0" style="float: left;">
       	 <input name="search" type="date" style="margin:2px;" class="form-control" placeholder="Masukan Nama Siswa atau NPM :" aria-label="Masukan Program" aria-describedby="basic-addon2">
        	<div class="input-group-append">
        		<button class="button button-white" style="margin:2px;" type="submit">Search</button>
            <div class="input-group-append">
          </div>
          </div>
          <b style="position:relative; left: 100%" style="color: blue">Lembar ke:</b> {!! $all_siswa->render() !!}
   	</div>
		{!! Form::close() !!}

    {!! Form::open(['method'=>'GET','url'=>'/all_siswa/invoice','role'=>'search'])  !!}
    <div class=" col-md-4 input-group mb-1 px-0" style="float: left; position: relative; right: 75px">
      <input class="form-control required" type="text" name="search" id="search" value="{{$search}}" style="display: none;">
      <button class="button button-white" target="_blank" style="margin:2px;" type="submit"><i class="fa fa-print fa-xs"></i></button>
    </div>
    <!-- <a title="Print" class="button button-white" target="_blank" style="position: relative; top: 2px" href='{{URL::action("admin\LaporanController@invoice_all_siswa")}}'><i class="fa fa-print fa-xs"></i></a> -->
    {!! Form::close() !!}

      <div class="card-body p-0 pb-3 text-center">
        <table class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <th rowspan="2" scope="col" class="border-1" style="width:10px; text-align: center;">No</th>
              <th rowspan="2" scope="col" class="border-1">Nama Siswa</th>
              <th rowspan="2" scope="col" class="border-1">Sekolah</th>
              <th rowspan="2" scope="col" class="border-1">Kelas</th>
              <th colspan="4" scope="col" class="border-1">Biaya</th>
              <th rowspan="2" scope="col" class="border-1">Jumlah (Rp)</th>
              <th rowspan="2" scope="col" class="border-1">Tunggakan</th>
            </tr>
            <tr>
              <th scope="col" class="border-1">Formulir</th>
              <th scope="col" class="border-1">Pendaftaran</th>
              <th scope="col" class="border-1">Pendidikan</th>
              <th scope="col" class="border-1">Lain-lain</th>
            </tr>
          </thead>
          <tstatus>
        @foreach($all_siswa as $i=>$days)
		     	<tr>
		     	   <td>{{ $i + 1 }}</td>
		         <td>{{ $days->nama_siswa }}</td>
		         <td>{{ $days->nama_sekolah }}</td>
		         <td>{{ $days->kelas }}</td>	 
		         <td>Rp. {{{ number_format($days->form) }}}</td>	         
		         <td>Rp. {{{ number_format($days->register) }}}</td>	         
		         <td>Rp. {{{ number_format($days->has_payment_spp) }}}</td>	         
		         <td>Rp. 0</td>	         
		         <td>Rp. {{{ number_format($days->form+$days->register+$days->has_payment_spp) }}}</td>	         
		         <td>Rp. {{{ number_format(($days->spp_on_tunggakans*$days->semester)-$days->amount_on_tunggakans) }}}</td>	         
           </tr>
        @endforeach
            <tr>
              <td colspan="4">Total</td>
              <td>Rp. {{{ number_format($formulirs) }}}</td>
              <td>Rp. {{{ number_format($pendaftarans) }}}</td>
              <td>Rp. {{{ number_format($pendidikans) }}}</td>
              <td>Rp. 0</td>
              <td>Rp. {{{ number_format($total_seluruh) }}}</td>
              <td>Rp. {{{ number_format($tunggakans) }}}</td>
            </tr>
		      </tstatus>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection