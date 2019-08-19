<nav class="navbar navbar-static-top" role="navigation">
    <div class="navbar-header">
        <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
            <i class="fa fa-reorder"></i>
        </button>
        <a href="{{route('merchantdashboard')}}" class="navbar-brand">San Travel App</a>
    </div>
    <div class="navbar-collapse collapse" id="navbar">
        <ul class="nav navbar-nav">
            <li class="{{ (Request::is('merchant/dashboard')) ? 'active' : '' }}">
                <a href="{{route('merchantdashboard')}}"><span class="nav-label">DASHBOARD</span>  </a>
            </li>
            <li class="{{ (Request::is('merchant/bookings') || Route::currentRouteName() == 'search-bookings') ? 'active' : '' }}">
                <a href="{{route('booking-list')}}"><span class="nav-label">MY BOOKINGS</span>  </a>
            </li>
        </ul>
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown {{(Request::is('merchantusers') || Request::is('merchant/add') || Request::is('merchant/search') || Route::currentRouteName() == 'editmerchant') ? 'active' : '' }}">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> SETTINGS <span class="SETTINGS"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li class="{{(Route::currentRouteName() == 'update-profile') ? 'active' : ''}}"> <a href="{{route('update-profile',[Auth::user()->id])}}"><span class="nav-label">MYACCOUNT</span>  </a></li>
                    <li class="{{(Route::currentRouteName() == 'change-password') ? 'active' : ''}}"> <a href="{{route('mchange-password')}}"><span class="nav-label">CHANGE PASSWORD</span>  </a></li>
                    <li>
                        <a href="{{route('logout')}}"><i class="fa fa-sign-out"></i> <span class="nav-label">LOGOUT</span>  </a>
                    </li>
                </ul>
            </li> 
        </ul>
    </div>
</nav>