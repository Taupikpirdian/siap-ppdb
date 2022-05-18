<html>
<head>
	<title>Laporan Siswa</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>
	<center>
		<h5>Laporan Siswa</h4>
		<h6><a target="_blank" href="#">Nama Sekolah</a></h5>
	</center>
 
	<table class='table table-bordered'>
				<thead>
					<tr>
						<th>No.</th>
				       	<th><b>NPM</b></th>
						<th><b>Nama Siswa</b></th>
				       	<th><b>RFID</b></th>
				       	<th><b>Program</b></th>
				       	<th><b>Tempat Lahir</b></th>
				       	<th><b>Tanggal Lahir</b></th>
				       	<th><b>Kelas</b></th>
				       	<th><b>Alamat</b></th>
				       	<th><b>Kecamatan</b></th>
				       	<th><b>Kota/Kab</b></th>
				       </tr>
				</thead>
		<tstatus>
				@foreach($siswa as $i=>$students)
				    <tr>
				     	 <td>{{$i+1}}</td>
				         <td> {{ $students->npm }} </td>
				         <td> {{ $students->nama_siswa }} </td>
				         <td> {{ $students->rfid }} </td>
				         <td> {{ $students->program }} </td>
				         <td> {{ $students->tempat_lahir }} </td>
				         <td> {{ $students->tgl_lahir }} </td>
				         <td> {{ $students->kelas }} </td>
				         <td> {{ $students->alamat }} </td>
				         <td> {{ $students->kecamatan }} </td>
				         <td> {{ $students->kota_kab }} </td>
					</tr>
			@endforeach
		</tstatus>
	</table>
 
</body>
</html>