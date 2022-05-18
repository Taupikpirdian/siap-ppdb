@extends('admin.admin')
@section('content')

<div class="col-lg-12">
	{{ Form::open(array('url' => 'user_profile/create', 'files' => true, 'method' => 'post')) }}
	<div class="element-box">
	<form>
		<h5 class="form-header">
			CREATE USER MAHASISWA
		</h5>
		<div class="col-md-12">
			<label for=""> Nama</label>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<input class="form-control " placeholder="Nama" name="user_id">
				@if ($errors->has('user_id'))
					<div class="help-block form-text with-errors form-control-feedback">
						<ul class="list-unstyled text-danger">
							<li>{{ $errors->first('user_id') }}</li>
						</ul>
					</div>
				@endif
			</div>
		</div>
		<div class="col-md-12">
			<label for=""> Alamat</label>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<input class="form-control" placeholder="Alamat" name="address">
				@if ($errors->has('address'))
					<div class="help-block form-text with-errors form-control-feedback">
						<ul class="list-unstyled text-danger">
							<li>{{ $errors->first('address') }}</li>
						</ul>
					</div>
				@endif
			</div>
		</div>
		<div class="col-md-12">
			<label for=""> Birth Date</label>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<input type="date" class="form-control" placeholder="Birth Date" name="birth_date">
				@if ($errors->has('birth_date'))
					<div class="help-block form-text with-errors form-control-feedback">
						<ul class="list-unstyled text-danger">
							<li>{{ $errors->first('birth_date') }}</li>
						</ul>
					</div>
				@endif
			</div>
		</div>
		<div class="col-md-12">
			<label for=""> Tempat Lahir</label>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<input class="form-control" placeholder="Tempat Lahir" name="place_birth">
				@if ($errors->has('place_birth'))
					<div class="help-block form-text with-errors form-control-feedback">
						<ul class="list-unstyled text-danger">
							<li>{{ $errors->first('place_birth') }}</li>
						</ul>
					</div>
				@endif
			</div>
		</div>
		<div class="col-md-12">
			<label for=""> Phone</label>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<input class="form-control" placeholder="Phone" name="phone">
				@if ($errors->has('phone'))
					<div class="help-block form-text with-errors form-control-feedback">
						<ul class="list-unstyled text-danger">
							<li>{{ $errors->first('phone') }}</li>
						</ul>
					</div>
				@endif
			</div>
		</div>
		<div class="col-md-12">
			<label for=""> Status</label>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<input class="form-control" placeholder="Status" name="user_status_id">
				@if ($errors->has('user_status_id'))
					<div class="help-block form-text with-errors form-control-feedback">
						<ul class="list-unstyled text-danger">
							<li>{{ $errors->first('user_status_id') }}</li>
						</ul>
					</div>
				@endif
			</div>
		</div>
		<div class="col-md-12">
			<label for="exampleInputEmail1">Bio</label>
		</div>
		<div class="element-wrapper">
			<div class="col-md-12">
				<textarea name="bio" class="form-control" id="ckeditor1"> </textarea>
				@if ($errors->has('bio'))
					<div class="help-block form-text with-errors form-control-feedback">
						<ul class="list-unstyled text-danger">
							<li>{{ $errors->first('bio') }}</li>
						</ul>
					</div>
				@endif
			</div>
		</div>
		<div class="form-buttons-w">
			<button class="btn btn-primary" type="submit"> Submit</button>
		</div>
	</form>
	</div>
	{!! Form::close() !!}  
</div>

@endsection