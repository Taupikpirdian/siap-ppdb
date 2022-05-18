@extends('admin.admin')
@section('content')




<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <div class="main-content-container container-fluid px-4">
  <!-- Page Header -->
  <div class="page-header row no-gutters py-4">
    <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
      <h3 class="page-title">Input Penerimaan</h3>
    </div>
  </div>
  <!-- End Page Header -->
  <div class="row">
    <div class="col-lg-12 col-md-12">
      <!-- Add New Post Form -->
      <div class="card card-small mb-3">
        <div class="card-body">


	{{ Form::open(array('url' => 'bukti/create', 'files' => true, 'method' => 'post', 'class' => 'save_form')) }}
          <table class="table table-striped table-hover">

          <tr>
            <div class="col-md-4 input-group mb-1">
              <div class="button button-white" style="width:50%">Kode Penerimaan</div>
              {{ Form::select('id_kode', $kode_penerima, 'null',['class' => 'form-control id_kode', 'required', 'value'=>'']) }}
              <div class="input-group-append">
              </div>
            </div>
          </tr>
          
          <tr>
            <div class="col-md-4  input-group mb-1">
              <div class="button button-white" style="width:50%">Jumlah Penyaluran</div>
              {{ Form::text("jumlah_penyaluran", '',['class' => 'form-control required jumlah_penyaluran', 'placeholder' => ' ']) }}
              <div class="input-group-append">
                <label class="validation" style="color: red; display: none;">Jumlah penyaluran tidak boleh lebih dari jumlah penerimaan!</label>
              </div>
            </div>
          </tr>
          
          <tr>

          <div class="col-md-6 input-group mb-3">
            <div class="button button-white" style="width:32%">Upload Bukti</div>
            {{ Form::file("file", ['class' => 'file_upload']) }}
            <span>{{$errors->first('file')}}</span>
            <label class="validation_file" style="color: red; display: none;">File harus diisi!</label>
          </div>
          </tr>

          </table>

          <div class='form-group'>
            <div class='col-md-4 col-md-offset-2'>
              <a class="button button-sm button-primary" title="save" id="save" style="color: white;"> Save</a>
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
<script type="text/javascript">
  var max_input = 0;
  var error_cek = 0;
  // var error_file = 1;

  $('.id_kode').on('change',function(e){
    var id_kode = e.target.value;
    
    $.get('/ajax-get-id-penerimaan?id_kode='+id_kode, function(data){
        max_input = data.jumlah;
        console.log(max_input);
        $(".jumlah_penyaluran").prop('disabled', false);
      });

  });

  $('.jumlah_penyaluran').on('input', function(e) {
    var jumlah_input = e.target.value;

    if (jumlah_input > max_input) {
      $('.validation').show();
      error_cek = 1;
    }else{
      error_cek = 0;
      $('.validation').hide();
    }

  });

  // $('.file_upload').bind('change', function() {
  //   error_file = 0;
  //   alert(this.files[0].size);
  // });

  $(document).on("click", "#save", function() {
    if (error_cek == 1) {
      $('.validation').fadeOut(500);
      $('.validation').fadeIn(500);
    }else {
      $('.save_form').trigger('submit');
    }
  });
</script>
@endsection