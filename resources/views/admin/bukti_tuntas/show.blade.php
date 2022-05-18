@extends('admin.admin')
@section('content')

<div class="form-group row">
    <form>
        <h5 class="form-header">
        Show Penerimaan
        </h5>
            <div class="form-group row">
                <label class="col-form-label col-sm-4" for=""> Kode Penerimaan</label>
                <div class="col-sm-8"> {{{$penerimaans->kode_penerimaan}}}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-4" for=""> Tanggal Terima</label>
                <div class="col-sm-8"> {{{$penerimaans->tgl_terima}}}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-4" for=""> Asal Penerimaan</label>
                <div class="col-sm-8"> {{{$penerimaans->asal_penerimaan}}}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-4" for=""> Nama Penerimaan</label>
                <div class="col-sm-8"> {!! $penerimaans->nama_penerimaan !!}
                </div>
            </div>
            <div class="form-buttons-w">
                <a href="{{URL::to('penerimaans/index')}}" class="btn btn-primary" role="button"> Kembali</a>
            </div>
    </form>
</div>

  @endsection