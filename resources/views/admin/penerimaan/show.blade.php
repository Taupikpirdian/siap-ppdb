@extends('admin.admin')
@section('content')
<div class="main-content-container container-fluid px-4">
            <!-- End Page Header -->
            <!-- Default Light Table -->
            <div class="row py-4">
              <div class="col-lg-12">
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
                                <label for="feFirstName">Kode Penerimaan</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$penerimaans->kode_penerimaan}}}"  disabled> 
                              </div>
                              
                              <div class="form-group col-md-6">
                                <label for="feLastName">Nama Penerima</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{!! $penerimaans->nama_penerimaan !!}" disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">Diterima Dari</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$penerimaans->asal_penerimaan}}}"  disabled> 
                              </div>
                              
                              <div class="form-group col-md-6">
                                <label for="feLastName">Tanggal Penerimaan</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{ Carbon\Carbon::parse($penerimaans->tgl_terima)->formatLocalized('%d %B %Y')}}" disabled> 
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">Tunai</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value="Rp. {{ number_format($penerimaans->jumlah) }}"  disabled> 
                              </div>
                              
                              <div class="form-group col-md-6">
                                <label for="feLastName">Giro</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="Rp. {!! number_format($penerimaans->giro) !!}" disabled> 
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="feFirstName">Jumlah</label>
                                <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value="Rp. {{ number_format($penerimaans->jumlah+$penerimaans->giro) }}"  disabled> 
                              </div>
                              @if($penerimaan->isEmpty())
                              <div class="form-group col-md-6">
                                <label for="feLastName">Sisa</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="Rp. {{ number_format($penerimaans->jumlah+$penerimaans->giro) }}" disabled> 
                              </div>
                              @else
                              <div class="form-group col-md-6">
                                <label for="feLastName">Sisa</label>
                                <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="Rp. {{ number_format($value->sisa) }}" disabled> 
                              </div>
                              @endif
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
                    <h6 class="m-0">History Pertanggung Jawaban Penerimaan</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          @if($penerimaan->isEmpty())
                            Data Pertanggung Jawaban Masih Kosong
                          @else
                          <form>
                                <table class="col-md-12 table table table-bordered table-striped table-hover table-condensed tfix mb-1 " style="font-family: Arial; font-size: 13px">
                                    <thead class="bg-light">
                                      <tr>
                                        <th scope="col" class="border-0">No</th>
                                        <th scope="col" class="border-0">Tanggal Input</th>
                                        <th scope="col" class="border-0">Jumlah Input</th>
                                        <th scope="col" class="border-0">Status</th>
                                        <th scope="col" class="border-0">Kwitansi</th>
                                      </tr>
                                    </thead>
                                    <tstatus>
                                  @foreach($penerimaan as $i=>$terima)
                                    <tr>
                                       <td> {{ $i + 1 }} </td>
                                       <td> {{ Carbon\Carbon::parse($terima->created_at)->formatLocalized('%d %B %Y')}} </td>
                                       <td> Rp. {{ number_format($terima->jumlah_penyaluran) }} </td>
                                       <td> @if($terima->status_tuntas == 1)
                                          <span title="Tuntas" class="btn btn-success btn-sm"><i class="fa fa-check"></i></span>
                                            @else
                                          <span title="Belum Tuntas" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></span>
                                            @endif
                                       </td>
                                       <td> <a title="Download Bukti Pertanggungjawaban" class="btn btn-primary btn-sm" target="_blank" href="{{URL::to('/files/file/'.$terima->file)}}" ><i class="fa fa-download "> </i></a> </td>
                                    </tr>
                                  @endforeach
                              </tstatus>
                            </table>
                          </form>
                          @endif
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