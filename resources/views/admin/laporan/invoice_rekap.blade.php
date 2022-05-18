<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>REKAP</title>
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
		<h2  style="font-size: 12px; line-height: 2.0em;"><u><b><center>REKAP</center></b></u></h2>
		<br>
    <table style="font-family: Arial; font-size: 13px;" class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <th rowspan="2" scope="col" class="border-1" style="width:10px; text-align: center;">No</th>
              <th rowspan="2" scope="col" class="border-1" style="text-align: center;">Sekolah</th>
              <th colspan="4" scope="col" class="border-1" style="text-align: center;">Biaya</th>
              <th rowspan="2" scope="col" class="border-1" style="text-align: center;">Jumlah (Rp)</th>
              <th rowspan="2" scope="col" class="border-1" style="text-align: center;">Tunggakan (Rp)</th>
              
            </tr>
            <tr>
              <th scope="col" class="border-1" style="text-align: center;">Formulir</th>
              <th scope="col" class="border-1" style="text-align: center;">Pendaftaran</th>
              <th scope="col" class="border-1" style="text-align: center;">Pendidikan</th>
              <th scope="col" class="border-1" style="text-align: center;">Lain-lain</th>
            </tr>
          </thead>
          <tstatus>
    	   @foreach($rekap as $i=>$list)
		     	<tr>
		         <td>{{ $i + 1 }}</td>
		     	   <td>{{ $list->nama_sekolah }}</td>
		         <td>Rp. {{{ number_format($list->total_formulir) }}}</td>	         
		         <td>Rp. {{{ number_format($list->total_register) }}}</td>	         
		         <td>Rp. {{{ number_format($list->total_spp) }}}</td>	         
		         <td>Rp. {{{ number_format(0) }}}</td>	         
		         <td>Rp. {{{ number_format($list->total_formulir +  $list->total_register + $list->total_spp) }}}</td>	         
		         <td>Rp. {{{ number_format($list->tunggakan) }}}</td>	         
		     	</tr>
  		   @endforeach
         <tr>
            <td colspan="2">Total</td>
            <td>Rp. {{{ number_format($formulirs) }}}</td>
            <td>Rp. {{{ number_format($pendaftarans) }}}</td>
            <td>Rp. {{{ number_format($pendidikans) }}}</td>
            <td>Rp. 0</td>
            <td>Rp. {{{ number_format($total_seluruh) }}}</td>
            <td>Rp. {{{ number_format($tunggkans) }}}</td>
          </tr>
		    </tstatus>
    </table>

        <!-- <table style="width:15%; float:left; font-family: Arial; font-size: 13px;" class="table table table-bordered table-striped table-hover table-condensed tfix mb-1" style="font-family: Arial; font-size: 13px">
          <thead class="bg-light">
            <tr>
              <th style="height:96px" rowspan="2" scope="col" class="border-1" style="text-align: center;">Tunggakan</th>
            </tr>
          </thead>
          <tstatus>
         @if($rekap->isEmpty())
         @else
          @foreach($tunggakan as $i=>$list2)
            <tr>
              <td>Rp. {{{ number_format($list2->tunggakan) }}}</td>	         
            </tr>
          @endforeach
         @endif
		    </tstatus>
        </table> -->
		

    </main>
    <footer>
      <!-- Invoice was created on a computer and is valid without the signature and seal. -->
    </footer>
    
	<script>
		window.print()
	</script>
  </body>
</html>