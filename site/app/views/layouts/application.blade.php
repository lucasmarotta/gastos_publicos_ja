<!doctype html>

<html lang="es">
<head>
    
    @yield('facebook-opengraph')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Obras Públicas Já</title>

	<!-- fonts -->
    {{ HTML::style('assets/fonts/appicons.css') }}
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,300,400italic' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
	
	<!-- style -->
    {{ HTML::style('assets/css/bootstrap.min.css') }}
    {{ HTML::style('assets/css/main.css') }}

</head>
	
<body id="page-top" class="index">
	
	@include('layouts/_navigation')

	<div>
		@yield('content')
	</div>

	@include('layouts/_footer')
	
    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-top page-scroll visible-xs visible-sm">
        <a class="btn btn-primary icon-up" href="#page-top">
        </a>
    </div>

</body>

	<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    {{ HTML::script('assets/js/classie.js') }}
    {{ HTML::script('assets/js/cbpAnimatedHeader.js') }}
    {{ HTML::script('assets/js/jqBootstrapValidation.js') }}
    {{ HTML::script('assets/js/contact_me.js') }}
    {{ HTML::script('assets/js/main.js') }}
</html>
