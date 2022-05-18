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
        <b>REKAPITULASI TUNGGAKAN SPP SISWA</b>
    </div>
    <br>

    {!! Form::open(['method'=>'GET','url'=>'/search_tunggakan','role'=>'filter'])  !!}
              <div class="col-md-11 mb-3 px-0">
                  <div class="col-md-3" style="position: relative; top: 2px; float: left;">
                      <select name="school" id="school" class="form-control">
                        <!-- Menampilkan data dari tabel merk untuk dijadikan list dropdown -->
                      <option value="" disabled selected>Pilih Sekolah</option>
                      @foreach($scholls as $i=>$list)
                          <option value="{{ $list->id }}" >{{ $list->nama_sekolah }}</option>
                      @endforeach
                      </select>
                  </div>

                  <div class="col-md-2" style="position: relative; top: 2px; float: left;">
                      <select name="class" id="class" class="form-control">
                        <!-- Menampilkan data dari tabel merk untuk dijadikan list dropdown -->
                          <option value="" disabled selected>Pilih Kelas</option>
                      @foreach($class as $i=>$room)
                          <option value="{{ $room->kelas }}" >{{ $room->kelas }}</option>
                      @endforeach
                      </select>
                  </div>

                  <div class="input-group-append"  style="float: left; margin-right: 0px">
                    <button title="Filter" class="button button-white" style="position: relative; top: 2px" type="submit"><i class="fa fa-filter"></i></button>
                    <!-- <a title="Print" class="button button-white" target="_blank" style="position: relative; top: 2px" href='{{URL::action("admin\LaporanController@invoice_tunggakan")}}'><i class="fa fa-print fa-xs"></i></a> -->
                  </div>
         </div>
        {!! Form::close() !!}  
        <!-- mulai disini -->

        {!! Form::open(['method'=>'GET','url'=>'/tunggakan/invoice','role'=>'search'])  !!}
        <div class=" col-md-4 input-group mb-1 px-0" style="float: left;">
          <input class="form-control required" type="text" name="school" value="{{$school}}" style="display: none;">
          <input class="form-control required" type="text" name="class" value="{{$classes}}" style="display: none;">
          <button class="button button-white" target="_blank" style="margin-top:-15px; margin-left:5px" type="submit"><i class="fa fa-print fa-xs"></i></button>
        </div>
        <!-- <a type="button" title="Print" class="button button-white" target="_blank" style="position: relative; top: 2px" href='{{URL::action("admin\LaporanController@invoice_rekap")}}'><i class="fa fa-print fa-xs"></i></a> -->
        {!! Form::close() !!}

      <br>
      <br>
      <div class="card-body p-0 pb-3 text-center">
        <table class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <th rowspan="2" scope="col" class="border-1" style="width:10px; text-align: center;">No</th>
              <th rowspan="2" scope="col" class="border-1">NIS</th>
              <th rowspan="2" scope="col" class="border-1">Nama Siswa</th>
              <th rowspan="2" scope="col" class="border-1">Sekolah</th>
              <th colspan="2" scope="col" class="border-1">Biaya SPP</th>
              <th rowspan="2" scope="col" class="border-1">Tunggakan</th>
            </tr>
            <tr>
              <th scope="col" class="border-1">Yang harus dibayar</th>
              <th scope="col" class="border-1">Yang sudah dibayar</th>
            </tr>
          </thead>
          <tstatus>
    	   @foreach($tunggakan as $i=>$list)
		     	<tr>
		     	   <td>{{ ($tunggakan->currentpage()-1) * $tunggakan->perpage() + $i + 1 }}</td>
		         <td>{{ $list->npm }}</td>
		         <td>{{ $list->nama_siswa }}</td>
		         <td>{{ $list->nama_sekolah }}</td>	         
		         <td>Rp. {{{ number_format($list->semester*$list->spp) }}}</td>	         
		         <td>Rp. {{{ number_format($list->payment_amount) }}}</td>	         
		         <td>Rp. {{{ number_format(($list->semester*$list->spp)-($list->payment_amount)) }}}</td>	         
		     	</tr>
  		   @endforeach
          <tr>
		     	   <td colspan="4">Jumlah</td>
		     	   <td>Rp. {{{ number_format($spps) }}}</td>
             <td>Rp. {{{ number_format($has_payment_spp) }}}</td>
             <td>Rp. {{{ number_format($mod) }}}</td>
		     	</tr>
		</tstatus>
        </table>
  {!! $tunggakan->render() !!}
      </div>
    </div>
  </div>
</div>
@endsection