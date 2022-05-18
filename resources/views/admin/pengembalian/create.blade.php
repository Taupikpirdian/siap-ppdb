@extends('admin.admin')

@section('content')
<div class="main-content-container container-fluid px-4">
      <!-- Page Header -->
      <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
        <span class="text-uppercase page-subtitle"> <a href="{{URL::to('/siswa/index')}}"><i class="fa fa-dashboard"></i> Kembali</a></span>
          <h3 class="page-title">Metode Pengembalian</h3>
        </div>
      </div>
      <!-- End Page Header -->
      <!-- Default Light Table -->
      <div class="row">

        <div class="col-lg-12">
          <div class="card card-small mb-4">
            <div class="card-header border-bottom">
              <h6 class="m-0">Metode Pengembalian</h6>
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item p-3">
                <div class="row">
                  <div class="col">
                    <button id="not_class" type="button" class="btn btn-outline-primary">Belum Masuk Kelas</button>
                    <button id="class" type="button" class="btn btn-outline-dark">Sudah Masuk Kelas</button>
                    <br>
                    <br>

                    <!-- Bagian Metode Pengembalian sebelum masuk kelas -->
                    <div id="filter" class="card card-small mb-3">
                      <div class="card-body">
                        {{ Form::open(array('url' => 'uang_keluar/create', 'files' => true, 'method' => 'post')) }}
                        <table class="table table-striped table-hover">

                        <tr>
                          <div class="col-md-4 input-group mb-1" style="display: none; visibility: hidden;">
                            <div class="button button-primary" style="width:50%">Siswa</div>
                            {{ Form::text("siswa_id", $siswa->id,['class' => 'form-control required', 'placeholder' => 'Masukan Nama']) }}
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1">
                            <div class="button button-primary" style="width:40%">Siswa</div>
                            <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$siswa->nama_siswa}}}"  disabled> 
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1">
                            <div class="button button-primary" style="width:40%">Jenis Transaksi</div>
                            {{ Form::select("payment_id", $payments, null,['class' => 'form-control required']) }}
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1" style="display: none; visibility: hidden;">
                            <div class="button button-primary" style="width:40%">Sekolah</div>
                            {{ Form::text("sekolah_id", $siswa->sekolah_id,['class' => 'form-control required', 'placeholder' => 'Masukan Nama Sekolah']) }}
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1">
                            <div class="button button-primary" style="width:40%">Sekolah</div>
                            <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$siswa->nama_sekolah}}}"  disabled> 
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1">
                            <div class="button button-primary" style="width:40%">Tanggal Pengumuman</div>
                            {{ Form::date("tgl_pengumuman", '',['class' => 'form-control required', 'placeholder' => 'Masukan Tanggal Pengumuman']) }}
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1">
                            <div class="button button-primary" style="width:40%">Tanggal Pengambilan</div>
                            {{ Form::date("tgl_pengembalian", '',['class' => 'form-control required', 'placeholder' => 'Masukan Tanggal Pengambilan']) }}
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-4 input-group mb-1" style="display: none; visibility: hidden;">
                            <div class="button button-primary" style="width:50%">Jumlah Bayar</div>
                            {{ Form::text("jumlah", $cost_count,['class' => 'form-control required', 'placeholder' => 'Masukan Jumlah']) }}
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1">
                            <div class="button button-primary" style="width:40%">Jumlah Bayar</div>
                            <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$cost_count}}}"  disabled> 
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        </table>

                        <div class='col-md-5 form-group'>
                          <div class='col-md-4 col-md-offset-2 px-0'>
                            <button class='button button-primary' type='submit' name='save' id='save'><span class='glyphicon glyphicon-save'></span> Save</button>
                          </div>
                        </div>
                        {!! Form::close() !!}
                      </div>
                    </div>

                    <!-- Bagian Metode Pengembalian sesudah masuk kelas -->

                    <div id="filter2" class="card card-small mb-3">
                      <div class="card-body">
                        {{ Form::open(array('url' => 'uang_keluar/create_class', 'files' => true, 'method' => 'post')) }}
                        <table class="table table-striped table-hover">

                        <tr>
                          <div class="col-md-4 input-group mb-1" style="display: none; visibility: hidden;">
                            <div class="button button-primary" style="width:50%">Siswa</div>
                            {{ Form::text("siswa_id", $siswa->id,['class' => 'form-control required', 'placeholder' => 'Masukan Nama']) }}
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1">
                            <div class="button button-primary" style="width:40%">Siswa</div>
                            <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$siswa->nama_siswa}}}"  disabled> 
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1">
                            <div class="button button-primary" style="width:40%">Jenis Transaksi</div>
                            {{ Form::select("payment_id", $payments, null,['class' => 'form-control required']) }}
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1" style="display: none; visibility: hidden;">
                            <div class="button button-primary" style="width:40%">Sekolah</div>
                            {{ Form::text("sekolah_id", $siswa->sekolah_id,['class' => 'form-control required', 'placeholder' => 'Masukan Nama Sekolah']) }}
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1">
                            <div class="button button-primary" style="width:40%">Sekolah</div>
                            <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$siswa->nama_sekolah}}}"  disabled> 
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1">
                            <div class="button button-primary" style="width:40%">Tanggal Kegiatan Belajar</div>
                            {{ Form::date("tgl_masuk", '',['class' => 'form-control required', 'placeholder' => 'Masukan Tanggal Masuk']) }}
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1">
                            <div class="button button-primary" style="width:40%">Tanggal Pengambilan</div>
                            {{ Form::date("tgl_pengembalian", '',['class' => 'form-control required', 'placeholder' => 'Masukan Tanggal Pengambilan']) }}
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-4 input-group mb-1" style="display: none; visibility: hidden;">
                            <div class="button button-primary" style="width:50%">Jumlah Bayar</div>
                            {{ Form::text("jumlah", $cost_count,['class' => 'form-control required', 'placeholder' => 'Masukan Jumlah']) }}
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

                        <tr>
                          <div class="col-md-5 input-group mb-1">
                            <div class="button button-primary" style="width:40%">Jumlah Bayar</div>
                            <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$cost_count}}}"  disabled> 
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr>

              <!--           <tr>
                          <div class="col-md-4 input-group mb-1">
                            <div class="button button-primary" style="width:50%">Denda</div>
                            {{ Form::text("denda", '',['class' => 'form-control required', 'placeholder' => 'Masukan Denda']) }}
                            <div class="input-group-append">
                            </div>
                          </div>
                        </tr> -->

                        </table>

                        <div class='col-md-5 form-group'>
                          <div class='col-md-4 col-md-offset-2 px-0'>
                            <button class='button button-primary' type='submit' name='save' id='save'><span class='glyphicon glyphicon-save'></span> Save</button>
                          </div>
                        </div>
                        {!! Form::close() !!}
                      </div>
                    </div>

                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- End Default Light Table -->
    </div>
@endsection

@section('js')
<script>
  //Pertama sembunyikan elemen class gambar
  $('#filter').hide();        
  $('#filter2').hide();        
  
  //Ketika elemen class tampil di klik maka elemen class gambar tampil
  $('#not_class').click(function(){
      $('#filter2').hide();
      $('#filter').show();
  });

  $('#class').click(function(){
      $('#filter2').show();
      $('#filter').hide();
  });
  
  //Ketika elemen class sembunyi di klik maka elemen class gambar sembunyi
  $('#hide').click(function(){
      //Sembunyikan elemen class gambar
      $('#filter').hide();        
  });
</script>
@endsection