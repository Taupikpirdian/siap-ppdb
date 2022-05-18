@extends('admin.admin')

@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <h3 class="page-title">Edit Data Siswa</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <!-- Add New Post Form -->
                <div class="card card-small mb-3">
                  <div class="card-body">
                  {!! Form::model($student,['files'=>true,'method'=>'put','action'=>['admin\SiswaController@update',$student->id]]) !!}
                    <table class="table table-striped table-hover">
                    @if(Auth::check())
                    @if(Auth::user()->groups()->where("name", "=", "Admin")->first())
                    <tr>
                      <div class="col-md-5 input-group mb-1">
                        <div class="button button-primary" style="width:39%">Sekolah</div>
                          {{ Form::select('sekolah_id', $scholl, null,['class' => 'form-control sekolah', 'readonly' => $is_kasir ]) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                    </tr>
                    @else
                    <tr>
                      <div class="col-md-5 input-group mb-1">
                        <div class="button button-primary" style="width:39%">Sekolah</div>
                        <select class="form-control" name="sekolah_id">
                              <option value="{{$student->id}}">{{ $student->nama_siswa }}</option>
                        </select>
                        <div class="input-group-append">
                        </div>
                      </div>
                    </tr>
                    @endif
                    @endif

                    @if(Auth::check())
                    @if(Auth::user()->groups()->where("name", "=", "Admin")->first())
                    <tr>
                      <div class="col-md-5 input-group mb-1">
                        <div class="button button-primary" style="width:39%">Tahun Masuk</div>
                        {{ Form::select('thn_id',$year, null,['class' => 'form-control', 'readonly' => $is_kasir]) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                    </tr>
                    @else
                    <tr>
                      <div class="col-md-5 input-group mb-1">
                        <div class="button button-primary" style="width:39%">Tahun Masuk</div>
                        <select class="form-control" name="thn_id">
                              <option value="{{$student->thn_id}}">{{ $student->tahun }}</option>
                        </select>
                        <div class="input-group-append">
                        </div>
                      </div>
                    </tr>
                    @endif
                    @endif

                    <tr>
                      <div class="col-md-5 input-group mb-1">
                        <div class="button button-primary" style="width:39%">Program</div>
                        {{ Form::select('program_id',$prog, null,['class' => 'form-control program biaya']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                      @error('program_id')
                        <div class="col-md-5 input-group mb-1" style="color:red">
                            {{ $message }}
                        </div>
                      @enderror
                    </tr>

                    <tr>
                      <div class="col-md-4 input-group mb-1">
                        <div class="button button-primary" style="width:50%">NPM</div>
                        {{ Form::text("npm", null,['class' => 'form-control required', 'placeholder' => 'Masukan NPM']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                      @error('npm')
                        <div class="col-md-5 input-group mb-1" style="color:red">
                            {{ $message }}
                        </div>
                      @enderror
                    </tr>

                    <tr>
                      <div class="col-md-6 input-group mb-1">
                        <div class="button button-primary" style="width:32%">Nama Siswa</div>
                        {{ Form::text("nama_siswa", null,['class' => 'form-control required', 'placeholder' => 'Masukan Nama Siswa']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                      @error('nama_siswa')
                        <div class="col-md-5 input-group mb-1" style="color:red">
                            {{ $message }}
                        </div>
                      @enderror
                    </tr>

                    <tr>
                        <div class="col-md-4 input-group mb-1">
                          <div class="button button-primary" style="width:50%">RFID</div>
                          {{ Form::text("rfid", null,['class' => 'form-control required', 'placeholder' => 'Masukan RFID']) }}
                          <div class="input-group-append">
                          </div>
                        </div>
                    </tr>

                    <tr>
                      <div class="col-md-4 input-group mb-1">
                        <div class="button button-primary" style="width:50%">Semester</div>
                        {{ Form::text("semester", null,['class' => 'form-control required', 'placeholder' => 'Masukan Semester']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                    </tr>

                    <tr>
                    <div class="col-md-12 form-row">
                      <div class="col-md-4 input-group mb-1">
                        <div class="button button-primary" style="width:48%">Tempat Lahir</div>
                        {{ Form::text("tempat_lahir", null,['class' => 'form-control required', 'placeholder' => 'Masukan Tempat Lahir']) }}
                        <div class="input-group-append">
                        </div>
                      </div>

                      <div class="col-md-2">
                      </div>
                  
                      <div class="col-md-4 input-group mb-1">
                        <div class="button button-primary" style="width:50%">Tanggal Lahir</div>
                        {{ Form::date("tgl_lahir", null,['class' => 'form-control required', 'placeholder' => 'Pilih Tanggal Lahir']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                      </div>
                    </tr>

                    <tr>
                    <div class="col-md-12 form-row">
                      <div class="col-md-4 input-group mb-1">
                        <div class="button button-primary" style="width:48%">Kelas</div>
                        {{ Form::text("kelas", null,['class' => 'form-control required', 'placeholder' => 'Masukan Kelas']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                      
                      <div class="col-md-2">
                      </div>
                   
                      <div class="col-md-4 input-group mb-1">
                        <div class="button button-primary" style="width:50%">SubKelas</div>
                        {{ Form::text("subkelas", null,['class' => 'form-control required', 'placeholder' => 'Masukan SubKelas']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                    </div>
                    </tr>

                    <tr>
                      <div class="col-md-12 form-row">
                        <div class="col-md-6 input-group mb-1">
                          <div class="button button-primary" style="width:32%">Nama Ayah</div>
                          {{ Form::text("nama_ayah", null,['class' => 'form-control required', 'rows' => 2, 'cols' , 'placeholder' => 'Masukan Nama Ayah']) }}
                          <div class="input-group-append">
                          </div>
                        </div>
                    
                        <div class="col-md-4 input-group mb-1">
                          <div class="button button-primary" style="width:50%">No Hp Ayah</div>
                          {{ Form::text("hp_ayah", null,['class' => 'form-control required', 'placeholder' => 'Masukan No Hp Ayah']) }}
                          <div class="input-group-append">
                          </div>
                        </div>
                      </div>
                    </tr>

                    <tr>
                    <div class="col-md-12 form-row">
                      <div class="col-md-6 input-group mb-1">
                        <div class="button button-primary" style="width:32%">Nama Ibu</div>
                        {{ Form::text("nama_ibu", null,['class' => 'form-control required', 'placeholder' => 'Masukan Nama Ibu']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                   
                      <div class="col-md-4 input-group mb-1">
                        <div class="button button-primary" style="width:50%">No Hp Ibu</div>
                        {{ Form::text("hp_ibu", null,['class' => 'form-control required', 'placeholder' => 'Masukan No Hp Ibu']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                      </div>
                    </tr>

                    <tr>
                    <div class="col-md-12 form-row">
                      <div class="col-md-6 input-group mb-1">
                        <div class="button button-primary" style="width:32%">Nama Wali</div>
                        {{ Form::text("nama_wali", null,['class' => 'form-control required', 'placeholder' => 'Masukan Nama Wali']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                   
                      <div class="col-md-4 input-group mb-1">
                        <div class="button button-primary" style="width:50%">No Hp Wali</div>
                        {{ Form::text("hp_wali", null,['class' => 'form-control required', 'placeholder' => 'Masukan No Hp Wali']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                      </div>
                    </tr>

                    <tr>
                      <div class="col-md-6 input-group mb-1">
                        <div class="button button-primary" style="width:32%">Alamat</div>
                        {{ Form::textarea("alamat", null,['class' => 'form-control required',  'rows' => 2, 'placeholder' => 'Masukan Alamat Lengkap']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                    </tr>

                    <tr>
                    <div class="col-md-12 form-row">
                      <div class="col-md-6 input-group mb-1">
                        <div class="button button-primary" style="width:32%">Kecamatan</div>
                        {{ Form::text("kecamatan", null,['class' => 'form-control required', 'placeholder' => 'Masukan Kecamatan']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                    
                      <div class="col-md-6 input-group mb-1">
                        <div class="button button-primary" style="width:32%">Kota/Kab</div>
                        {{ Form::text("kota_kab", null,['class' => 'form-control required', 'placeholder' => 'Masukan Kota/Kab']) }}
                        <div class="input-group-append">
                        </div>
                      </div>
                      </div>
                    </tr>

                    <tr>
                      <div class="col-md-4 input-group mb-1">
                        <div class="button button-primary" style="width:50%">Status</div>
                        {{ Form::select('status', $status, null,['class' => 'form-control' , 'required','value'=>null ,'placeholder' => 'Masukan Status']) }}
                        @if($errors->has('status'))
                          {{ $errors->first('status') }}
                        @endif
                        <div class="input-group-append">
                        </div>
                      </div>
                    </tr>

                    <tr>
                      <div class="col-md-12 input-group mb-3">
                        <div class="button button-primary" style="width:15%">Foto</div>
                        {{ Form::file("foto", null) }}
                        <span>{{$errors->first('foto')}}</span>
                        <div class="input-group-append">
                         <img style="width:150px; height: 150px" src="{{URL::asset('images/siswa/'.$student->foto)}}">
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
</script>

<script type="text/javascript">
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
      $('.payment').append('<option value="0" disable="true" selected="true">Pilih Jenis Pembayaran</option>');
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
      $.each(data,function(index,subcatObj){
        $('.cost').append('<option value="'+subcatObj.id+'">'+subcatObj.name+'</option>');
      });
    });
  });
</script>
@endsection