@extends('admin.admin')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <div class="main-content-container container-fluid px-4">
  <!-- Page Header -->
  <div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
      <h3 class="page-title">Calon Siswa</h3>
    </div>
  </div>
  <!-- End Page Header -->
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <!-- Add New Post Form -->
      <div class="card card-small mb-3">
        <div class="card-body">
          {{ Form::open(array('url' => 'candidate/create', 'files' => true, 'method' => 'post')) }}
          <table class="table table-striped table-hover">
          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:50%">Sekolah</div>
              {{ Form::select('sekolah_id', $scholls, null,['class' => 'form-control', 'required' => 'required']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:50%">Tahun</div>
              {{ Form::select('thn_id',$years, null,['class' => 'form-control', 'placeholder' => 'Pilih Tahun', 'required' => 'required']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:50%">Program</div>
              {{ Form::select('program_id',$progs, null,['class' => 'form-control', 'placeholder' => 'Pilih Program', 'required' => 'required']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:50%">Nama Pendaftar</div>
              {{ Form::text("nama", '',['class' => 'form-control required', 'placeholder' => 'Masukan Nama Pendaftar', 'required' => 'required']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:50%">Tanggal Lahir</div>
              {{ Form::date("tgl_lahir", '',['class' => 'form-control required', 'placeholder' => 'Masukan Tanggal Lahir', 'required' => 'required']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:50%">Tanggal Bayar</div>
              {{ Form::date("tgl_bayar", Carbon\Carbon::now(),['class' => 'form-control required', 'placeholder' => 'Masukan Tanggal Bayar', 'required' => 'required']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:50%">Jenis Pembayaran</div>
              {{ Form::select('payment_id',$payments, null,['class' => 'form-control', 'placeholder' => 'Pilih Jenis Pembayaran', 'required' => 'required']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:50%">Biaya</div>
              {{ Form::text('cost_id', '',['class' => 'form-control', 'placeholder' => 'Pilih Biaya', 'required' => 'required']) }}
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
      <!-- / Add New Post Form -->
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script>
  $(function() {
    $(".datepicker4").datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: '-80:+0',
    dateFormat: "yy-mm-dd"
    });
    $(".datepicker2").datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: '-80:+0',
    dateFormat: "yy-mm-dd"
    });
    $(".datepicker3").datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: '-80:+0',
    dateFormat: "yy-mm-dd"
    });
  });

</script>
@endsection