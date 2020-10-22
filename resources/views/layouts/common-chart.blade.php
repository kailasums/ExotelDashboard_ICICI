<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Dashboard</title>
    <!--<link rel="shortcut icon" href="/images/favicon.png" type="image/ico"/>-->


    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css"/>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
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
    <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js""></script>
    <script type="text/javascript" src="{{URL::asset('js/custom.js')}}"></script>
</body>

</html>
