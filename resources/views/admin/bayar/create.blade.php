@extends('admin.admin')

@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <div class="main-content-container container-fluid px-4">
  <!-- Page Header -->
  <div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
      <h3 class="page-title">Input Data Biaya</h3>
    </div>
  </div>
  <!-- End Page Header -->
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <!-- Add New Post Form -->
      <div class="card card-small mb-3">
        <div class="card-body">
          {{ Form::open(array('url' => 'cost/create', 'files' => true, 'method' => 'post')) }}
          <table class="table table-striped table-hover">

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:50%">Sekolah</div>
              {{ Form::select('sekolah_id', $scholls, null,['class' => 'form-control sekolah']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:50%">Tahun</div>
              {{ Form::select('thn_id', $years, null,['class' => 'form-control tahun', 'placeholder' => 'Pilih Tahun']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:50%">Program</div>
              {{ Form::select('program_id',$progs, null,['class' => 'form-control', 'placeholder' => 'Pilih Program']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:50%">Jenis Pembayaran</div>
              {{ Form::select('payment_id',$payments, null,['class' => 'form-control payment', 'placeholder' => 'Pilih Jenis Pembayaran']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:50%">Biaya</div>
              {{ Form::text("name", '',['class' => 'form-control required', 'placeholder' => 'Masukan Biaya']) }}
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
<!-- <script type="text/javascript">
  $('.sekolah').on('change',function(e){
    var sekolah_id = e.target.value;
    console.log(sekolah_id);
    $.get('/ajax-sekolah-tahun?sekolah_id='+sekolah_id, function(data){
    console.log(data);
      $('.tahun').empty();
      $('.tahun').append('<option value="0" disable="true" selected="true">Pilih Tahun</option>');
      $.each(data,function(index,subcatObj){
        $('.tahun').append('<option value="'+subcatObj.id+'">'+subcatObj.tahun+'</option>');
      });
    });
  });
</script>
<script type="text/javascript">
  $('.prog_thn').on('change',function(e){
    var thn_id = e.target.value;
    console.log(thn_id);
    $.get('/ajax-thn-program?thn_id='+thn_id, function(data){
    console.log(data);
      $('.program').empty();
      $('.program').append('<option value="0" disable="true" selected="true">Pilih Program</option>');
      $.each(data,function(index,subcatObj){
        $('.program').append('<option value="'+subcatObj.id+'">'+subcatObj.nama+'</option>');
      });
    });
  });
</script>
<script type="text/javascript">
  $('.biaya').on('change',function(e){
    var program_id = e.target.value;
    console.log(program_id);
    $.get('/ajax-program-bayar?program_id='+program_id, function(data){
    console.log(data);
      $('.payment').empty();
      $('.payment').append('<option value="0" disable="true" selected="true">Pilih Pembayaran</option>');
      $.each(data,function(index,subcatObj){
        $('.payment').append('<option value="'+subcatObj.id+'">'+subcatObj.name+'</option>');
      });
    });
  });
</script> -->
@endsection
