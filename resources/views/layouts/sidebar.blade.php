<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element site-title"> 
                    <a href="{{route('dashboard')}}">
                        San Travel App
                    </a>
                </div>
                <div class="logo-element site-title">
                    <a href="{{route('dashboard')}}">
                        SA
                    </a>
                </div>
            </li>
            @can('dashboard', 'sidebar')
            <li class="{{ (Request::is('dashboard')) ? 'active' : '' }}">
                <a href="{{route('dashboard')}}"><span class="nav-label">DASHBOARD</span>  </a>
            </li>
            @endcan
            @can('merchant', 'sidebar')
            <li class="{{ (Request::is('merchantusers') || Request::is('merchant/add') || Request::is('merchant/search') || Route::currentRouteName() == 'editmerchant') ? 'active' : '' }}">
                <a href="{{route('merchantlist')}}"><span class="nav-label">MERCHANT USERS</span>  </a>
            </li>
            @endcan
            @can('system_users', 'sidebar')
            <li class="{{ (Request::is('systemusers') || Request::is('systemusers/add') || Request::is('systemusers/search') || Route::currentRouteName() == 'edituser') ? 'active' : '' }}">
                <a href="{{route('users')}}"><span class="nav-label">SYSTEM USERS</span>  </a>
            </li>
            @endcan
            @can('customers', 'sidebar')
            <li class="{{ (Request::is('customers') || Request::is('customer/search')) ||  Route::currentRouteName() == 'viewcustomer' ? 'active' : '' }}">
                <a href="{{route('customers')}}"><span class="nav-label">CUSTOMERS</span>  </a>
            </li>
            @endcan
            @can('activities', 'sidebar')
            <li class="{{ (Request::is('activity') || Request::is('activity/search') || Request::is('activity/add') || Route::currentRouteName() == 'updateactivity' || Route::currentRouteName() == 'set_package_configuration') ? 'active' : '' }}">
                <a href="{{route('activity')}}"><span class="nav-label">ACTIVITIES</span>  </a>
            </li>
            @endcan
            @can('locations', 'sidebar')
            <li class="{{ (Request::is('locations') || Request::is('city/add') || Request::is('location/search') || Route::currentRouteName() == 'editcity') ? 'active' : '' }}">
                <a href="{{route('locations')}}"><span class="nav-label">LOCATIONS</span>  </a>
            </li>
            @endcan
            @can('categories', 'sidebar')
            <li class="{{ (Request::is('categories') || Request::is('category/search')) ? 'active' : '' }}">
                <a href="{{route('categories')}}"><span class="nav-label">CATEGORIES</span>  </a>
            </li>
            @endcan
            @can('categories', 'sidebar')
            <li class="{{ (Request::is('orders') || Request::is('orders/search') ||  Route::currentRouteName() == 'orderview') ? 'active' : '' }}">
                <a href="{{route('Orders')}}"><span class="nav-label">ORDERS</span>  </a>
            </li>
            @endcan
            @can('settings', 'sidebar')
            <li class="{{( Request::is('settings/emailtemplate') || Route::currentRouteName() == 'editemailtemplate' || Request::is('settings/general-policy') || Request::is('settings/about-us') || Request::is('settings/explore')) ? 'active' : ''}}">
                <a href="javascript:void(0)"><span class="nav-label">SETTINGS</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse" style="height: 0px;">
                    <li class="{{(Request::is('settings/emailtemplate') || Route::currentRouteName() == 'editemailtemplate') ? 'active' : ''}}"><a href="{{route('emailtemplate')}}">EMAIL TEMPLATES</a></li>
                    <li class="{{(Request::is('settings/general-policy')) ? 'active' : ''}}"><a href="{{route('general_policy')}}">GENERAL POLICY</a></li>
                    <li class="{{(Request::is('settings/about-us')) ? 'active' : ''}}"><a href="{{route('aboutUs')}}">ABOUT US</a></li>
                    <li class="{{(Request::is('settings/explore')) ? 'active' : ''}}"><a href="{{route('explore')}}">EXPLORE</a></li>
                </ul>
            </li>
            @endcan
            
            <li>
                <a href="{{route('logout')}}"><i class="fa fa-sign-out"></i> <span class="nav-label">LOGOUT</span>  </a>
            </li>
            
        </ul>
    </div>
</nav>