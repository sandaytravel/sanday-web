<!DOCTYPE html>
<html>

    @include('layouts.head')

    <body class="gray-bg merchant-login-bg">
        <div class="loginscreen">
            <div class="middle-box text-center animated fadeInDown">
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

                        <div>

                            <h1 class="logo-name">San App</h1>
                        </div>
                        <p class="app-stores color_white">Reset your password.</p>
                    </div>
                    {{ Form::open(['route' => 'postresetpassword', 'method' => 'POST','class' => 'm-t','role' => 'form','id' => 'resetpassword-form']) }}
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{Request::segment(2)}}">
                    <div class="form-group">
                        {{ Form::password('newpassword', ['class' => 'form-control','id'=>'User','placeholder' => 'New Password']) }} 
                    </div>
                    <div class="form-group">
                        {{ Form::password('confirmpassword', ['class' => 'form-control','id'=>'Password','placeholder' => 'Confirm Password']) }} 
                    </div>
                    <button type="submit" class="btn btn-primary block full-width m-b">Reset Now</button>
                    <a class="color_white hvr_clrp" href="{{route('loginform')}}"><small>Login?</small></a>
                    {{ Form::close() }}
                    <p class="m-t color_white"> <small>{{date('Y')}} All Rights Reserved</small> </p>
                </div>
            </div>
        </div>

        <!-- Mainly scripts -->
        @include('layouts.foot')

    </body>

</html>
