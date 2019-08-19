@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h3> Add System User</h3>
    </div>
    <div class="col-lg-2 pull-right">
        <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary pull-right">Back</a>
    </div>
</div>
<div class="wrapper wrapper-content">    
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>All form elements with * are required fields.</h5>
                </div>
                <div class="ibox-content">
                    {{ Form::open(['route' => 'adduser', 'method' => 'POST', 'id'=>'user-form']) }}

                    <input type="hidden" name="actionname" value="{{Route::current()->getName()}}" id="actionname"/>
                    <input type="hidden" name="id" value="empty" id="user-id"/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Name: <span class="input-required">*</span></label>
                                    {{ Form::text('name','', ['class' => 'form-control', 'id' => 'name']) }}
                                </div>
                            </div>                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Email: <span class="input-required">*</span></label>
                                    {{ Form::text('email','', ['class' => 'form-control', 'id' => 'email']) }}
                                </div>  
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Password: <span class="input-required">*</span></label>
                                    {{ Form::password('password', ['class' => 'form-control', 'id' => 'password']) }}
                                </div>  
                            </div>
                            <div class="col-md-3">
                                <div class="form-group"><label class="control-label">Confirm Password: <span class="input-required">*</span></label>
                                    {{ Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation']) }}
                                </div>  
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">                            
                            <div class="col-md-2">
                                <div class="form-group"><label class="control-label">Status:</label>
                                    {{ Form::select('status',["Active" => "Active","Inactive" => "Inactive"],'',['class' => 'form-control', 'id' => 'status']) }}
                                </div>
                            </div>
                        </div>
                    </div>                                                            
                    <div class="form-group">
                        <div class="col-sm-4">
                            <a href="{{route('users')}}" class="btn btn-white">Cancel</a>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection