@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h3> View Customer</h3>
    </div>
    <div class="col-lg-2 pull-right">
        <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary pull-right">Back</a>
    </div>
</div>
<div class="wrapper wrapper-content">    
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    {{ Form::open(['route' => ['update', $user->id], 'method' => 'POST', 'id'=>'user-form']) }}

                    <input type="hidden" name="actionname" value="{{Route::current()->getName()}}" id="actionname"/>
                    <input type="hidden" name="id" value="{{$user->id}}" id="user-id"/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label class="control-label">Title: </label>
                                    {{ Form::text('title',$user->title, ['class' => 'form-control', 'id' => 'title','readonly']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Name: </label>
                                    {{ Form::text('name',$user->name, ['class' => 'form-control', 'id' => 'name','readonly']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Family Name: </label>
                                    {{ Form::text('family_name',$user->family_name, ['class' => 'form-control', 'id' => 'family_name','readonly']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Email: </label>
                                    {{ Form::text('email',$user->email, ['class' => 'form-control', 'id' => 'email','readonly']) }}
                                </div>  
                            </div>
                           
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Country: </label>
                                    {{ Form::text('country_name',$user->country_name, ['class' => 'form-control', 'id' => 'country_name','readonly']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Country Code: </label>
                                    {{ Form::text('country_code',$user->country_code, ['class' => 'form-control', 'id' => 'country_code','readonly']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Mobile Number: </label>
                                    {{ Form::text('mobile_number', $user->mobile_number, ['class' => 'form-control', 'id' => 'mobile_number','readonly']) }}
                                </div>
                            </div>
                            <!-- <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">City Name: </label>
                                    {{ Form::text('city_name', $user->city_name, ['class' => 'form-control', 'id' => 'city_name ','readonly']) }}
                                </div>  
                            </div> 
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Company Name: </label>
                                    {{ Form::text('company_name', $user->company_name, ['class' => 'form-control', 'id' => 'company_name ','readonly']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Website: </label>
                                    {{ Form::text('website', $user->website, ['class' => 'form-control', 'id' => 'website ','readonly']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Sst Certificate: </label>
                                    {{ Form::text('sst_certificate', $user->sst_certificate, ['class' => 'form-control', 'id' => 'sst_certificate ','readonly']) }}
                                </div>  
                            </div>-->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Registration Type: </label>
                                    @php
                                    if($user->registration_type == 2 ){
                                        $register_type = "Facebook";
                                    }else{
                                        $register_type = "Normal";
                                    }
                                    @endphp
                                    {{ Form::text('register_type', $register_type, ['class' => 'form-control', 'id' => 'register_type ','readonly']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Status:</label>
                                    {{ Form::text('status', $user->status, ['class' => 'form-control', 'id' => 'status ','readonly']) }}
                                </div>  
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <!-- <a href="{{route('users')}}" class="btn btn-white">Cancel</a>
                                    <button class="btn btn-primary" type="submit">Save</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

</div>
@endsection