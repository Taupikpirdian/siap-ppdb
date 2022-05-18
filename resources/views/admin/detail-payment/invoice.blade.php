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
		<h2><u><center>TANDA PEMBAYARAN</center></u></h2>
		<p><u><center>No. {{{$candidate->alias}}} / {{ Carbon\Carbon::now()->formatLocalized(' %m ') }} / {{ Carbon\Carbon::now()->formatLocalized(' %y ') }} / {{{$candidate->no_kwitansi}}} / {{{$candidate->id}}}</center></u></p>
		<br>
	<table>

	<tr>
		<td style="width: 120px">Diterima dari</td>
		<td>:</td>
		<td>{{{$candidate->nama}}} ( {{ Carbon\Carbon::parse($candidate->tgl_lahir)->formatLocalized('%d %B %Y') }} )</td>
	</tr>

	<!-- <tr>
		<td>Tanggal Lahir</td>
		<td>:</td>
		<td>{{ Carbon\Carbon::parse($candidate->tgl_lahir)->formatLocalized('%d %B %Y') }}</td>
	</tr> -->

	<tr>
		<td>Uang Sejumlah</td>
		<td>:</td>
		<td>Rp. {{{ number_format($candidate->cost_name) }}}</td>
	</tr>

	<tr>
		<td>Untuk Pembayaran</td>
		<td>:</td>
		<td>{{{$candidate->payment_name}}}</td>
	</tr>

	<tr>
		<td><b>Sekolah</b></td>
		<td>:</td>
		<td>{{{$candidate->nama_sekolah}}}</td>
	</tr>

	<tr>
		<td><b>Program</b></td>
		<td>:</td>
		<td>{{{$candidate->program_nama}}}</td>
	</tr>

	</table>

	<table>
		<tr>
			<td colspan="3" align=right>Bandung, {{ Carbon\Carbon::now()->formatLocalized('%d %B %Y') }}</td>
		</tr>
		<tr>
			<td style="width:50%; line-height: 1.8em;"> <br> </td>
			<td style="width:50%; line-height: 11em;"> </td>
			<td style="width:50%; line-height: 11em;">Kasir,</td>
		</tr>

		<tr>
			<td style="width:50%"> </td>
			<td style="width:50%"> </td>
			<td style="width:50%">({{{$name_user}}})</td>
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
		<h2><u><center>TANDA PEMBAYARAN</center></u></h2>
		<p><u><center>No. {{{$candidate->alias}}}/ {{ Carbon\Carbon::now()->formatLocalized(' %m ') }}/{{ Carbon\Carbon::now()->formatLocalized(' %y ') }} / {{{$candidate->no_kwitansi}}} / {{{$candidate->id}}}</center></u></p>
		<br>
	<table>

	<tr>
		<td style="width: 120px">Diterima dari</td>
		<td>:</td>
		<td>{{{$candidate->nama}}} ( {{ Carbon\Carbon::parse($candidate->tgl_lahir)->formatLocalized('%d %B %Y') }} )</td>
	</tr>

	<!-- <tr>
		<td>Tanggal Lahir</td>
		<td>:</td>
		<td>{{ Carbon\Carbon::parse($candidate->tgl_lahir)->formatLocalized('%d %B %Y') }}</td>
	</tr> -->

	<tr>
		<td>Uang Sejumlah</td>
		<td>:</td>
		<td>Rp. {{{ number_format($candidate->cost_name) }}} ;</td>
	</tr>

	<tr>
		<td>Untuk Pembayaran</td>
		<td>:</td>
		<td>{{{$candidate->payment_name}}}</td>
	</tr>

	<tr>
		<td><b>Sekolah</b></td>
		<td>:</td>
		<td>{{{$candidate->nama_sekolah}}}</td>
	</tr>

	<tr>
		<td><b>Program</b></td>
		<td>:</td>
		<td>{{{$candidate->program_nama}}}</td>
	</tr>

	</table>

	<table>
		<tr>
			<td colspan="3" align=right>Bandung, {{ Carbon\Carbon::now()->formatLocalized('%d %B %Y') }}</td>
		</tr>
		<tr>
			<td style="width:50%; line-height: 1.8em;"> <br> </td>
			<td style="width:50%; line-height: 11em;"> </td>
			<td style="width:50%; line-height: 11em;">Kasir,</td>
		</tr>

		<tr>
			<td style="width:50%"> </td>
			<td style="width:50%"> </td>
			<td style="width:50%">({{{$name_user}}})</td>
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