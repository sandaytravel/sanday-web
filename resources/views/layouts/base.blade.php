<!DOCTYPE html>
<html>
    @include('layouts.head')
    <body class="{{(Auth::user()->role_id == 3) ? 'top-navigation' : ''}}">

        <div id="wrapper">            
            @if(Auth::user()->role_id == 3)
                @include('layouts.navigation')
            @else
                @include('layouts.sidebar')
            @endif
            <div id="page-wrapper" class="gray-bg">

                @yield('content')

                @include('layouts.footer')

            </div>
        </div>
        @include('layouts.foot')
        @yield('javascript')
    </body>

</html>
