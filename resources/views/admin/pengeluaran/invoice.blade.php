<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Pengeluaran</title>
	{!! Html::style('invoice/css/style.css') !!}
  </head>
  <body>
    <header class="clearfix">
      <!-- <div id="logo">
        <img src="{{URL::asset('invoice/image/logo.png')}}">
      </div> -->
	<div class="header-kop">
		<div class="header-left">
			<img src="{{URL::asset('invoice/image/logo.png')}}">
		</div>
		<div class="header-right">
			<h1 style="color:#000"><b>YAYASAN PENDIDIKAN PGII-BANDUNG</b></h1>
			<h5>Jl. Panatayuda No. 2 Telp. 2500604 (Hunting System)</h5>
			<h5>Jl. Pahlawan Blk. 17, Telp. 774994</h5>
			<h5>Jl. H. Hasan No. 5, E-mail: pgii-bandung@yahoo.co.id</h5>
			<h5>Bandung - Indonesia</h5>
		</div>
	</div>
    </header>
    <main>
		<h2><u><center>BUKTI PENGELUARAN KAS</center></u></h2>
		<br>
	<table>

	<tr>
		<td>Ditujukan Untuk</td>
		<td>:</td>
		<td>{{{$luaran->asal_pengeluaran}}}</td>
	</tr>

	<tr>
		<td>Tunai</td>
		<td>:</td>
		<td>Rp {{ number_format($luaran->jumlah) }}</td>
	</tr>

	<tr>
		<td>Cek / Giro</td>
		<td>:</td>
		<td>Rp. {!! number_format($luaran->giro) !!}</td>
		<td>(No Giro. {!! $luaran->no_giro !!}  ) </td>
	</tr>

	<tr>
		<td></td>
		<td></td>
		<td><hr></td>
	</tr>

	<tr>
		<td>Jumlah</td>
		<td>:</td>
		<td><b><i>Rp {{ number_format($luaran->jumlah+$luaran->giro) }}</i></b></td>
	</tr>

	<tr>
		<td style="width:26%;"><b>Terbilang</b></td>
		<td>:</td>
		<td colspan="2" style="border: 1px solid black;"><b><i>{!! $luaran->terbilang !!} Rupiah</i></b></td>
	</tr>

	<tr>
		<td>Uraian / Keterangan</td>
		<td>:</td>
		<td>{!! $luaran->ket !!}</td>
	</tr>
	</table>

	<table style="line-height: 7.8em;">
		<tr>
			<td colspan="3" align=right>Bandung, {{ Carbon\Carbon::now()->formatLocalized('%d %B %Y') }}</td>
		</tr>
		<tr>
			<td style="width:50%; line-height: 1.8em;">Mengetahui, <br>Bendahara/Staff Keuangan,</td>
			<td style="width:50%; line-height: 11em;">Kasir,</td>
			<td style="width:50%">Penerima,</td>
		</tr>

		<tr>
			<td style="width:50%">(.......................................)</td>
			<td style="width:50%">(.......................................)</td>
			<td style="width:50%">(.......................................)</td>
		</tr>
	</table>

    </main>
    <footer>
      <!-- Invoice was created on a computer and is valid without the signature and seal. -->
    </footer>

	<br>
    <br>
    <br>
    <hr style="border-top: 3px dotted #000;">
    <br>
    <br>
    <br>

    <header class="clearfix">
      <!-- <div id="logo">
        <img src="{{URL::asset('invoice/image/logo.png')}}">
      </div> -->
	<div class="header-kop">
		<div class="header-left">
			<img src="{{URL::asset('invoice/image/logo.png')}}">
		</div>
		<div class="header-right">
			<h1 style="color:#000"><b>YAYASAN PENDIDIKAN PGII-BANDUNG</b></h1>
			<h5>Jl. Panatayuda No. 2 Telp. 2500604 (Hunting System)</h5>
			<h5>Jl. Pahlawan Blk. 17, Telp. 774994</h5>
			<h5>Jl. H. Hasan No. 5, E-mail: pgii-bandung@yahoo.co.id</h5>
			<h5>Bandung - Indonesia</h5>
		</div>
	</div>
    </header>
    <main>
		<h2><u><center>BUKTI PENGELUARAN KAS</center></u></h2>
		<br>
	<table>

	<tr>
		<td>Diterima dari</td>
		<td>:</td>
		<td>{{{$luaran->asal_penerimaan}}}</td>
	</tr>

	<tr>
		<td>Tunai</td>
		<td>:</td>
		<td>Rp {{ number_format($luaran->jumlah) }}</td>
	</tr>

	<tr>
		<td>Cek / Giro</td>
		<td>:</td>
		<td>Rp. {!! number_format($luaran->giro) !!}</td>
		<td>(No. {!! $luaran->no_giro !!})</td>
	</tr>

	<tr>
		<td></td>
		<td></td>
		<td><hr></td>
	</tr>

	<tr>
		<td>Jumlah</td>
		<td>:</td>
		<td><b><i>Rp {{ number_format($luaran->jumlah+$luaran->giro) }}</i></b></td>
	</tr>

	<tr>
		<td style="width:26%;"><b>Terbilang</b></td>
		<td>:</td>
		<td colspan="2" style="border: 1px solid black;"><b><i>{!! $luaran->terbilang !!}</i></b></td>
	</tr>

	<tr>
		<td>Uraian / Keterangan</td>
		<td>:</td>
		<td>{!! $luaran->ket !!}</td>
	</tr>
	</table>

	<table style="line-height: 7.8em;">
		<tr>
			<td colspan="3" align=right>Bandung, {{ Carbon\Carbon::now()->formatLocalized('%d %B %Y') }}</td>
		</tr>
		<tr>
			<td style="width:50%; line-height: 1.8em;">Mengetahui, <br>Bendahara/Staff Keuangan,</td>
			<td style="width:50%; line-height: 11em;">Kasir,</td>
			<td style="width:50%">Penerima,</td>
		</tr>

		<tr>
			<td style="width:50%">(.......................................)</td>
			<td style="width:50%">(.......................................)</td>
			<td style="width:50%">(.......................................)</td>
		</tr>
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