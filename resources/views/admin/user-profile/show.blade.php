@extends('admin.admin')
@section('content')


  <section class="content-header">
    <h1>
      User Profile
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"><a href="{{URL::to('user_profile/index')}}">List User Profile</a></li>
    </ol>
  </section></br></br>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped table-hover">
          <tr>
            <td>Nama</td>
            <td>
              {{ $user_profile->user_id }}
            </td>
          </tr>
          <tr>
            <td>Alamat</td>
            <td>
              {{ $user_profile->address }}
            </td>
          </tr>
          <tr>
            <td>Birth Date</td>
            <td>
              {{ $user_profile->birth_date }}
            </td>
          </tr>
          <tr>
            <td>Tempat Lahir</td>
            <td>
              {{ $user_profile->place_birth }}
            </td>
          </tr>
          <tr>
            <td>Jabatan</td>
            <td>
              {{ $user_profile->phone }}
            </td>
          </tr>
          <tr>
            <td>Status</td>
            <td>
              {{ $user_profile->user_status_id }}
            </td>
          </tr>
          <tr>
            <td>Deskripsi</td>
            <td>
              {!! $user_profile->bio !!}
            </td>
          </tr>
        </table>
        <p align="center">
          <a href="{{URL::to('user_profile/index')}}" class="btn btn-primary" role="button">kembali</a>
        </p>
      
      </div>
    </div>
  </section>

  @endsection