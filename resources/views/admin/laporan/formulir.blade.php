@extends('admin.admin')
@section('content')
<div class="page-header row no-gutters py-4">
  <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
    <h3 class="page-title">List Pembelian Formulir Harian</h3>
  </div>
</div>

<div class="row">
  <div class="col">
    <div class="card card-small mb-5">
      <div class="card-header border-bottom">
        <div class="pull-left">
          
        <div class="col-md-8">
          <div class="input-group input-daterange">
              <input type="text" name="from_date" id="from_date" readonly class="form-control" />
              <div class="input-group-addon" style="margin:2px;">-   Sampai Dengan   -</div>
              <input type="text"  name="to_date" id="to_date" readonly class="form-control" />
              
              <div class="input-group-addon" style="color:white">-</div>
              
              <button  title="Filter" style="margin:2px;" type="button" name="filter" id="filter" class="btn btn-info btn-sm"><i class="fas fa-filter"></i></button>
              <a title="Refresh" href="{{URL::to('/formulir-harian')}}" style="margin:2px;" class="button button-primary"><i class="fas fa-sync"></i></a>
              <!-- <button type="button" name="refresh" id="refresh" class="btn btn-warning btn-sm">Refresh</button> -->

              <a title="Download Excel" href="{{ route('export.formulir-harian',['type'=>'xls']) }}" class="btn button-primary btn-sm" style="margin:2px;" target="_blank"><i class="fa fa-download fa-xs"></i></a>
              <a title="Print " style="margin:2px;" class="btn btn-warning btn-sm" target="_blank" href='{{URL::action("admin\LaporanController@invoice")}}'><i class="fa fa-print fa-xs" style="color: white"></i></a>
          </div>
        </div>
      
        <div class='form-group clearfix'>
          <div class='col-md-5'>
          </div>
        </div>
      </div>
      
      <div class="col-md-5">Total Data - <b><span id="total_records"></span></b></div>
      
      <div class="card-body p-0 pb-3 text-center">
        <table class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <!-- <th scope="col" class="border-0" style="width:10px;">No</th> -->
              <th scope="col" class="border-0">Tanggal Bayar</th>
              <th scope="col" class="border-0">Nama</th>
              <th scope="col" class="border-0">Sekolah</th>
              <th scope="col" class="border-0">Jenis Pembayaran</th>
              <th scope="col" class="border-0">Biaya</th>
            </tr>
          </thead>
          <tstatus>
          <tbody>
          @foreach($formulir as $i=>$formulirs)
            <tr>
            <!-- <td>{{ ($formulir->currentpage()-1) * $formulir->perpage() + $i + 1 }}</td> -->
              <td> {{ Carbon\Carbon::parse($formulirs->tgl_bayar)->formatLocalized('%d %B %Y')}} </td>
              <td> {{ $formulirs->nama_siswa }} </td>
              <td> {{ $formulirs->nama_sekolah }} </td>
              <td> {{ $formulirs->nm_payment }} </td>
              <td> Rp. {{{ number_format($formulirs->cost_id) }}} </td>
            </tr>
          @endforeach
          </tbody>
    </tstatus>
        </table>
      {!! $formulir->render() !!}
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
  $(document).ready(function(){

  var date = new Date();

  $('.input-daterange').datepicker({
    todayBtn: 'linked',
    format: 'yyyy-mm-dd',
    autoclose: true
  });

  var _token = $('input[name="_token"]').val();

  fetch_data();

  function fetch_data(from_date = '', to_date = '')
  {
    $.ajax({
    url:"{{ route('daterange.fetch_data') }}",
    method:"POST",
    data:{from_date:from_date, to_date:to_date, _token:_token},
    dataType:"json",
    success:function(data)
    {
      var output = '';
      $('#total_records').text(data.length);
      for(var count = 0; count < data.length; count++)
      {
      output += '<tr>';
      output += '<td>' + data[count].tgl_bayar + '</td>';
      output += '<td>' + data[count].nama_siswa + '</td>';
      output += '<td>' + data[count].sekolah + '</td>';
      output += '<td>' + data[count].payment + '</td>';
      output += '<td>' + data[count].cost_id + '</td></tr>';
      }
      $('tbody').html(output);
    }
    })
  }

  $('#filter').click(function(){
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    if(from_date != '' &&  to_date != '')
    {
    fetch_data(from_date, to_date);
    }
    else
    {
    alert('Both Date is required');
    }
  });

  $('#refresh').click(function(){
    $('#from_date').val('');
    $('#to_date').val('');
    fetch_data();
  });
  });
</script>
@endsection
