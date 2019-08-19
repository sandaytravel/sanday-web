<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>San Travel App</title>

        <link href="{{admin_asset('css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{admin_asset('fontawesome/css/font-awesome.css')}}" rel="stylesheet">

        <link href="{{admin_asset('css/animate.css')}}" rel="stylesheet">
        <link href="{{admin_asset('css/style.css')}}" rel="stylesheet">

    </head>

    <body class="gray-bg">


        <div class="middle-box text-center animated fadeInDown">
            <h1 class="error-code">404</h1>
            <h3 class="font-bold">Page Not Found</h3>

            <div class="error-desc">
                Sorry, but the page you are looking for has note been found. Try checking the URL for error, then hit the refresh button on your browser or try to go on dashboard.
                <form class="form-inline m-t" role="form">

                    <a href='{{route('dashboard')}}' class="btn btn-primary">Dashboard</a>
                </form>
            </div>
        </div>

        <!-- Mainly scripts -->
        <script src="{{admin_asset('js/jquery-3.1.1.min.js')}}"></script>
        <script src="{{admin_asset('js/bootstrap.min.js')}}"></script>

    </body>

</html>
