<!doctype html>
<html class="no-js h-100" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Aplikasi Keuangan</title>
    <meta name="description" content="A high-quality &amp; free Bootstrap admin dashboard template pack that comes with lots of templates and components.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet"> -->
    <!-- Custom CSS -->
    <!-- Font Awesome -->
    @yield('css')
    {!! Html::style('fontawesome/css/all.css') !!}
    {!! Html::style('css/upload.css') !!}
    {!! Html::style('css/material-icons.css') !!}
    {!! Html::style('css/bootstrap.min.css') !!}
    {!! Html::style('css/image_galeri.css') !!}
    {!! Html::style('styles/shards-dashboards.1.1.0.min.css') !!}
    {!! Html::style('styles/extras.1.1.0.min.css') !!}
    {!! Html::style('css/jquery.dataTables.min.css') !!}

    <style type="text/css">
      .preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background-color: #fff;
      }
      .preloader .loading {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
        font: 14px arial;
      }
    </style>
  </head>
  <body class="h-100">
  <div class="preloader">
    <div class="loading">
    <i class="fas fa-spinner fa-pulse fa-2x"></i>
      
    </div>
  </div>
  @yield('image')
    <div class="container-fluid">
      <div class="row">
        <!-- Main Sidebar -->
        <aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
          <div class="sidebar">
            <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom p-0">
            <ul class="navbar-nav flex-row" style="position:relative; left:30px; top:10px">
              <li>
                <img id="main-logo" class="d-inline-block mr-1" style="max-width: 35px;" src="{{URL::asset('/images/shards-dashboards-logo.png')}}">
              </li>
              <li>
                <a class="navbar-brand w-100 mr-0" href="#">
                  <div class="d-table m-auto">
                    <span class="d-none d-md-inline ml-1">Aplikasi Keuangan</span>
                  </div>
                </a>
              </li>
              <li>
                <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                  <i class="material-icons">&#xE5C4;</i>
                </a>
              </li>
            </ul>
            </nav>
          </div>
          <form action="#" class="main-sidebar__search w-100 border-right d-sm-flex d-md-none d-lg-none">
            <div class="input-group input-group-seamless ml-3">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-search"></i>
                </div>
              </div> </div>
          </form>
          <div class="nav-wrapper">
            <ul class="nav flex-column">
            @if(Auth::check())
            @if(Auth::user()->groups()->where("name", "=", "Admin")->first())
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-nowrap px-4" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false" href="index.html">
                  <i class="material-icons">edit</i>
                  <span>Admin</span>
                </a>
                <div class="dropdown-menu dropdown-menu-small">
                    <a class="dropdown-item" href="{{URL::to('/roles')}}">
                      <i class="material-icons">edit</i> Roles</a>
                    <a class="dropdown-item" href="{{URL::to('/groups')}}">
                      <i class="material-icons">edit</i> Groups</a>
                    <a class="dropdown-item" href="{{URL::to('/group-roles')}}">
                      <i class="material-icons">edit</i> Groups Roles</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item " href="{{URL::to('/user-groups')}}">
                      <i class="material-icons">edit</i> User Groups </a>
                  </div>
              </li>

              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-nowrap px-4" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false" href="index.html">
                  <i class="material-icons">person</i>
                  <span>User</span>
                </a>
                <div class="dropdown-menu dropdown-menu-small">
                    <a class="dropdown-item" href="{{URL::to('/user/index')}}">
                      <i class="material-icons">person</i> User</a>
                    <a class="dropdown-item" href="{{URL::to('/user_sekolah/index')}}">
                      <i class="material-icons">person</i> User Sekolah</a>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="{{URL::to('/sekolah/index')}}">
                  <i class="material-icons">view_module</i>
                  <span>Sekolah</span>
                </a>
              </li>
              @else
              @endif
              @endif
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-nowrap px-4" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false" href="index.html">
                  <i class="material-icons">person</i>
                  <span>Siswa</span>
                </a>
                <div class="dropdown-menu dropdown-menu-small">
                    <a class="dropdown-item" href="{{URL::to('/siswa/index')}}">
                      <i class="material-icons">person</i> Semua Aktif</a>
                    <a class="dropdown-item" href="{{URL::to('/calon/index')}}">
                      <i class="material-icons">person</i> Calon</a>
                    <a class="dropdown-item" href="{{URL::to('/calon')}}">
                      <i class="material-icons">person</i> Alumni</a>
                    <a class="dropdown-item" href="{{URL::to('/siswa/tidak_aktif')}}">
                      <i class="material-icons">person</i> Siswa Tidak Aktif</a>
                </div>
              </li>
              
              @if (Auth::user()->hasAnyRole('Penerimaan'))
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-nowrap px-4" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false" href="index.html">
                  <i class="material-icons">edit</i>
                  <span>Penerimaan</span>
                </a>
                <div class="dropdown-menu dropdown-menu-small">
                    <a class="dropdown-item" href="{{URL::to('/penerimaans/index')}}">
                      <i class="material-icons">edit</i> List Penerimaan</a>
                    <a class="dropdown-item" href="{{URL::to('/bukti/index')}}">
                      <i class="material-icons">edit</i> Pertanggung Jawaban</a>
                </div>
              </li>
              @endif
              @if (Auth::user()->hasAnyRole('Pengeluaran'))
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-nowrap px-4" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false" href="index.html">
                  <i class="material-icons">edit</i>
                  <span>Pengeluaran</span>
                </a>
                <div class="dropdown-menu dropdown-menu-small">
                    <a class="dropdown-item" href="{{URL::to('/luaran/index')}}">
                      <i class="material-icons">edit</i> List Pengeluaran</a>
                    <a class="dropdown-item" href="{{URL::to('/buktipengeluaran/index')}}">
                      <i class="material-icons">edit</i> Pertanggung Jawaban</a>
                </div>
              </li>
            @endif
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-nowrap px-4" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false" href="index.html">
                  <i class="material-icons">edit</i>
                  <span>Pembayaran</span>
                </a>
                <div class="dropdown-menu dropdown-menu-small">
            @if(Auth::check())
            @if(Auth::user()->groups()->where("name", "=", "Admin")->first())
                    <a class="nav-link dropdown-toggle text-nowrap px-4" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false" href="index.html">
                      <i class="material-icons">edit</i>
                      <span>Setting</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-small">
                      <a class="dropdown-item" href="{{URL::to('/year/index')}}">
                        <i class="material-icons">edit</i> Tahun</a>
                      <a class="dropdown-item" href="{{URL::to('/programs/index')}}">
                        <i class="material-icons">edit</i> Program</a>  
                      <a class="dropdown-item" href="{{URL::to('/payment/index')}}">
                        <i class="material-icons">edit</i> Jenis Pembayaran</a>  
                      <a class="dropdown-item" href="{{URL::to('/cost/index')}}">
                        <i class="material-icons">edit</i> Biaya</a>
                      <a class="dropdown-item" href="{{URL::to('/detail_payments/index')}}">
                        <i class="material-icons">note</i> Detail Pembayaran</a>
                    </div>
            @else
            @endif
            @endif
                    <a class="dropdown-item" href="{{URL::to('/candidate/index')}}">
                      <i class="material-icons">edit</i> PPDB</a>
                    <a class="dropdown-item" href="{{URL::to('/spp/index')}}">
                      <i class="material-icons">edit</i> SPP</a>
                </div>  
              </li>

              <li class="nav-item">
                <a class="nav-link " href="{{URL::to('/uang_keluar/index')}}">
                  <i class="material-icons">note</i>
                  <span>Pengembalian</span>
                </a>
              </li>

              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-nowrap px-4" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false" href="index.html">
                  <i class="material-icons">note</i>
                  <span>Laporan</span>
                </a>
                <div class="dropdown-menu dropdown-menu-small">
<!--                     <a class="nav-link dropdown-toggle text-nowrap px-4" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false" href="index.html">
                      <i class="material-icons">note</i>
                      <span>Harian</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-small">
                      <a class="dropdown-item" href="{{URL::to('/formulir-harian')}}">
                        <i class="material-icons">note</i> Formulir</a>
                      <a class="dropdown-item" href="{{URL::to('/daftarulang-harian')}}">
                        <i class="material-icons">note</i> Daftar Ulang</a>  
                      <a class="dropdown-item" href="{{URL::to('/spp-harian')}}">
                        <i class="material-icons">note</i> SPP</a>
                    </div> -->
                    <a class="dropdown-item" href="{{URL::to('/rekap/index')}}">
                      <i class="material-icons">note</i> Rekap</a>
                    <a class="dropdown-item" href="{{URL::to('/report-all-siswa')}}">
                      <i class="material-icons">note</i> Siswa</a>
                    <a class="dropdown-item" href="{{URL::to('/tunggakan/index')}}">
                      <i class="material-icons">note</i> Tunggakan</a>
                </div>  
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-nowrap px-4" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false" href="index.html">
                  <i class="material-icons">note</i>
                  <span>Template Import</span>
                </a>
                <div class="dropdown-menu dropdown-menu-small">
                    <a class="dropdown-item" href="{{URL::to('/file/spp.xls')}}">
                      <i class="material-icons">note</i> Spp</a>
                    <a class="dropdown-item" href="{{URL::to('/file/siswa.xls')}}">
                      <i class="material-icons">note</i> Siswa</a>
                </div>  
              </li>
            </ul>
          </div>
        </aside>
        <!-- End Main Sidebar -->
        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
          <div class="main-navbar sticky-top bg-white">
            <!-- Main Navbar -->
            <nav class="navbar align-items-stretch navbar-light flex-md-nowrap p-0">
              <form action="#" class="main-navbar__search w-100 d-none d-md-flex d-lg-flex">
              </form>
              <ul class="navbar-nav border-left flex-row ">
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle text-nowrap px-3" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img class="user-avatar rounded-circle mr-2" src="{{URL::asset('/images/avatars/0.jpg')}}" alt="User Avatar">
                    <span class="d-none d-md-inline-block">{{ Auth::user()->name }}</span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-small">
                    <a class="dropdown-item" href="user-profile-lite.html">
                      <i class="material-icons">&#xE7FD;</i> Profile</a>
                    <div class="pull-right">
                      <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();" class="btn btn-default btn-flat text-danger"><i class="material-icons text-danger">&#xE879;</i>Sign out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
                </li>
              </ul>
              <nav class="nav">
                <a href="#" class="nav-link nav-link-icon toggle-sidebar d-md-inline d-lg-none text-center border-left" data-toggle="collapse" data-target=".header-navbar" aria-expanded="false" aria-controls="header-navbar">
                  <i class="material-icons">&#xE5D2;</i>
                </a>
              </nav>
            </nav>
          </div>
          <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <div class="row">
              <div class="col">
                @yield('content')
              </div>
            </div>
          </div>
            <!-- End Page Header -->
            <!-- Default Light Table -->
          <footer class="main-footer d-flex p-2 px-3 bg-white border-top">
            <ul class="nav">
              
            </ul>
            <span class="copyright ml-auto my-auto mr-2">Copyright © 2019 Agora
              <a href="#" rel="nofollow"> </a>
            </span>
          </footer>
        </main>
      </div>
    </div>

    {!! Html::script('fontawesome-free-5.0.2\svg-with-js\fontawesome-all.min.js') !!}
    {!! Html::script('js/jquery-3.3.1.min.js') !!}
    {!! Html::script('js/jquery.dataTables.min.js') !!}
    {!! Html::script('js/popper.min.js') !!}
    {!! Html::script('js/bootstrap.min.js') !!}
    {!! Html::script('js/Chart.min.js') !!}
    {!! Html::script('js/shards.min.js') !!}
    {!! Html::script('js/jquery.sharrre.min.js') !!}
    {!! Html::script('scripts/extras.1.1.0.min.js') !!}
    {!! Html::script('scripts/shards-dashboards.1.1.0.min.js') !!}
    {!! Html::script('scripts/app/app-blog-overview.1.1.0.js') !!}
    <script>
      $(document).ready(function(){
      $(".preloader").fadeOut();
      })
    </script>
    @yield('js')
    @stack('scripts')
  </body>
</html>