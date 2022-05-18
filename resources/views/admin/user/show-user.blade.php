@extends('admin.admin')
@section('content')
      <h5 class="form-header">
        Show User
      </h5>
       <div class="form-group row">
        <div class="col-sm-8"> 
         @if ($user->foto)
            <a href="{{ url('/images/foto/'.$user->foto) }}"><img class="w3-card-12 img-circle" src="{{URL::asset('/images/foto/').'/'.$user->foto}}" alt="person" style="width:100%"></a>
        @else
            <img src="/front/img/mhs.png">
        @endif
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-sm-4" for=""> Nama</label>
        <div class="col-sm-8"> 
          : {{{$user->name}}}
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-sm-4" for=""> Email</label>
        <div class="col-sm-8"> 
          : {{{$user->email}}}
        </div>
      </div>
       <div class="form-group row">
        <label class="col-form-label col-sm-4" for=""> Alamat</label>
        <div class="col-sm-8"> 
          : {{{$user->address}}}
        </div>
      </div> 
      <div class="form-group row">
        <label class="col-form-label col-sm-4" for=""> Tanggal Lahir</label>
        <div class="col-sm-8"> 
          : {{{$user->birth_date}}}
        </div>
      </div>
       <div class="form-group row">
        <label class="col-form-label col-sm-4" for=""> Tempat Lahir</label>
        <div class="col-sm-8"> 
          : {{{$user->place_birth}}}
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-sm-4" for=""> No Hp</label>
        <div class="col-sm-8"> 
          : {{{$user->phone}}}
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-sm-4" for=""> Bio</label>
        <div class="col-sm-8"> 
          <p style="float: left;">: </p> {!! $user->bio !!}
        </div>
      </div>
       <div class="form-group row">
        <label class="col-form-label col-sm-4" for=""> Akses</label>
        <div class="col-sm-8"> 
          : {{{$user->group_name}}}
        </div>
      </div>

      </div>
      <div class="form-buttons-w" align="center">
      </div>
@endsection