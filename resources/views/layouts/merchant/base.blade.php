<!DOCTYPE html>
<html>
    @include('layouts.merchant.head')
    <body class="top-navigation">

        <div id="wrapper">

            @include('layouts.merchant.sidebar')

            <div id="page-wrapper" class="gray-bg">

                @yield('content')

                @include('layouts.merchant.footer')

            </div>
        </div>

        @include('layouts.merchant.foot')
        @yield('javascript')
    </body>

</html>
