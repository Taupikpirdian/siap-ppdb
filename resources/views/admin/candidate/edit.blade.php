@extends('admin.admin')

@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <div class="main-content-container container-fluid px-4">
  <!-- Page Header -->
  <div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
      <h3 class="page-title">Edit Data Calon Siswa</h3>
    </div>
  </div>
  <!-- End Page Header -->
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <!-- Add New Post Form -->
      <div class="card card-small mb-3">
        <div class="card-body">
        {!! Form::model($candidate,['files'=>true,'method'=>'put','action'=>['admin\CandidateController@update',$candidate->id]]) !!}
          <table class="table table-striped table-hover">

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:35%">Nama Pendaftar</div>
              {{ Form::text("nama_siswa", null,['class' => 'form-control required', 'placeholder' => 'Masukan Nama Pendaftar']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:35%">Tanggal Lahir</div>
              {{ Form::date("tgl_lahir", null,['class' => 'form-control required', 'placeholder' => 'Masukan Tanggal Lahir']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:35%">Sekolah</div>
              {{ Form::select('sekolah_id', $scholl, null,['class' => 'form-control sekolah']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:35%">Tahun Masuk</div>
              {{ Form::select('thn_id', $year, null,['class' => 'form-control tahun prog_thn']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:35%">Program</div>
              {{ Form::select('program_id', $prog, null,['class' => 'form-control program biaya']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          </table>

          <div class='col-md-5  form-group'>
            <div class='col-md-4 md-2 px-0'>
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

  // Ajax Pembayaran
    $('#thn_masuk').on('change', function(e){
        console.log(e);
        var year_id = e.target.value;
        $.get('/json-payment?year_id=' + year_id,function(data) {
          console.log(data);
          $('#payments').empty();
          $('#payments').append('<option value="0" disable="true" selected="true">Pilih Pembayaran</option>');

          $('#amount').empty();
          $('#amount').append('<option value="0" disable="true" selected="true">Pilih Biaya</option>');

          $.each(data, function(index, paymentsObj){
            $('#payments').append('<option value="'+ paymentsObj.id +'">'+ paymentsObj.name +'</option>');
          })
        });
      });

      $('#payments').on('change', function(e){
        console.log(e);
        var payment_id = e.target.value;
        $.get('/json-cost?payment_id=' + payment_id,function(data) {
          console.log(data);
          $('#amount').empty();
          $('#amount').append('<option value="0" disable="true" selected="true">Select Biaya</option>');

          $.each(data, function(index, amountObj){
            $('#amount').append('<option value="'+ amountObj.id +'">'+ amountObj.name +'</option>');
          })
        });
      });

</script>

<script type="text/javascript">
  $('.sekolah').on('change',function(e){
    var sekolah_id = e.target.value;
    console.log(sekolah_id);
    $.get('/ajax-sekolah-tahun?sekolah_id='+sekolah_id, function(data){
    console.log(data);
      $('.tahun').empty();
      $('.tahun').append('<option value="0" disable="true" selected="true">Pilih Tahun Masuk</option>');
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
      $('.payment').append('<option value="0" disable="true" selected="true">Pilih Program</option>');
      $.each(data,function(index,subcatObj){
        $('.payment').append('<option value="'+subcatObj.id+'">'+subcatObj.name+'</option>');
      });
    });
  });
</script>
<script type="text/javascript">
  $('.prabayar').on('change',function(e){
    var payment_id = e.target.value;
    console.log(payment_id);
    $.get('/ajax-payment-biaya?payment_id='+payment_id, function(data){
    console.log(data);
      $('.cost').empty();
      $('.cost').append('<option value="0" disable="true" selected="true">Pilih Program</option>');
      $.each(data,function(index,subcatObj){
        $('.cost').append('<option value="'+subcatObj.id+'">'+subcatObj.name+'</option>');
      });
    });
  });
</script>
@endsection