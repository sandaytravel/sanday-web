<nav class="navbar navbar-static-top" role="navigation">
    <div class="navbar-header">
        <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
            <i class="fa fa-reorder"></i>
        </button>
        <a href="#" class="navbar-brand">San Travel App</a>
    </div>
    <div class="navbar-collapse collapse" id="navbar">
        <ul class="nav navbar-nav">
            <li class="{{ (Request::is('merchant/dashboard')) ? 'active' : '' }}">
                <a href="{{route('dashboard')}}"><span class="nav-label">DASHBOARD</span>  </a>
            </li>
            <li class="{{ (Request::is('activity') || Request::is('activity/search') || Request::is('activity/add') || Route::currentRouteName() == 'updateactivity' || Route::currentRouteName() == 'set_package_configuration') ? 'active' : '' }}">
                    <a href="{{route('activity')}}"><span class="nav-label">ACTIVITIES</span>  </a>
                </li>
                <li class="{{ (Request::is('locations') || Request::is('city/add') || Request::is('location/search') || Route::currentRouteName() == 'editcity') ? 'active' : '' }}">
                <a href="{{route('locations')}}"><span class="nav-label">LOCATIONS</span>  </a>
            </li>
            <li class="{{ (Request::is('categories') || Request::is('category/search')) ? 'active' : '' }}">
                <a href="{{route('categories')}}"><span class="nav-label">CATEGORIES</span>  </a>
            </li>
            <li class="dropdown {{( Request::is('settings/emailtemplate') || Route::currentRouteName() == 'editemailtemplate' || Request::is('settings/general-policy') || Request::is('settings/about-us') || Request::is('settings/explore')) ? 'active' : ''}}">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> SETTINGS <span class="SETTINGS"></span></a>
                    <ul role="menu" class="dropdown-menu">
                        <!-- <li class="{{(Request::is('settings/emailtemplate') || Route::currentRouteName() == 'editemailtemplate') ? 'active' : ''}}"><a href="{{route('emailtemplate')}}">EMAIL TEMPLATES</a></li> -->
                        <li class="{{(Request::is('settings/general-policy')) ? 'active' : ''}}"><a href="{{route('general_policy')}}">GENERAL POLICY</a></li>
                        <!-- <li class="{{(Request::is('settings/about-us')) ? 'active' : ''}}"><a href="{{route('aboutUs')}}">ABOUT US</a></li>
                        <li class="{{(Request::is('settings/explore')) ? 'active' : ''}}"><a href="{{route('explore')}}">EXPLORE</a></li>     -->
                    </ul>
            </li>
            <!-- <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="">Menu item</a></li>
                    <li><a href="">Menu item</a></li>
                    <li><a href="">Menu item</a></li>
                    <li><a href="">Menu item</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="">Menu item</a></li>
                    <li><a href="">Menu item</a></li>
                    <li><a href="">Menu item</a></li>
                    <li><a href="">Menu item</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="">Menu item</a></li>
                    <li><a href="">Menu item</a></li>
                    <li><a href="">Menu item</a></li>
                    <li><a href="">Menu item</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="">Menu item</a></li>
                    <li><a href="">Menu item</a></li>
                    <li><a href="">Menu item</a></li>
                    <li><a href="">Menu item</a></li>
                </ul>
            </li> -->

        </ul>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <a href="{{route('merchantlogout')}}"><i class="fa fa-sign-out"></i> <span class="nav-label">LOGOUT</span>  </a>
            </li>
        </ul>
    </div>
</nav>