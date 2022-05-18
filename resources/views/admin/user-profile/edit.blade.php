@extends('admin.admin')
@section('content')
<div class="col-lg-12">
	{!! Form::model($user_profile,['method'=>'put', 'files'=> 'true', 'action'=>['admin\UserProfileController@update',$user_profile->user_id]]) !!}
    {{ csrf_field() }}
	    <div class="element-wrapper">
			<h6 class="element-header">
				EDIT USER PROFILE
			</h6>
			<div class="element-box">
		        <form>
					<h5 class="form-header">
						USER PROFILE
					</h5>
					<div class="form-group">
						<label for=""> Nama</label>
						<input class="form-control" placeholder="Nama" name="user_id" value="{{$user_profile->user_id}}">
						@if ($errors->has('user_id'))
							<div class="help-block form-text with-errors form-control-feedback">
								<ul class="list-unstyled text-danger">
									<li>{{ $errors->first('user_id') }}</li>
								</ul>
							</div>
						@endif
					</div>
					<div class="form-group">
						<label for=""> Alamat</label>
						<input class="form-control" placeholder="Alamat" name="address" value="{{$user_profile->address}}">
						@if ($errors->has('address'))
							<div class="help-block form-text with-errors form-control-feedback">
								<ul class="list-unstyled text-danger">
									<li>{{ $errors->first('address') }}</li>
								</ul>
							</div>
						@endif
					</div>
					<div class="form-group">
						<label for=""> Birth Date</label>
						<input class="form-control" placeholder="Birth Date" name="birth_date" value="{{$user_profile->birth_date}}">
						@if ($errors->has('birth_date'))
							<div class="help-block form-text with-errors form-control-feedback">
								<ul class="list-unstyled text-danger">
									<li>{{ $errors->first('birth_date') }}</li>
								</ul>
							</div>
						@endif
					</div>
					<div class="form-group">
						<label for=""> Tempat Lahir</label>
						<input class="form-control" placeholder="Tempat Lahir" name="place_birth" value="{{$user_profile->place_birth}}">
						@if ($errors->has('place_birth'))
							<div class="help-block form-text with-errors form-control-feedback">
								<ul class="list-unstyled text-danger">
									<li>{{ $errors->first('place_birth') }}</li>
								</ul>
							</div>
						@endif
					</div>
					<div class="form-group">
						<label for=""> Phone</label>
						<input class="form-control" placeholder="Phone" name="phone" value="{{$user_profile->phone}}">
						@if ($errors->has('phone'))
							<div class="help-block form-text with-errors form-control-feedback">
								<ul class="list-unstyled text-danger">
									<li>{{ $errors->first('phone') }}</li>
								</ul>
							</div>
						@endif
					</div>
					<div class="form-group">
						<label for=""> Status</label>
						<input class="form-control" placeholder="Status" name="user_status_id" value="{{$user_profile->user_status_id}}">
						@if ($errors->has('user_status_id'))
							<div class="help-block form-text with-errors form-control-feedback">
								<ul class="list-unstyled text-danger">
									<li>{{ $errors->first('user_status_id') }}</li>
								</ul>
							</div>
						@endif
					</div>
					<div class="element-wrapper">
						<label for="exampleInputEmail1">Deskripsi</label>
						<textarea name="bio" class="form-control" id="ckeditor1" value="{{$user_profile}}"> {{$user_profile->bio}}</textarea>
						@if ($errors->has('bio'))
							<div class="help-block form-text with-errors form-control-feedback">
								<ul class="list-unstyled text-danger">
									<li>{{ $errors->first('bio') }}</li>
								</ul>
							</div>
						@endif
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