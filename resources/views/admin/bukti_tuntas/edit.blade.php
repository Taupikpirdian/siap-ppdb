@extends('admin.admin')
@section('content')
<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script> 
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet">   
</head>
<div class="col-lg-6">
	{!! Form::model($bukti,['method'=>'put', 'files'=> 'true', 'action'=>['admin\BuktiTuntasController@update',$bukti->id]]) !!}
	{{ csrf_field() }}
		<div class="element-wrapper">
			<h6 class="element-header">
			</h6>
			<div class="element-box">
				<form>
					<h5 class="form-header">
					Edit Penerimaan
					</h5>
					<div class="form-group">
						<label for=""> Kode Penerimaan</label>
						{{ Form::select('id_kode', $kode_penerimas, null,['class' => 'form-control', 'required', 'value'=>'']) }}
						@if ($errors->has('id_kode'))
							<div class="help-block form-text with-errors form-control-feedback">
								<ul class="list-unstyled text-danger">
									<li>{{ $errors->first('id_kode') }}</li>
								</ul>
							</div>
						@endif
					</div>
					<div class="form-group">
						<label for=""> Jumlah Penyaluran</label>
						<input class="form-control" placeholder="Jumlah Penyaluran" name="jumlah_penyaluran" data-type="number" value="{{{$bukti->jumlah_penyaluran}}}">
						@if ($errors->has('jumlah_penyaluran'))
							<div class="help-block form-text with-errors form-control-feedback">
								<ul class="list-unstyled text-danger">
									<li>{{ $errors->first('jumlah_penyaluran') }}</li>
								</ul>
							</div>
						@endif
					</div>
					<div class="row">
						<div class='col-md-6'>
							<div class="form-group">
								<label for=""> Input File</label>
								<input type="file" class="form-control" name="file" value="{{{$bukti->file}}}">
								@if ($errors->has('file'))
			        				<div class="help-block form-text with-errors form-control-feedback">
			          					<ul class="list-unstyled text-danger">
			            					<li>{{ $errors->first('file') }}</li>
			          					</ul>
			        				</div>
			      				@endif
							</div>	
						</div>	
					</div>	
					<div class="form-buttons-w">
						<button class="btn btn-primary" type="submit"> Submit</button>
					</div>
				</form>
			</div>
		</div>
	{!! Form::close() !!}  
</div>
@endsection
@section('js')
<script type="text/javascript">
    $( "#datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true,
      yearRange: '-80:+0',
      dateFormat: "yy-mm-dd"
    });
    $( "#datepicker2" ).datepicker({
      changeMonth: true,
      changeYear: true,
      yearRange: '-80:+0',
      dateFormat: "yy-mm-dd"
    });
</script>
<script>
    $(document).ready(function(){
      $("input[data-type='number']").keyup(function(event){
        // skip for arrow keys
        if(event.which >= 37 && event.which <= 40){
            event.preventDefault();
        }
        var $this = $(this);
        var num = $this.val().replace(/,/gi, "");
        var num2 = num.split(/(?=(?:\d{3})+$)/).join(",");
        console.log(num2);
        // the following line has been simplified. Revision history contains original.
        $this.val(num2);
        var bla = $('#id_step2-number_4').val(num);
        console.log(num);
      });
    });
</script>
<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'deskripsi' );
</script>
@endsection