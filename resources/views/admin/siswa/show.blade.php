@extends('admin.admin')

@section('content')
     <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
              <span class="text-uppercase page-subtitle"> <a href="{{URL::to('siswa/index')}}"><i class="fa fa-dashboard"></i> Kembali</a></span>
                <h3 class="page-title">Detail Siswa</h3>
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
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{{$student->nama}}}" disabled> 
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
                                <label for="feLastName">Tempat Lahir</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{{$student->tempat_lahir}}}" disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feFirstName">Tanggal Lahir</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value="{{ Carbon\Carbon::parse($student->tgl_lahi)->formatLocalized('%d %B %Y')}}"  disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feLastName">Kelas</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{{$student->kelas}}}" disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feFirstName">Sub Kelas</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$student->subkelas}}}"  disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feLastName">Nama Ayah</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{{$student->nama_ayah}}}" disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feFirstName">No Hp Ayah</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$student->hp_ayah}}}"  disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feLastName">Nama Ibu</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{{$student->nama_ibu}}}" disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feFirstName">No Hp Ibu</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$student->hp_ibu}}}"  disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feLastName">Nama Wali</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{{$student->nama_wali}}}" disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feFirstName">No Hp Wali</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$student->hp_wali}}}"  disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feLastName">Kecamatan</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{{$student->kecamatan}}}" disabled> 
                              </div>

                              <div class="form-group col-md-6">
                                <label for="feFirstName">Kab/Kota</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$student->kota_kab}}}"  disabled> 
                              </div>
                            </div>
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