<!DOCTYPE html>
<html>

    @include('layouts.head')

    <body class="gray-bg  merchant-login-bg">
        <div class="loginscreen">
            <div class="middle-box text-center  animated fadeInDown">
                <!--Flash message--> 
                @if(Session::has('success'))
                <div class="alert alert-success alert-dismissable">
                    {{ Session::get('success') }}
                </div>
                @endif

                @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    {{ Session::get('error') }}
                </div>
                @endif
                <!--End--> 
                <div>
                    <div>

                        <h1 class="logo-name">San App</h1>
                        <p class="app-stores color_white">Enter you email address to reset your password.</p>
                    </div>
                    {{ Form::open(['route' => 'postMerchantForgotPassword', 'method' => 'POST','class' => 'm-t','role' => 'form','id' => 'forgotpassword-form']) }}
                    {{ csrf_field() }}
                    <div class="form-group">
                        {{ Form::text('email', '', ['class' => 'form-control','id'=>'User','placeholder' => 'Enter you email address']) }} 
                    </div>
                    
                    <button type="submit" class="btn btn-primary block full-width m-b">Send Password Reset Link</button>
                    
                    {{ Form::close() }}
                    <div class="form-group">
                        <div class="pull-right">
                            <a class="color_white hvr_clrp" href="{{route('merchantloginform')}}" class="forgot-pass-link">Login?</a>
                        </div>
                    </div>
                    <p class="m-t color_white"> <small>{{date('Y')}} All Rights Reserved</small> </p>
                </div>
            </div>
        </div>
        <!-- Mainly scripts -->
        @include('layouts.foot')

    </body>

</html>
