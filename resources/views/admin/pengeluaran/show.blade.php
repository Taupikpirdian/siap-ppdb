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
                      <label for="feFirstName">Kode Pengeluaran</label>
                      <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$luaran->kode_pengeluaran}}}"  disabled> 
                    </div>
                    <div class="form-group col-md-6">
                      <label for="feLastName">Nama yang Mengeluarkan</label>
                      <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{!! $luaran->nama_pengeluaran !!}" disabled> 
                    </div>
                    
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="feFirstName">Ditujukan Untuk</label>
                      <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value=" {{{$luaran->asal_pengeluaran}}}"  disabled> 
                    </div>
                    
                    <div class="form-group col-md-6">
                      <label for="feLastName">Tanggal Pengeluaran</label>
                      <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="{{ Carbon\Carbon::parse($luaran->tgl_keluar)->formatLocalized('%d %B %Y')}}" disabled> 
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="feFirstName">Tunai</label>
                      <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value="Rp. {{ number_format($luaran->jumlah) }}"  disabled> 
                    </div>
                    
                    <div class="form-group col-md-6">
                      <label for="feLastName">Giro</label>
                      <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="Rp. {!! number_format($luaran->giro) !!}" disabled> 
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="feFirstName">Jumlah</label>
                      <input type="text" class="form-control" id="feFirstName" placeholder="First Name" value="Rp. {{ number_format($luaran->jumlah+$luaran->giro) }}"  disabled> 
                    </div>
                    @if(!empty($value))
                    <div class="form-group col-md-6">
                      <label for="feLastName">Sisa</label>
                      <input type="text" class="form-control" id="feLastName" placeholder="Last Name" value="Rp. {{ number_format($value->sisa) }}" disabled> 
                    </div>
                    @endif
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="feDescription">Description</label>
                      <textarea class="form-control" name="feDescription" rows="5" value="{!! $luaran->ket !!}" disabled>{!! $luaran->ket !!}</textarea>
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
                    <h6 class="m-0">History Pertanggung Jawaban Pengeluaran</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          @if(!empty($penerimaans))
                            Data Masih Kosong
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
                                  @foreach($buktipengeluaran as $i=>$terima)
                                    <tr>
                                       <td> {{ $i + 1 }} </td>
                                       <td> {{ Carbon\Carbon::parse($terima->created_at)->formatLocalized('%d %B %Y')}} </td>
                                       <td> Rp. {{ number_format($terima->jumlah_pengeluaran) }} </td>
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