<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Obras Públicas Já</title>

    {{ HTML::style('assets/css/fonts.min.css') }}
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    {{ HTML::style('assets/css/main.min.css') }}

</head>
<body id="page-top" class="index">
    
    @include('layouts/_navigation')
    <main>@yield('content')</main>
    @include('layouts/_footer')

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    {{ HTML::script('assets/js/classie.js') }}
    {{ HTML::script('assets/js/cbpAnimatedHeader.js') }}
    {{ HTML::script('assets/js/jqBootstrapValidation.js') }}
    {{ HTML::script('assets/js/contact_me.js') }}
    {{ HTML::script('assets/js/main.js') }}

</body>
</html>