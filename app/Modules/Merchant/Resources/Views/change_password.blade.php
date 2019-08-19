@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h3>Change Password</h3>
    </div>
    <div class="col-lg-2 pull-right">
        <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary pull-right">Back</a>
    </div>
</div>
<div class="wrapper wrapper-content">    
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
             <!--Flash message-->
             <div class="alert alert-success alert-dismissable hide status-flash-div">
                    <span class="status-message"></span>
                </div>
                @if(Session::has('success'))
                <div class="alert alert-success alert-dismissable">
                    {{ Session::get('success') }}
                </div>
                @endif

                @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissable">
                    {{ Session::get('error') }}
                </div>
                @endif
                <!--End--> 
                <div class="ibox-title">
                    <h5>All form elements with * are required fields.</h5>
                </div>
                <div class="ibox-content">
                    {{ Form::open(['route' => 'mchange-password', 'method' => 'POST', 'id'=>'changepassword-form']) }}

                    <input type="hidden" name="actionname" value="edituser" id="actionname"/>
                    <input type="hidden" name="id" value="{{Auth::user()->id}}" id="user-id"/>
                    <div class="row">
                        <div class="col-md-12">   
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Old Password: <span class="input-required">*</span></label>
                                    {{ Form::password('oldpassword', ['class' => 'form-control', 'id' => 'password']) }}
                                </div>  
                            </div>                         
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">   
                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label">New Password :<span class="input-required">*</span></label>
                                {{ Form::password('password', ['class' => 'form-control', 'id' => 'password']) }}
                                </div>
                            </div>                         
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">   
                            <div class="col-md-4">
                                <div class="form-group"><label class="control-label">Confirm New Password:</label>
                                {{ Form::password('confirmpassword', ['class' => 'form-control', 'id' => 'password_confirmation']) }}
                                </div>
                            </div>   
                        </div>
                    </div>
                            
                    <div class="row">
                        <div class="col-md-12">                                                          
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <a href="{{route('merchantdashboard')}}" class="btn btn-white">Cancel</a>
                                    <button class="btn btn-primary" type="submit">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection