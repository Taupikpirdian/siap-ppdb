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
		<p><u><center>No. SPP / {{{$pay->no_kwitansi}}} / {{ Carbon\Carbon::now()->formatLocalized(' %m ') }} / {{ Carbon\Carbon::now()->formatLocalized(' %y ') }}</center></u></p>
		<br>
	<table>

	<tr>
		<td style="width: 120px">Diterima dari</td>
		<td>:</td>
		<td>{{{$pay->nama_siswa}}} ( {{ Carbon\Carbon::parse($pay->tgl_lahir)->formatLocalized('%d %B %Y') }} )</td>
	</tr>

	<!-- <tr>
		<td>Tanggal Lahir</td>
		<td>:</td>
		<td> </td>
	</tr> -->

	<tr>
		<td>Uang Sejumlah</td>
		<td>:</td>
		<td>Rp. {{{ number_format($pay->amount)}}}    ;</td>
	</tr>


	<tr>
		<td>Untuk Pembayaran</td>
		<td>:</td>
		<td>{{{$pay->payment_name}}}  </td>
	</tr>

	<tr>
		<td>Semester</td>
		<td>:</td>
		<td>{{{$pay->semester}}}</td>
	</tr>

	<tr>
		<td><b>Sekolah</b></td>
		<td>:</td>
		<td> {{{$pay->nama_sekolah}}}  </td>
	</tr>

	<tr>
		<td><b>Program</b></td>
		<td>:</td>
		<td>{{{$pay->program_nama}}}  </td>
	</tr>

	</table>
	<div>
		<div style="width:50%; float:left;">
		<b><u>History Pembayaran:</u></b>
			<table>
				<tr>
					@if($spp1 != 0)
					<td>Semester 1: Rp. {{{ number_format($spp1)}}} {{ $status_spp1 }}</td>
					@endif
					@if($spp4 != 0)
					<td>Semester 4: Rp. {{{ number_format($spp4)}}} {{ $status_spp4 }}</td>
					@endif
				</tr>
				<tr>
					@if($spp2 != 0)
					<td>Semester 2: Rp. {{{ number_format($spp2)}}} {{ $status_spp2 }}</td>
					@endif
					@if($spp5 != 0)
					<td>Semester 5: Rp. {{{ number_format($spp5)}}} {{ $status_spp5 }}</td>
					@endif
				</tr>
				<tr>
					@if($spp3 != 0)
					<td>Semester 3: Rp. {{{ number_format($spp3)}}} {{ $status_spp3 }}</td>
					@endif
					@if($spp6 != 0)
					<td>Semester 6: Rp. {{{ number_format($spp6)}}} {{ $status_spp6 }}</td>
					@endif
				</tr>
			</table>
		</div>
		<div style="width:50%; float:left;">
			<table>
				<tr>
					<td colspan="3" align=right>Bandung, {{ Carbon\Carbon::parse($pay->tgl_bayar)->formatLocalized('%d %B %Y')}}</td>
				</tr>
				<tr>
					<td colspan="3" align=right>Kasir,</td>
				</tr>
				<tr>
					<td colspan="3" align=right height="50px"></td>
				</tr>
				<tr>
					<td colspan="3" align=right>({{{$name_user}}})</td>
				</tr>
			</table>
		</div>
	</div>

    </main>
    <footer>
      <!-- Invoice was created on a computer and is valid without the signature and seal. -->
    </footer>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
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
		<p><u><center>No. SPP/{{{$pay->no_kwitansi}}}/{{ Carbon\Carbon::now()->formatLocalized(' %m ') }}/{{ Carbon\Carbon::now()->formatLocalized(' %y ') }}</center></u></p>
		<br>
	<table>

	<tr>
		<td style="width: 120px">Diterima dari</td>
		<td>:</td>
		<td>{{{$pay->nama_siswa}}} ( {{ Carbon\Carbon::parse($pay->tgl_lahir)->formatLocalized('%d %B %Y') }} )</td>
	</tr>

	<!-- <tr>
		<td>Tanggal Lahir</td>
		<td>:</td>
		<td> </td>
	</tr> -->

	<tr>
		<td>Uang Sejumlah</td>
		<td>:</td>
		<td>Rp. {{{ number_format($pay->amount)}}}    ;</td>
	</tr>

	<tr>
		<td>Untuk Pembayaran</td>
		<td>:</td>
		<td>{{{$pay->payment_name}}}  </td>
	</tr>

	<tr>
		<td>Semester</td>
		<td>:</td>
		<td>{{{$pay->semester}}}    ;</td>
	</tr>

	<tr>
		<td><b>Sekolah</b></td>
		<td>:</td>
		<td> {{{$pay->nama_sekolah}}}  </td>
	</tr>

	<tr>
		<td><b>Program</b></td>
		<td>:</td>
		<td>{{{$pay->program_nama}}}  </td>
	</tr>

	</table>
	<div>
		<div style="width:50%; float:left;">
		<b><u>History Pembayaran:</u></b>
			<table>
				<tr>
					@if($spp1 != 0)
					<td>Semester 1: Rp. {{{ number_format($spp1)}}} {{ $status_spp1 }}</td>
					@endif
					@if($spp4 != 0)
					<td>Semester 4: Rp. {{{ number_format($spp4)}}} {{ $status_spp4 }}</td>
					@endif
				</tr>
				<tr>
					@if($spp2 != 0)
					<td>Semester 2: Rp. {{{ number_format($spp2)}}} {{ $status_spp2 }}</td>
					@endif
					@if($spp5 != 0)
					<td>Semester 5: Rp. {{{ number_format($spp5)}}} {{ $status_spp5 }}</td>
					@endif
				</tr>
				<tr>
					@if($spp3 != 0)
					<td>Semester 3: Rp. {{{ number_format($spp3)}}} {{ $status_spp3 }}</td>
					@endif
					@if($spp6 != 0)
					<td>Semester 6: Rp. {{{ number_format($spp6)}}} {{ $status_spp6 }}</td>
					@endif
				</tr>
			</table>
		</div>
		<div style="width:50%; float:left;">
			<table>
				<tr>
					<td colspan="3" align=right>Bandung, {{ Carbon\Carbon::parse($pay->tgl_bayar)->formatLocalized('%d %B %Y')}}</td>
				</tr>
				<tr>
					<td colspan="3" align=right>Kasir,</td>
				</tr>
				<tr>
					<td colspan="3" align=right height="50px"></td>
				</tr>
				<tr>
					<td colspan="3" align=right>({{{$name_user}}})</td>
				</tr>
			</table>
		</div>
	</div>

    </main>
    <footer>
      <!-- Invoice was created on a computer and is valid without the signature and seal. -->
    </footer>
	<script>
		window.print()
	</script>
  </body>
</html>