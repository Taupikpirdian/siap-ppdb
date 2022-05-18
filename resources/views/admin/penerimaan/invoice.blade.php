<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Penerimaan</title>
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
			<h1 style="color:#000; line-height: 1.8em"><b>YAYASAN PENDIDIKAN PGII-BANDUNG</b></h1>
			<h5>Jl. Panatayuda No. 2 Telp. 2500604 (Hunting System)</h5>
			<h5>Jl. Pahlawan Blk. 17, Telp. 774994</h5>
			<h5>Jl. H. Hasan No. 5, E-mail: pgii-bandung@yahoo.co.id</h5>
			<h5>Bandung - Indonesia</h5>
		</div>
	</div>
    </header>
    <main>
		<h2><u><center>BUKTI PENERIMAAN KAS</center></u></h2>
		<br>
	<table>

	<tr>
		<td>Diterima dari</td>
		<td>:</td>
		<td>{{{$penerimaans->asal_penerimaan}}}</td>
	</tr>

	<tr>
		<td>Tunai</td>
		<td>:</td>
		<td>Rp {{ number_format($penerimaans->jumlah) }}</td>
	</tr>

	<tr>
		<td>Cek / Giro</td>
		<td>:</td>
		<td>Rp. {!! number_format($penerimaans->giro) !!}</td>
	</tr>

	<tr>
		<td></td>
		<td></td>
		<td><hr></td>
	</tr>


	<tr>
		<td>Jumlah</td>
		<td>:</td>
		<td><b><i>Rp {{ number_format($penerimaans->jumlah+$penerimaans->giro) }}</i></b></td>
	</tr>

	<tr>
		<td style="width:26%;"><b>Terbilang</b></td>
		<td>:</td>
		<td colspan="2" style="border: 1px solid black;"><b><i>{!! $penerimaans->terbilang !!} Rupiah</i></b></td>
	</tr>

	<tr>
		<td>Uraian / Keterangan</td>
		<td>:</td>
		<td>{!! $penerimaans->ket !!}</td>
	</tr>
	</table>

	<table>
		<tr>
			<td colspan="3" align=right>Bandung, {{ Carbon\Carbon::now()->formatLocalized('%d %B %Y') }}</td>
		</tr>
		<tr>
			<td style="width:50%; line-height: 1.8em;">Mengetahui, <br>Bendahara/Staff Keuangan,</td>
			<td style="width:50%; line-height: 11em;">Pembukuan II,</td>
			<td style="width:50%">Kasir,</td>
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
			<h1 style="color:#000; line-height: 1.8em"><b>YAYASAN PENDIDIKAN PGII-BANDUNG</b></h1>
			<h5>Jl. Panatayuda No. 2 Telp. 2500604 (Hunting System)</h5>
			<h5>Jl. Pahlawan Blk. 17, Telp. 774994</h5>
			<h5>Jl. H. Hasan No. 5, E-mail: pgii-bandung@yahoo.co.id</h5>
			<h5>Bandung - Indonesia</h5>
		</div>
	</div>
    </header>
    <main>
		<h2><u><center>BUKTI PENERIMAAN KAS</center></u></h2>
		<br>
	<table>

	<tr>
		<td>Diterima dari</td>
		<td>:</td>
		<td>{{{$penerimaans->asal_penerimaan}}}</td>
	</tr>

	<tr>
		<td>Tunai</td>
		<td>:</td>
		<td>Rp {{ number_format($penerimaans->jumlah) }}</td>
	</tr>

	<tr>
		<td>Cek / Giro</td>
		<td>:</td>
		<td>Rp. {!! number_format($penerimaans->giro) !!}</td>
	</tr>

	<tr>
		<td></td>
		<td></td>
		<td><hr></td>
	</tr>

	<tr>
		<td>Jumlah</td>
		<td>:</td>
		<td><b><i>Rp {{ number_format($penerimaans->jumlah+$penerimaans->giro) }}</i></b></td>
	</tr>

	<tr>
		<td style="width:26%;"><b>Terbilang</b></td>
		<td>:</td>
		<td colspan="2" style="border: 1px solid black;"><b><i>{!! $penerimaans->terbilang !!} Rupiah</i></b></td>
	</tr>

	<tr>
		<td>Uraian / Keterangan</td>
		<td>:</td>
		<td>{!! $penerimaans->ket !!}</td>
	</tr>
	</table>

	<table>
		<tr>
			<td colspan="3" align=right>Bandung, {{ Carbon\Carbon::now()->formatLocalized('%d %B %Y') }}</td>
		</tr>
		<tr>
			<td style="width:50%; line-height: 1.8em;">Mengetahui, <br>Bendahara/Staff Keuangan,</td>
			<td style="width:50%; line-height: 11em;">Pembukuan II,</td>
			<td style="width:50%">Kasir,</td>
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