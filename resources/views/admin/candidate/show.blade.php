@extends('admin.admin')
@section('content')
<div class="main-content-container container-fluid px-4">
            <!-- End Page Header -->
            <!-- Default Light Table -->
            <div class="row py-4">
            <p>
              <a href="{{URL::to('candidate/index')}}" role="button">kembali</a>
            </p>
              <div class="col-lg-12">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Data Lengkap Calon Siswa</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                        {{ Form::open(array('url' => 'approve/create', 'files' => true, 'method' => 'post')) }}
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">Nama</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$candidate->nama_siswa}}}"  disabled> 
                              </div>
                              
                              <div class="form-group col-md-6">
                                <label for="feLastName">Tanggal Lahir</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{ Carbon\Carbon::parse($candidate->tgl_lahir)->formatLocalized('%d %B %Y')}}" disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">Sekolah</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$candidate->nama_sekolah}}}"  disabled> 
                              </div>
                              
                              <div class="form-group col-md-6">
                                <label for="feLastName">Program</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{ $candidate->program->nama }}" disabled> 
                              </div>

                              <div class="form-group col-md-6" style="display: none; visibility: hidden;">
                                <label for="feFirstName">Id Calon</label>
                                {{ Form::text("candidate_id", $candidate->id,['class' => 'form-control required', 'placeholder' => 'Masukan Jenis Pembayaran']) }} 
                              </div>
                            </div>

                            <div class="form-row" style="display: none; visibility: hidden;">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">Nama</label>
                                {{ Form::text("nama_siswa", $candidate->nama,['class' => 'form-control required', 'placeholder' => 'Masukan Jenis Pembayaran']) }} 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feLastName">Tanggal Lahir</label>
                                {{ Form::text("tgl_lahir", $candidate->tgl_lahir, ['class' => 'form-control required', 'placeholder' => 'Masukan Jenis Pembayaran']) }} 
                              </div>
                            </div>

                            <div class="form-row" style="display: none; visibility: hidden;">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">Sekolah</label>
                                {{ Form::text("sekolah_id", $candidate->sekolah_id, ['class' => 'form-control required', 'placeholder' => 'Masukan Jenis Pembayaran']) }} 
                              </div>
                              
                              <div class="form-group col-md-6">
                                <label for="feLastName">Program</label>
                                {{ Form::text("program_id", $candidate->program->id, ['class' => 'form-control required', 'placeholder' => 'Masukan Jenis Pembayaran']) }}  
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6" style="display: none; visibility: hidden;">
                                <label for="feFirstName">Tahun Masuk</label>
                                {{ Form::text("thn_id", $candidate->thn_id, ['class' => 'form-control required', 'placeholder' => 'Masukan Jenis Pembayaran']) }} 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feFirstName">Tahun Masuk</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$candidate->tahun}}}"  disabled> 
                              </div>
                              
                              <div class="form-group col-md-6">
                                <label for="feLastName">Tanggal Daftar</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{ Carbon\Carbon::parse($candidate->created_at)->formatLocalized('%d %B %Y')}}" disabled>
                              </div>
                            </div>
                            @if($candidate->status == "Calon" && $candidate->payment_status == 2)
                            <button type='submit' name='save' id='save' class="btn btn-info btn-sm" onclick="return confirm('Apakah anda yakin untuk mengaktifkan siswa ini?');">Terima</button>
                            @elseif($candidate->payment_status == "3")
                            <span title="Tuntas" class="btn btn-success btn-sm">Siswa Sudah Aktif</span>
                            @else
                            <span title="Tuntas" class="btn btn-info btn-sm">Siswa Belum Aktif</span>
                            @endif
                          {!! Form::close() !!}
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">History Data Pembayaran</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          <form>
                                <table class="col-md-12 table table table-bordered table-striped table-hover table-condensed tfix mb-1 " style="font-family: Arial; font-size: 13px">
                                    <thead class="bg-light">
                                      <tr>
                                        <th scope="col" class="border-0">No</th>
                                        <th scope="col" class="border-0">Tanggal Bayar</th>
                                        <th scope="col" class="border-0">Jenis Pembayaran</th>
                                        <th scope="col" class="border-0">Jumlah Pembayaran</th>
                                        <th scope="col" class="border-0">Kwitansi</th>
                                      </tr>
                                    </thead>
                                    <tstatus>
                                  @foreach($candidates as $i=>$value)
                                    <tr>
                                       <td> {{ $i + 1 }} </td>
                                       <td> {{ Carbon\Carbon::parse($value->tgl_bayar)->formatLocalized('%d %B %Y')}} </td>
                                       <td> {{{$value->payment_name}}} </td>
                                       <td>Rp. {{{ number_format($value->cost_id) }}} </td>
                                       <td>
                                       <a title="Print Bukti Pembayaran" class="btn btn-warning btn-sm" target="_blank" href='{{URL::action("admin\CandidateController@invoice",array($value->id_invoice))}}'><i class="fa fa-print fa-xs" style="color: white"></i></a>
                                       </td>
                                    </tr>
                                  @endforeach
                                  <tr>
                                    <th colspan="3" class="border-0"><center>Jumlah</center></th>
                                    <th class="border-0">Rp. {{{ number_format($value->amounts) }}}</th>
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
            </div>
            <!-- End Default Light Table -->
          </div>
  @endsection

  @section('js')

@endsection