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
    <h3 class="page-title">Bukti Pengeluaran</h3>
  </div>
</div>
{{-- menampilkan error validasi --}}
   @if ($errors->any())
   <div class="alert alert-danger alert-block">
    <button type="button" style="color:#fff;" class="close" data-dismiss="alert">×</button> 
            @foreach ($errors->all() as $error)
                <strong style="font-family: Palatino; font-size: 12px">{{ $error }}</strong>
            @endforeach
        
    </div>
  @endif

<div class="row">
  <div class="col">
    <div class="card card-small mb-5">
      <div class="card-header border-bottom">
        <div class="pull-right">
         <div class='form-group clearfix'>
          <div class='col-md-12 px-0'>

            {!! Form::open(['method'=>'GET','url'=>'searchbuktipengeluaran','role'=>'search'])  !!}
            <div class=" col-md-6 input-group px-0 " >
            @if (Auth::user()->hasAnyRole('Create Bukti Pengeluaran'))
                <a title="Create" href="{{URL::to('/buktipengeluaran/create')}}"  style="margin:2px;" class="button button-primary"><i class="fa fa-plus-circle"></i></a>
            @endif 
                <input name="search" type="text" style="margin:2px;" class="form-control" placeholder="Masukan ID Bukti Pengeluaran..." aria-label="Masukan Bukti Pengeluaran" aria-describedby="basic-addon2">
                <div class="input-group-append">
                <button class="button button-white" style="margin:2px;" type="submit">Search</button>
                <a title="Create" href="{{URL::to('/buktipengeluaran/index')}}" style="margin:2px;" class="button button-primary"><i class="fas fa-sync"></i></a>
                <a title="Buka Filter" id="show" style="margin:2px;" class="button button-primary"><i class="fa fa-filter" style="color: white"></i></a>
                </div>
            {!! Form::close() !!}
            </div>     
          </div>

          <div id="filter" class='col-md-12 px-0'>
            {!! Form::open(['method'=>'GET','url'=>'filterbuktipengeluaran','role'=>'filter'])  !!}          
            <div class="col-md-6 mb-3 px-0" style="width: 100%">
              <div class="col-md-4" style="position: relative; right: 3%; top: 2px; float: left;">
                  <select name="filter" id="filter" class="form-control">
                    <!-- Menampilkan data dari tabel merk untuk dijadikan list dropdown -->
                      <option value="" disabled selected>Pilih Status</option>
                      <option value="1">Tuntas</option>
                      <option value="2">Belum Tuntas</option>
                  </select>
              </div>

              <div class="col-md-4" style="position: relative; right: 8%; top: 2px; float: left;">
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

              <div class="col-md-3" style="position: relative; right: 13%; top: 2px; float: left;">
                  <input name="year" type="text" class="form-control" placeholder="Tahun" aria-label="Masukan Tahun" aria-describedby="basic-addon2">
              </div>

              <div class="input-group-append">
                <button class="button button-white" style="position: relative; right: 183%; top: 2px" type="submit">Filter</button>

                <a id="hide" title="Tutup" class="button button-white" style="position: relative; right: 175%; top: 2px"><i class="fa fa-filter"></i></a>
              </div>

            {!! Form::close() !!}  
            </div>
          </div>
          
        </div>
      </div>
   
      
      <div class="card-body p-0 pb-3 text-center">
        <table class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="border-0">No</th>
              <th scope="col" class="border-0" style="width:60px;">Kode Pengeluaran</th>
              <th scope="col" class="border-0">Nama Pengeluaran</th>
              <th scope="col" class="border-0">Tanggal Pengeluaran</th>
              <th scope="col" class="border-0" style="width:110px;">Jumlah Pengeluaran</th>
              <th scope="col" class="border-0" style="width:110px;">Jumlah Penyaluran</th>
              <th scope="col" class="border-0" style="width:40px;">Status</th>
              <th scope="col" class="border-0" style="width:100px;">Actions</th>
            </tr>
          </thead>
          <tstatus>
            @foreach($bukti as $i=>$buktipengeluarans)
          <tr>
             <td>{{ ($bukti->currentpage()-1) * $bukti->perpage() + $i + 1 }}</td>
             <td> {{ $buktipengeluarans->kode_pengeluaran }} </td>
             <td> {{ $buktipengeluarans->nama_pengeluaran }} </td>
             <td> {{ Carbon\Carbon::parse($buktipengeluarans->tgl_keluar)->formatLocalized('%d %B %Y')}} </td>
             <td> Rp. {{ number_format($buktipengeluarans->jumlah_pengeluarans) }} </td>
             <td> Rp. {{ number_format($buktipengeluarans->jumlah_pengeluaran) }} </td>
             
             <td> @if($buktipengeluarans->status_penyaluran == 1)
                    <span title="Tuntas" class="btn btn-success btn-sm"><i class="fas fa-check"></i></span>
                  @elseif($buktipengeluarans->status_penyaluran == 2)
                    <span title="Berlebihh"class="btn btn-danger btn-sm"><i class="far fa-exclamation-circle"></i> </span>
                  @else
                  <span title="Belum Tuntas" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></span>
                  @endif
             </td>
             <td>
             @if (Auth::user()->hasAnyRole('Delete Bukti Pengeluaran'))
              <form title="Delete" class="btn btn-danger btn-sm" id="delete_buktis{{$buktipengeluarans->id}}" action='{{URL::action("admin\BuktiPengeluaranController@destroy",array($buktipengeluarans->id))}}' method="POST">
                  <input type="hidden" name="_method" value="delete">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <a href="#" onclick="document.getElementById('delete_buktis{{$buktipengeluarans->id}}').submit();"><i class="fa fa-trash fa-xs" style="color: white"></i></a>
              </form>
            @endif
             <a title="Download" target="_blank"  href="{{URL::to('/files/file/'.$buktipengeluarans->file)}}"  class="btn btn-primary btn-sm"><i class="fa fa-download fa-xs"> </i></a>
            </td>          
          </tr>
         @endforeach
    </tstatus>
        </table>
          {{ $bukti->links() }}
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