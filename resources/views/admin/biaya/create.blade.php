@extends('admin.admin')

@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <div class="main-content-container container-fluid px-4">
  <!-- Page Header -->
  <div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
      <h3 class="page-title">Data Biaya</h3>
    </div>
  </div>
  <!-- End Page Header -->
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <!-- Add New Post Form -->
      <div class="card card-small mb-3">
        <div class="card-body">
          {{ Form::open(array('url' => 'biaya/create', 'files' => true, 'method' => 'post')) }}
          <table class="table table-striped table-hover">

            <tr>
              <div class="input-group mb-3">
                <div class="btn btn-white" style="width:15%">Tahun</div>
                {{ Form::text("thn", '',['class' => 'form-control required', 'placeholder' => 'Masukan Tahun']) }}
                <div class="input-group-append">
                </div>
              </div>
            </tr>

            <tr>
              <div class="col-md-4 input-group mb-1">
                <div class="btn btn-white" style="width:50%">Kode Penerimaan</div>
                {{ Form::select('id_kode', $jenis_bayar, 'null',['class' => 'form-control', 'required', 'value'=>'']) }}
                <div class="input-group-append">
                </div>
              </div>
            </tr>

            <tr>
              <div class="input-group mb-3">
                <div class="btn btn-white" style="width:15%">Jumlah</div>
                {{ Form::text("jumlah", '',['class' => 'form-control required', 'placeholder' => 'Masukan Jumlah']) }}
                <div class="input-group-append">
                </div>
              </div>
            </tr>
          
          </table>
          <div class='form-group'>
            <div class='col-md-4 col-md-offset-2'>
              <button class='btn btn-primary' type='submit' name='save' id='save'><span class='glyphicon glyphicon-save'></span> Save</button>
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