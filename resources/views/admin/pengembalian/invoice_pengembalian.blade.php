<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PENGEMBALIAN</title>
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
		<h2  style="font-size: 12px; line-height: 2.0em;"><u><b><center>LAPORAN PENGEMBALIAN</center></b></u></h2>
		<br>
	<table class="table table-bordered" style="font-family: Arial; font-size: 13px;">
          <thead class="bg-light">
            <tr>
              <th scope="col" class="border-0" style="width:10px;">No</th>
              <th scope="col" class="border-0" style="text-align: center;">Nama</th>
              <th scope="col" class="border-0" style="text-align: center;">Jenis Pembayaran</th>
              <th scope="col" class="border-0" style="text-align: center;">Sekolah</th>
              <th scope="col" class="border-0" style="text-align: center;">Tanggal Pengembalian</th>
              <th scope="col" class="border-0" style="text-align: center;">Jumlah</th>
              <th scope="col" class="border-0" style="text-align: center;">Kembali</th>
            </tr>
          </thead>
          <tstatus>
			   @foreach($uang_keluar as $i=>$uang_keluars)
		     	<tr>
		     	 <td style="text-align: center;">{{ ($uang_keluar->currentpage()-1) * $uang_keluar->perpage() + $i + 1 }}</td>
		         <td> {{ $uang_keluars->nama_siswa }} </td>
		         <td style="text-align: center;"> {{ $uang_keluars->name }} </td>
		         <td style="text-align: center;"> {{ $uang_keluars->nama_sekolah }} </td>
		         <td style="text-align: center;"> {{ Carbon\Carbon::parse($uang_keluars->tgl_pengembalian)->formatLocalized('%d %B %Y')}} </td>
		         <td style="text-align: center;"> Rp. {{ number_format($uang_keluars->jumlah) }}</td>
		         <td style="text-align: center;"> Rp. {{ number_format($uang_keluars->denda) }}</td>        
		     	</tr>
			   @endforeach
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