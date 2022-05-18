<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>TUNGGAKAN</title>
	{!! Html::style('invoice/css/style.css') !!}
    {!! Html::style('css/bootstrap.min.css') !!}

	<!-- {!! Html::style('styles/shards-dashboards.1.1.0.min.css') !!} -->
    <!-- {!! Html::style('styles/extras.1.1.0.min.css') !!} -->
  </head>
  <body class="klm">
    <header class="clearfix">
      <!-- <div id="logo">
        <img src="{{URL::asset('invoice/image/logo.png')}}">
      </div> -->
	<div class="header-kop">
		<div class="header-left">
			<img src="{{URL::asset('invoice/image/logo.png')}}">
		</div>
		<div class="header-right">
			<h1 style="color:#000; line-height: 1.8em; font-size: 16px;"><b>YAYASAN PENDIDIKAN PGII-BANDUNG</b></h1>
			<h5 style="font-size: 12px;">Jl. Panatayuda No. 2 Telp. 2500604 (Hunting System)</h5>
			<h5 style="font-size: 12px;">Jl. Pahlawan Blk. 17, Telp. 774994</h5>
			<h5 style="font-size: 12px;">Jl. H. Hasan No. 5, E-mail: pgii-bandung@yahoo.co.id</h5>
			<h5 style="font-size: 12px;">Bandung - Indonesia</h5>
		</div>
	</div>
    </header>
    <main>
		<h2  style="font-size: 12px; line-height: 2.0em;"><u><b><center>TUNGGAKAN</center></b></u></h2>
		<br>
	<table class="table table-bordered" style="font-family: Arial; font-size: 13px;">
          <thead class="bg-light">
            <tr>
              <th rowspan="2" scope="col" class="border-1" style="width:10px; text-align: center;">No</th>
              <th rowspan="2" scope="col" class="border-1" style="text-align: center;">NIS</th>
              <th rowspan="2" scope="col" class="border-1" style="text-align: center;">Nama Siswa</th>
              <th rowspan="2" scope="col" class="border-1" style="text-align: center;">Sekolah</th>
              <th colspan="2" scope="col" class="border-1" style="text-align: center;">Biaya SPP</th>
              <th rowspan="2" scope="col" class="border-1" style="text-align: center;">Tunggakan</th>
            </tr>
            <tr>
              <th scope="col" class="border-1" style="text-align: center;">Yang harus dibayar</th>
              <th scope="col" class="border-1" style="text-align: center;">Yang sudah dibayar</th>
            </tr>
          </thead>
          <tstatus>
         @foreach($tunggakan as $i=>$list)
          <tr>
		     	   <td>{{ $i + 1 }}</td>
             <td style="text-align: center;">{{ $list->npm }}</td>
             <td>{{ $list->nama_siswa }}</td>
             <td style="text-align: center;">{{ $list->nama_sekolah }}</td>          
             <td style="text-align: center;">Rp. {{{ number_format($list->semester*$list->spp) }}}</td>          
             <td style="text-align: center;">Rp. {{{ number_format($list->payment_amount) }}}</td>          
             <td style="text-align: center;">Rp. {{{ number_format(($list->semester*$list->spp)-($list->payment_amount)) }}}</td>           
          </tr>
         @endforeach
          <tr>
             <td colspan="4">Jumlah</td>
             <td style="text-align: center;">Rp. {{{ number_format($spps) }}}</td>
             <td style="text-align: center;">Rp. {{{ number_format($has_payment_spp) }}}</td>
             <td style="text-align: center;">Rp. {{{ number_format($mod) }}}</td>
          </tr>
    </tstatus>
        </table>
    </main>
    <footer>
      <!-- Invoice was created on a computer and is valid without the signature and seal. -->
    </footer>
    
	<script>
		window.print()
	</script>
  </body>
</html>