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
        <b>REKAPITULASI PENERIMAAN HARIAN</b>
    </div>
    <br>
    {!! Form::open(['method'=>'GET','url'=>'/search_rekap','role'=>'search'])  !!}
		<div class=" col-md-4 input-group mb-1 px-0" style="float: left;">
       	 <input name="search" type="date" style="margin:2px;" class="form-control" placeholder="Masukan Tanggal :" aria-label="Masukan Tanggal" aria-describedby="basic-addon2">
        	<div class="input-group-append">
        		<button class="button button-white" style="margin:2px;" type="submit">Search</button>
            <div class="input-group-append">
          </div>
          </div>
   	</div>
		{!! Form::close() !!}

    {!! Form::open(['method'=>'GET','url'=>'/spp-rekap/invoice','role'=>'search'])  !!}
    <div class=" col-md-4 input-group mb-1 px-0" style="float: left;">
      <input class="form-control required" type="text" name="search" id="search" value="{{$search}}" style="display: none;">
      <button class="button button-white" target="_blank" style="margin:2px;" type="submit"><i class="fa fa-print fa-xs"></i></button>
    </div>
    <!-- <a type="button" title="Print" class="button button-white" target="_blank" style="position: relative; top: 2px" href='{{URL::action("admin\LaporanController@invoice_rekap")}}'><i class="fa fa-print fa-xs"></i></a> -->
    {!! Form::close() !!}

      <!-- <a title="Download Excel" href="{{ route('export.spp-rekap',['type'=>'xls']) }}" class="btn button-primary btn-sm" style="margin:2px;" target="_blank">Export</a> -->
      <div class="card-body p-0 pb-3 text-center">

        <table class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <th rowspan="2" scope="col" class="border-1" style="width:10px; text-align: center;">No</th>
              <th rowspan="2" scope="col" class="border-1">Sekolah</th>
              <th colspan="4" scope="col" class="border-1">Biaya</th>
              <th rowspan="2" scope="col" class="border-1">Jumlah (Rp)</th>
              <th rowspan="2" scope="col" class="border-1">Tunggakan (Rp)</th>
            </tr>
            <tr>
              <th scope="col" class="border-1">Formulir</th>
              <th scope="col" class="border-1">Pendaftaran</th>
              <th scope="col" class="border-1">Pendidikan</th>
              <th scope="col" class="border-1">Lain-lain</th>
            </tr>
          </thead>
          <tstatus>
    	   @foreach($rekap as $i=>$list)
		     	<tr>
		         <td>{{ ($rekap->currentpage()-1) * $rekap->perpage() + $i + 1 }}</td>
		     	   <td>{{ $list->nama_sekolah }}</td>
		         <td>Rp. {{{ number_format($list->total_formulir) }}}</td>	         
		         <td>Rp. {{{ number_format($list->total_register) }}}</td>	         
		         <td>Rp. {{{ number_format($list->total_spp) }}}</td>	         
		         <td>Rp. {{{ number_format(0) }}}</td>	         
		         <td>Rp. {{{ number_format($list->total_formulir +  $list->total_register + $list->total_spp) }}}</td>	
		         <td>Rp. {{{ number_format($list->tunggakan) }}}</td>	         
          @endforeach

          <tr>
            <td colspan="2">Total</td>
            <td>Rp. {{{ number_format($formulirs) }}}</td>
            <td>Rp. {{{ number_format($pendaftarans) }}}</td>
            <td>Rp. {{{ number_format($pendidikans) }}}</td>
            <td>Rp. 0</td>
            <td>Rp. {{{ number_format($total_seluruh) }}}</td>
            <td>Rp. {{{ number_format($tunggkans) }}}</td>
          </tr>

         </tr>
        </tstatus>
      </table>

        <!-- <table style="width:15%; float:left" class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <th style="height:96px" rowspan="2" scope="col" class="border-1">Tunggakan</th>
            </tr>
          </thead>
          <tstatus>
         @if($rekap->isEmpty())
         @else
          @foreach($tunggakan as $i=>$list2)
            <tr>
              <td>Rp. {{{ number_format($list2->tunggakan) }}}</td>	         
            </tr>
          @endforeach
         @endif
		    </tstatus>
        </table> -->

      </div>
    </div>
  </div>
</div>
@endsection