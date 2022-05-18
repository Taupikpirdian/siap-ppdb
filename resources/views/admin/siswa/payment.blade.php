@extends('admin.admin')

@section('content')

      @if($errors->any())
      <div class="alert alert-danger alert-block">
        <button type="button" style="color:#fff;" class="close" data-dismiss="alert">x</button>
        <strong style="font-family: Palatino; font-size: 14px">{{$errors->first()}}</strong>
      </div>
      @endif

      @if ($message = Session::get('flash-store'))
        <div class="alert alert-success alert-block">
          <button type="button" style="color:#fff;" class="close" data-dismiss="alert">x</button>
          <strong style="font-family: Palatino; font-size: 14px">{{ $message }}</strong>
        </div>
      @endif

     <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
              <span class="text-uppercase page-subtitle"> <a href="{{URL::to('siswa/index')}}"><i class="fa fa-dashboard"></i> Kembali</a></span>
                <h3 class="page-title">Detail Pembayaran</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <!-- Default Light Table -->
            <div class="row">
              <div class="col-lg-4">
                <div class="card card-small mb-4 pt-3">
                  <div class="card-header border-bottom text-center">
                    <div class="mb-3 mx-auto">
                      <img class="rounded-circle" src="{{URL::asset('images/siswa/thumbs/'.$student->foto)}}" alt="User Avatar" width="220" height="220"> </div>
                    <h4 class="mb-0">{{ $student->nama_siswa }}</h4>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-4">
                      <strong class="text-muted d-block mb-2">Alamat</strong>
                      <span>{{{$student->alamat}}}</span>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Data Lengkap</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          <form>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">Sekolah</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$student->nama_sekolah}}}"  disabled> 
                              </div>
                              
                              <div class="form-group col-md-6">
                                <label for="feLastName">NPM</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{{$student->npm}}}" disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">RFID</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$student->rfid}}}"  disabled> 
                              </div>
                              
                              <div class="form-group col-md-6">
                                <label for="feLastName">Program</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{{$student->nm_prog}}}" disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">Tahun Masuk</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$student->tahun}}}"  disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feLastName">Status</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{{$student->status}}}" disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feLastName">Semester</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{{$student->semester}}}" disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feFirstName">Tanggal Lahir</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value="{{ Carbon\Carbon::parse($student->tgl_lahir)->formatLocalized('%d %B %Y')}}"  disabled> 
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Riwayat Data Pembayaran PPDB</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          <form>
                            <table class="col-md-12 table table table-bordered table-striped table-hover table-condensed tfix mb-1 " style="font-family: Arial; font-size: 13px">
                                <thead class="bg-light">
                                  <tr>
                                    <th scope="col" class="border-0" style="width:4px;">No</th>
                                    <th scope="col" class="border-0">Tanggal Bayar</th>
                                    <th scope="col" class="border-0">Jenis Pembayaran</th>
                                    <th scope="col" class="border-0">Jumlah Pembayaran</th>
                                    <th scope="col" class="border-0" style="width:5px;"></th>
                                  </tr>
                                </thead>
                                <tstatus>
                                  @foreach($candidates as $i=>$value)
                                    <tr>
                                       <td> {{ $i + 1 }} </td>
                                       <td> {{ Carbon\Carbon::parse($value->tgl_bayar)->formatLocalized('%d %B %Y')}} </td>
                                       <td> {{{$value->name}}} </td>
                                       <td>Rp. {{{ number_format($value->cost_id) }}} </td>
                                       <td>
                                       <a title="Print Bukti Pembayaran" class="btn btn-warning btn-sm" target="_blank" href='{{URL::action("admin\CandidateController@invoice",array($value->id_invoice))}}'><i class="fa fa-print fa-xs" style="color: white"></i></a>
                                       </td>
                                    </tr>
                                  @endforeach
                                  <tr>
                                    <th colspan="3" class="border-0"><center>Jumlah</center></th>
                                    @if(!empty($value))
                                    <th colspan="2" class="border-0">Rp. {{{ number_format($value->amounts) }}}</th>
                                    @endif
                                  </tr>
                                </tstatus>
                            </table>
                          </form>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Pembayaran Semester</h6>
                  </div>
                  @if($spp_persemester)
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          <form>
                            <table class="col-md-12 table table table-bordered table-striped table-hover table-condensed tfix mb-1 " style="font-family: Arial; font-size: 13px">
                                <thead class="bg-light">
                                  <tr>
                                    <th scope="col" class="border-0">
                                    Semester 1
                          					@if($spp_persemester->name <= $semester_1)
                                      <a title="Lunas" class="btn btn-success btn-sm" style="color:white"><i class="fa fa-check"></i></a>
                                    @endif
                                    </th>
                                    <th scope="col" class="border-0">
                                    Semester 2
                          					@if($spp_persemester->name <= $semester_2)
                                      <a title="Lunas" class="btn btn-success btn-sm" style="color:white"><i class="fa fa-check"></i></a>
                                    @endif
                                    </th>
                                    <th scope="col" class="border-0">
                                    Semester 3
                          					@if($spp_persemester->name <= $semester_3)
                                      <a title="Lunas" class="btn btn-success btn-sm" style="color:white"><i class="fa fa-check"></i></a>
                                    @endif
                                    </th>
                                    <th scope="col" class="border-0">
                                    Semester 4
                          					@if($spp_persemester->name <= $semester_4)
                                      <a title="Lunas" class="btn btn-success btn-sm" style="color:white"><i class="fa fa-check"></i></a>
                                    @endif
                                    </th>
                                    <th scope="col" class="border-0">
                                    Semester 5
                          					@if($spp_persemester->name <= $semester_5)
                                      <a title="Lunas" class="btn btn-success btn-sm" style="color:white"><i class="fa fa-check"></i></a>
                                    @endif
                                    </th>
                                    <th scope="col" class="border-0">
                                    Semester 6
                          					@if($spp_persemester->name <= $semester_6)
                                      <a title="Lunas" class="btn btn-success btn-sm" style="color:white"><i class="fa fa-check"></i></a>
                                    @endif
                                    </th>
                                  </tr>
                                </thead>
                                <tstatus>
                                    <tr>
                                       <td>Rp. {{{ number_format($semester_1) }}} </td>
                                       <td>Rp. {{{ number_format($semester_2) }}} </td>
                                       <td>Rp. {{{ number_format($semester_3) }}} </td>
                                       <td>Rp. {{{ number_format($semester_4) }}} </td>
                                       <td>Rp. {{{ number_format($semester_5) }}} </td>
                                       <td>Rp. {{{ number_format($semester_6) }}} </td>
                                    </tr>
                                </tstatus>
                            </table>
                          </form>
                        </div>
                      </div>
                    </li>
                  </ul>
                  @else
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          <b></b>
                        </div>
                      </div>
                    </li>
                  </ul>
                  @endif
                </div>
              </div>
              <div class="col-lg-12">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Detail Data Pembayaran Semester</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          <form>
                            <table class="col-md-12 table table table-bordered table-striped table-hover table-condensed tfix mb-1 " style="font-family: Arial; font-size: 13px">
                                <thead class="bg-light">
                                  <tr>
                                    <th scope="col" class="border-0" style="width:4px;">No</th>
                                    <th scope="col" class="border-0">Tanggal Bayar</th>
                                    <th scope="col" class="border-0">Semester</th>
                                    <th scope="col" class="border-0">Jumlah Pembayaran</th>
                                    <th scope="col" class="border-0" style="width:5px;"></th>
                                  </tr>
                                </thead>
                                <tstatus>
                                  @foreach($spp as $i=>$value)
                                    <tr>
                                       <td> {{ $i + 1 }} </td>
                                       <td> {{ Carbon\Carbon::parse($value->tgl_bayar)->formatLocalized('%d %B %Y')}} </td>
                                       <td> {{{$value->semester}}} </td>
                                       <td>Rp. {{{ number_format($value->amount) }}} </td>
                                       <td>
                                       <a title="Print Bukti Pembayaran" class="btn btn-warning btn-sm" target="_blank" href='{{URL::action("admin\SiswaController@invoice",array($value->id_invoice))}}'><i class="fa fa-print fa-xs" style="color: white"></i></a>
                                       </td>
                                    </tr>
                                  @endforeach
                                  <!-- kondisi jika data kosong -->
                                  <tr>
                                    <th colspan="3" class="border-0"><center>Sub Jumlah</center></th>
                                      <th>Rp. {{{ number_format($subjumlah) }}}</th>
                                  </tr>
                                  <tr>
                                    <th colspan="3" class="border-0"><center>Sisa</center></th>
                                    <th>Rp. {{{ number_format($jumlahseluruh - $subjumlah) }}}</th>
                                  </tr>
                                  <tr>
                                    <th colspan="3" class="border-0"><center>Jumlah</center></th>
                                    <th>Rp. {{{ number_format($jumlahseluruh) }}}</th>
                                  </tr>
                                  <!-- sampai sini -->
                                </tstatus>
                            </table>
                          </form>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Masukan Pembayaran Baru</h6>
                  </div>
                  @if($spp_persemester)
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                        {{ Form::open(array('url' => 'siswa/payment', 'files' => true, 'method' => 'post')) }}
                            <table class="table table-striped table-hover">
                            @if($student->semester)
                            @else
                            <div class="alert alert-danger alert-block">
                              <button type="button" style="color:#fff;" class="close" data-dismiss="alert">x</button>
                              <strong style="font-family: Palatino; font-size: 14px">Data Semester Kosong, Harap diisi terlebih dahulu</strong>
                            </div>
                            @endif

                            <tr>
                              <div class="col-md-8 input-group mb-1" style="display: none; visibility: hidden;">
                                <div class="button button-primary" style="width:30%">NPM</div>
                                  {{ Form::text('npm', $student->npm,['class' => 'form-control', 'value'=>'']) }}
                              </div>
                            </tr>

                            <tr>
                              <div class="col-md-8 input-group mb-1" style="display: none; visibility: hidden;">
                                <div class="button button-primary" style="width:30%">Nama Siswa</div>
                                  {{ Form::text('siswa_id', $student->id,['class' => 'form-control', 'required', 'value'=>'']) }}
                              </div>
                            </tr>

                            <!-- Semester otomatis -->
                            <!-- <tr>
                              <div class="col-md-8 input-group mb-1">
                                <div class="button button-primary" style="width:30%">Semester</div>
                                  {{ Form::text('semester', $student->semester,['class' => 'form-control', 'required', 'value'=>'']) }}
                              </div>
                            </tr> -->

                            <tr>
                              <div class="col-md-8 input-group mb-1" style="display: none; visibility: hidden;">
                                <div class="button button-primary" style="width:30%">Tanggal Bayar</div>
                                  {{ Form::date('tgl_bayar', Carbon\Carbon::now(), null,['class' => 'form-control', 'required', 'value'=>'']) }}
                              </div>
                            </tr>

                            <tr>
                              <div class="col-md-8 input-group mb-1" style="display: none; visibility: hidden;">
                                <div class="button button-primary sekolah" style="width:30%">Sekolah</div>
                                {{ Form::text('sekolah_id', $student->sekolah_id,['class' => 'form-control', 'required', 'value'=>'']) }}
                                <div class="input-group-append">
                                </div>
                              </div>
                            </tr>

                            <tr>
                              <div class="col-md-8 input-group mb-1" style="display: none; visibility: hidden;">
                                <div class="button button-primary" style="width:30%">Tahun</div>
                                {{ Form::text('thn_id', $student->thn_id,['class' => 'form-control', 'required', 'value'=>'']) }}
                                <div class="input-group-append">
                                </div>
                              </div>
                            </tr>

                            <tr>
                              <div class="col-md-8 input-group mb-1" style="display: none; visibility: hidden;">
                                <div class="button button-primary" style="width:30%">Program</div>
                                {{ Form::text('program_id', $student->program_id,['class' => 'form-control', 'required', 'value'=>'']) }}
                                <div class="input-group-append">
                                </div>
                              </div>
                            </tr>

                            <tr>
                              <div class="col-md-8 input-group mb-1">
                                <div class="button button-primary" style="width:30%">Semester</div>
                                {{ Form::select('semester',$semester, null,['class' => 'form-control payment prabayar']) }}
                                <div class="input-group-append">
                                </div>
                              </div>
                            </tr>

                            <tr>
                              <div class="col-md-8 input-group mb-1">
                                <div class="button button-primary" style="width:30%">Jenis Pembayaran</div>
                                {{ Form::select('payment_id',$payments, null,['class' => 'form-control payment prabayar']) }}
                                <div class="input-group-append">
                                </div>
                              </div>
                            </tr>

                            <tr>
                              <div class="col-md-8 input-group mb-1">
                                <div class="button button-primary" style="width:30%">Biaya</div>
                                  {{ Form::text('amount', '',['class' => 'form-control', 'required', 'value'=>'']) }}
                              </div>
                            </tr>

                            <tr>
                              <div class="col-md-8 input-group mb-1">
                                <div class="button button-primary" style="width:30%">Keterangan</div>
                                  {{ Form::textarea('ket', '',['class' => 'form-control', 'required', 'value'=>'']) }}
                              </div>
                            </tr>
        
                            </table>
                            <div class='form-group'>
                              <div class='col-md-4 col-md-offset-2'>
                                <button class='button button-primary' onclick="return confirm('Apakah anda yakin data pembayaran spp sudah benar?');" type='submit' name='save' id='save'><span class='glyphicon glyphicon-save'></span> Save</button>
                              </div>
                            </div>
                        {!! Form::close() !!}
                        </div>
                      </div>
                    </li>
                  </ul>
                  @else
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          <b>Nilai SPP untuk data ini tidak ada, harap masukan terlebih dahulu !</b>
                        </div>
                      </div>
                    </li>
                  </ul>
                  @endif
                </div>
              </div>
            </div>
            <!-- End Default Light Table -->
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