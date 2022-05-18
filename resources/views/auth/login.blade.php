<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<title>{{ config('app.name', 'Laravel') }}</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
<!--===============================================================================================-->

</head>
<body>
	
	<div class="limiter">
    <form method="POST" action="{{ route('login') }}">
    @csrf
		<div class="container-login100" style="background-image: url('images/img-01.jpg');">
			<div class="wrap-login100 p-t-100 p-b-30">
				<form class="login100-form validate-form">
					<div class="login100-form-avatar">
						<img src="images/avatar-01.png" alt="AVATAR">
					</div>

					<span class="login100-form-title p-t-20 p-b-45">
						Universitas Islam Nusantara
					</span>
					@if ($errors->has('email'))
                            <div class="alert alert-danger alert-block">
						    <button type="button"style="color:#000;" class="close" data-dismiss="alert">x</button>
						    <strong style="font-family: Palatino; font-size: 14px">Username/password salah</strong>
						  </div>
                    @endif     
                    @if ($errors->has('password'))
                            <div class="alert alert-danger alert-block">
						    <button type="button"style="color:#000;" class="close" data-dismiss="alert">x</button>
						    <strong style="font-family: Palatino; font-size: 14px">Username/password salah</strong>
						  </div>
                    @endif

					<div class="wrap-input100 validate-input m-b-10 {{ $errors->has('email') ? ' has-error' : '' }}"  data-validate = "Username is required">
						<input class="input100" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user"></i>
						</span>

                        
                            
					</div>

					<div class="wrap-input100 validate-input m-b-10{{ $errors->has('password') ? ' has-error' : '' }}" data-validate = "Password is required">
						<input class="input100" type="password" class="form-control" name="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock"></i>
						</span>
						
					</div>

					<div class="container-login100-form-btn p-t-10">
						<button class="login100-form-btn">
							Login
						</button>
					</div>
					
					
				</form>
			</div>
		</div>
        </form>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>