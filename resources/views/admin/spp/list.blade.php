@extends('admin.admin')
@section('content')

{{-- notifikasi form validasi --}}
@if ($errors->has('file'))
<span class="invalid-feedback" role="alert">
	<strong>{{ $errors->first('file') }}</strong>
</span>
@endif

@if($errors->any())
<div class="alert alert-danger alert-block">
	<button type="button" style="color:#fff;" class="close" data-dismiss="alert">x</button>
	<strong style="font-family: Palatino; font-size: 14px">{{$errors->first()}}</strong>
</div>
@endif

@if ($message = Session::get('flash-approve'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">x</button>
	<strong>{{ $message }}</strong>
</div>
@endif

@if ($message = Session::get('flash-disagree'))
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">x</button>
	<strong>{{ $message }}</strong>
</div>
@endif

{{-- notifikasi sukses --}}
@if ($sukses = Session::get('sukses'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button> 
	<strong>{{ $sukses }}</strong>
</div>
@endif

<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-6 text-center text-sm-left mb-0">
    <h3 class="page-title">List Detail Pembayaran SPP Belum disetujui</h3>
  </div>
</div>

<div class="row">
  <div class="col">
    <div class="card card-small mb-5">
      <div class="card-header border-bottom">
        <div class="pull-left">
          <div class='form-group clearfix'>
            <div class='col-md-5'>
            </div>
          </div>
		    </div>
		{!! Form::open(['method'=>'GET','url'=>'/searchspp','role'=>'search'])  !!}
		<div class=" col-md-5 input-group mb-1 px-0">
       	 	<input name="search" type="text" style="margin:2px;" class="form-control" placeholder="Cari Nama, NPM atau Semester" aria-label="Cari Nama atau Tahun" aria-describedby="basic-addon2">
        	<div class="input-group-append">
        		<button class="button button-white" style="margin:2px;" type="submit">Search</button>
    				<a data-toggle="modal" data-target="#importExcel" title="Import" href="{{ route('export.siswa',['type'=>'xls']) }}" class="button button-primary" style="margin:2px;" target="_blank"><i class="fa fa-file-import"></i></a>
          </div>
    	</div>
		{!! Form::close() !!}

    <!-- Import Excel -->
		<div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form method="post" action="/spp/import" enctype="multipart/form-data">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
						</div>
						<div class="modal-body">

							{{ csrf_field() }}

							<label>Pilih file excel</label>
							<div class="form-group">
								<input type="file" name="file" required="required">
							</div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Import</button>
						</div>
					</div>
				</form>
			</div>
		</div>
      
      <div class="card-body p-0 pb-3 text-center">
        <table class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="border-0" style="width:10px;">No</th>
              <th scope="col" class="border-0">Tanggal Bayar</th>
              <th scope="col" class="border-0">Sekolah</th>
              <th scope="col" class="border-0">NPM</th>
              <th scope="col" class="border-0">Nama Siswa</th>
              <th scope="col" class="border-0">Semester</th>
              <th scope="col" class="border-0">No Urut</th>
              <th scope="col" class="border-0">Jumlah</th>
              <th scope="col" class="border-0">Keterangan</th>
              <th scope="col" class="border-0">Action</th>
            </tr>
          </thead>
          <tstatus>
			@foreach($spp as $i=>$detail_payment)
			<tr>
				<td>{{ ($spp->currentpage()-1) * $spp->perpage() + $i + 1 }}</td>
				<td> {{ $detail_payment->tgl_bayar }} </td>
				<td> {{ $detail_payment->nama_sekolah }} </td>
				<td> {{ $detail_payment->npm }} </td>
				<td> {{ $detail_payment->nama_siswa }} </td>
				<td> {{ $detail_payment->semester }} </td>
				<td> {{ $detail_payment->no_urut }} </td>
				<td> Rp. {{{ number_format($detail_payment->amount) }}} </td>
				<td> {{ $detail_payment->ket }} </td>
				<td>
					@if($detail_payment->status_approvals == 1)
                    <span title="Aktif" class="btn btn-success btn-sm">Disetujui</span>
					@elseif($detail_payment->status_approvals == 2)
                    <span title="Calon"class="btn btn-danger btn-sm">Tidak Disetujui</span>
					@else
                		<a title="Setujui Pembayaran" class="btn btn-success btn-sm" onclick="return confirm('Apakah anda yakin untuk menyetujui pembayaran ini?');" href='{{URL::action("admin\SppController@approve",array($detail_payment->id))}}'><i class="fa fa-check"></i></a>
                		<a title="Tidak Setujui Pembayaran" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin untuk tidak menyetujui pembayaran ini?');" href='{{URL::action("admin\SppController@disagree",array($detail_payment->id))}}'><i class="fa fa-times"></i></a>
					@endif
				</td>
			</tr>
			@endforeach
		</tstatus>
        </table>
		{!! $spp->render() !!}
      </div>
    </div>
  </div>
</div>
@endsection