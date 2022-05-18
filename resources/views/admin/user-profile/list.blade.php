@extends('admin.admin')
@section('content')

@if ($message = Session::get('flash-store'))
  <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>{{ $message }}</strong>
  </div>
@endif

@if ($message = Session::get('flash-update'))
  <div class="alert alert-info alert-block">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>{{ $message }}</strong>
  </div>
@endif

@if ($message = Session::get('flash-destroy'))
  <div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>{{ $message }}</strong>
  </div>
@endif

@if ($message = Session::get('flash-approve'))
  <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>{{ $message }}</strong>
  </div>
@endif


<div class="row pt-4">
                <div class="col-sm-12">
                  <!--START - Recent Ticket Comments-->
                  <div class="element-wrapper">
                    <h6 class="element-header">
                       PROFILE
                    </h6>
                    <div class="element-box-tp full-right">
                    {!! Form::open(['method'=>'GET','url'=>'searchuser_profile','role'=>'search'])  !!}
                    <div class="input-search-w ">
                    <input class="form-control rounded bright col-sm-5" name="search" type="search">
                    </div>
                    {!! Form::close() !!}
                    <div class="el-buttons-list full-right">
                    <a class="btn btn-white btn-sm" href="{{URL::to('user_profile/create')}}"><i class="os-icon os-icon-delivery-box-2"></i><span>Create New User Profile</span></a>
                  </div>
                </div>
                    <div class="element-box-tp">
                      <div class="table-responsive">
                        <table class="table table-padded">
                          <thead>
                            <tr>
                              <th style="width:  10px;">
                                No
                              </th>
                              <th>
                                Nama
                              </th>
                              <th>
                                Alamat
                              </th>
                              <th>
                                Birth Date
                              </th>
                              <th>
                                Tempat Lahir
                              </th>
                              <th>
                                Phone
                              </th>
                              <th>
                                Status
                              </th>
                              <th>
                                Deskripsi
                              </th>
                              <th colspan="3" align="center">
                                Actions
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                          @foreach($user_profile as $i=>$user_profiles)
                            <tr>
                              <td>{{ $i+1 }}</td>
                              <td>
                                <span>{{$user_profiles->name}}</span>
                              </td>
                              <td>
                                <span>{{$user_profiles->address}}</span>
                              </td>
                              <td>
                                  <span>{{$user_profiles->birth_date}}</span>
                              </td>
                               <td>
                                  <span>{{$user_profiles->place_birth}}</span>
                              </td>
                               <td>
                                  <span>{{$user_profiles->phone}}</span>
                              </td>
                               <td>
                                  <span>{{$user_profiles->user_status_id}}</span>
                              </td>
                              <td>
                                  <span>{{$user_profiles->bio}}</span>
                              </td>
                              <td class="row-actions" style="width:  10px;">
                                <a href='{{URL::action("admin\UserProfileController@show",array($user_profiles->id))}}' data-toggle="tooltip" data-placement="top" title="Show"><i class="os-icon os-icon-grid-10"></i></a>
                               </td>
                              <td class="row-actions" style="width:  10px;">
                                <a href='{{URL::action("admin\UserProfileController@edit",array($user_profiles->id))}}' data-toggle="tooltip" data-placement="top" title="Edit"><i class="os-icon os-icon-ui-44"></i></a>
                                </td>
                              <td class="row-actions" style="width:  10px;">
                                <form id="delete_user_profile{{$user_profiles->id}}" action='{{URL::action("admin\UserProfileController@destroy",array($user_profiles->id))}}' method="POST">
                                <input type="hidden" name="_method" value="delete">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <a  class="danger"  href="#" onclick="document.getElementById('delete_user_profile{{$user_profiles->id}}').submit();"><i class="os-icon os-icon-ui-15" data-toggle="tooltip" data-placement="top" title="Hapus"></i></a>
                                </form>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <!--END - Recent Ticket Comments-->
                </div>
              </div>



@endsection