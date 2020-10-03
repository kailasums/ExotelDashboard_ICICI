<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Login</title>
    <!--<link rel="shortcut icon" href="/images/favicon.png" type="image/ico"/>-->


    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>

<body>
    <div class="wrap">
    @yield('content')

    </div>

    <script src="{{URL::asset('js/jquery.js')}}"></script>
    <script src="{{URL::asset('js/inline-script.js')}}"></script>
    <script src="{{URL::asset('js/tether.min.js')}}"></script>
    <script src="{{URL::asset('js/bootstrap-material-design.iife.min.js')}}"></script>
    <script src="{{URL::asset('js/jquery.slimscroll.js')}}"></script>
</body>

</html>
