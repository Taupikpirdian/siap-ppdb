@extends('admin.admin')
@section('content')
<head>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet">   
</head>
<div class="col-lg-6">
	{!! Form::model($user,['method'=>'put', 'files'=> 'true', 'action'=>['admin\UserController@updateuserprofile',$user->id]]) !!}
	{{ csrf_field() }}
  <div class="element-wrapper">
    <h6 class="element-header">
      EDIT USER
    </h6>
	<div class="col-md-12 element-box">
	    <form>
			<h5 class="form-header">
				USER
			</h5>
				<div class="form-group">
				<label for=""> Nama</label>
				<input class="form-control" placeholder="Masukan Nama" name="name" value="{{{$user->name}}}">
			</div>
			<div class="form-group">
				<label for=""> Address</label>
				<input class="form-control" placeholder="Masukan Address" name="address" value="{{{$user->address}}}">
			</div>
         	<div class="form-group">
	            <label for=""> Tanggal Lahir</label>
	            <input type="date" class="form-control" placeholder="Masukan Birth Date" name="birth_date" value="{{{$user->birth_date}}}">
      		</div>
         	<div class="form-group">
	            <label for=""> Tempat Lahir</label>
	            <input class="form-control" placeholder="Masukan Place Birth" name="Tempat Lahir" value="{{{$user->place_birth}}}">
        	</div>
         	<div class="form-group">
	            <label for=""> Phone</label>
	            <input class="form-control" placeholder="Masukan Phone" name="phone" value="{{{$user->phone}}}">
        	</div>
        	<div class='form-group clearfix'>
				<div class='col-md-12'>
	                <label for="exampleInputEmail1">Bio</label>
	                {{ Form::textarea("bio", null,['class' => 'form-control required', 'id' => 'deskripsi']) }}
	                <span class="error">{{$errors->first('bio')}}</span>
          		</div>
          	</div>
         	<div class="form-group">
	            <label for=""> Foto</label>
	            <input type="file" class="form-control" placeholder="Masukan Foto" name="foto" value="{{{$user->foto}}}">
        	</div>
			<div class="col-md-12 form-buttons-w">
				<button class="btn btn-primary" type="submit"> Submit</button>
			</div>
	    </form>
	</div>
  </div>
	{!! Form::close() !!}  
</div>
@endsection

@section('js')
<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'deskripsi' );
</script>

<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'deskripsi1' );
</script>
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
@endsection