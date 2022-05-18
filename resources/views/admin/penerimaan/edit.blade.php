@extends('admin.admin')
@section('content')


<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <div class="main-content-container container-fluid px-4">
  <!-- Page Header -->
  <div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
      <h3 class="page-title">Edit Penerimaan</h3>
    </div>
  </div>
  <!-- End Page Header -->
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <!-- Add New Post Form -->
      <div class="card card-small mb-3">
        <div class="card-body">


		{!! Form::model($penerimaans,['method'=>'put', 'files'=> 'true', 'action'=>['admin\PenerimaanController@update',$penerimaans->id]]) !!}
          <table class="table table-striped table-hover">

          <tr>
            <div class="col-md-4 input-group mb-1">
              <div class="button button-primary" style="width:54%">Kode Penerimaan</div>
              {{ Form::text("kode_penerimaan", null,['class' => 'form-control required', 'placeholder' => ' ']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>
          <tr>
            <div class="col-md-4 input-group mb-1">
              <div class="button button-primary" style="width:54%">Tanggal Penerimaan</div>
              {{ Form::date("tgl_terima", Carbon\Carbon::now(),['class' => 'form-control required', 'placeholder' => ' ']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>
          <tr>
            <div class="col-md-4  input-group mb-1">
              <div class="button button-primary" style="width:54%">Asal Penerimaan</div>
              {{ Form::text("asal_penerimaan", null,['class' => 'form-control required', 'placeholder' => ' ']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>
          <tr>
            <div class=" col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:43%">Nama Penerima</div>
              {{ Form::text("nama_penerimaan", null,['class' => 'form-control required', 'placeholder' => ' ']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>
          <tr>
            <div class="col-md-5 input-group mb-1">
              <div class="button button-primary" style="width:43%">Sekolah</div>
              {{ Form::select('id_sekolah', $sekolah, null,['class' => 'form-control', 'required', 'value'=>null]) }}
				@if ($errors->has('id_sekolah'))
					<div class="help-block form-text with-errors form-control-feedback">
						<ul class="list-unstyled text-danger">
							<li>{{ $errors->first('id_sekolah') }}</li>
						</ul>
					</div>
				@endif<div class="input-group-append">
              </div>
            </div>
          </tr>
          <tr>
            <div class="col-md-5 mb-1">
              <div class="button button-primary" style="width:43%">Jumlah Penerimaan </div>
              <div class="input-group-append">
              </div>
            </div>
          </tr>
          <tr>
            <div class="col-md-12 form-row">
              <div class="col-md-2">
              </div>

              <div class="col-md-4 input-group mb-1">
                <div class="button button-success" style="width:30%">Tunai</div>
                {{ Form::text("jumlah", null,['class' => 'form-control required', 'placeholder' => ' ', 'data-type' => 'number', 'onkeyup' => 'sum();', 'id'=>'tunai']) }}
                <div class="input-group-append">
                </div>
              </div>
            </div>
          </tr>
          
          <tr>
            <div class="col-md-12 form-row">
              <div class="col-md-2">
              </div>
              <div class="col-md-4 input-group mb-1">
                <div class="button button-info" style="width:30%">Giro </div>
                {{ Form::text("giro", null,['class' => 'form-control required', 'placeholder' => ' ', 'onkeyup' => 'sum();', 'id'=>'giro']) }}
                <div class="input-group-append">
                </div>
              </div>
            </div>
          </tr>
          <tr>
              <div class="col-md-4 input-group mb-1" style="float: left; margin-left: 16%">
                <div class="button button-primary" style="width:30%">Jumlah </div>
                 <input class="form-control required" type="text" name="email" id="result" disabled>
                <div class="input-group-append">
                </div>
              </div>

              <div class="col-md-6 input-group mb-1" style="float: left;">
                <div class="button button-primary" style="width:20%">Terbilang </div>
                  {{ Form::text("terbilang", null,['class' => 'form-control required', 'placeholder' => null]) }}
                <div class="input-group-append">
                </div>
              </div>
          </tr>

          <tr>
            <div class="col-md-6 input-group mb-1">
              <div class="button button-primary" style="width:34%">Keterangan</div>
              {{ Form::textarea("ket", null,['class' => 'form-control required', 'rows' => 4, 'placeholder' => ' ']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>

          </table>

          <div class='form-group'>
            <div class='col-md-4 col-md-offset-2'>
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

  function sum() {
      var txtFirstNumberValue = document.getElementById('tunai').value;
      var txtSecondNumberValue = document.getElementById('giro').value;
      var result = parseFloat(txtFirstNumberValue) + parseFloat(txtSecondNumberValue);
      if (!isNaN(result)) {
         document.getElementById('result').value = result;
      }
  }
</script>

@endsection
