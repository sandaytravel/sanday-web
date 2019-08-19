@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h3> Edit Merchant User</h3>
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
                    {{ Form::open(['route' => ['updatemerchant' ,$user->id], 'method' => 'POST', 'id'=>'merchant-form']) }}

                    <input type="hidden" name="actionname" value="edituser" id="actionname"/>
                    <input type="hidden" name="id" value="{{$user->id}}" id="user-id"/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Name: <span class="input-required">*</span></label>
                                    {{ Form::text('name',$user->name, ['class' => 'form-control', 'id' => 'name']) }}
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="control-label">Company Name: <span class="input-required">*</span></label>
                                    {{ Form::text('companyname',$user->company_name, ['class' => 'form-control', 'id' => 'companyname']) }}
                                </div>
                            </div>   
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Phone: <span class="input-required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{$user->country_code}}</span>
                                        {{ Form::text('phone',$user->mobile_number, ['class' => 'form-control', 'id' => 'phone','maxlength' => 10]) }}
                                    </div>
                                </div>  
                            </div> 
                        </div>
                        <div class="col-md-12">      
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Email Address: <span class="input-required">*</span></label>
                                    {{ Form::text('email',$user->email, ['class' => 'form-control', 'id' => 'email','readonly']) }}                                   
                                </div>  
                            </div>  
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Country: <span class="input-required">*</span></label>
                                    <select name="country" class="form-control">
                                        <option value="">--Select Country--</option>
                                        @foreach($countries as $key => $value)
                                        <option value="{{$key}}" <?php if($value == $user->country_name){ ?> selected="selected" <?php } ?>>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>  
                            </div>                                   
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">City: <span class="input-required">*</span></label>
                                    {{ Form::text('city',$user->city_name, ['class' => 'form-control', 'id' => 'city']) }}
                                </div>  
                            </div>
                        </div>
                        <div class="col-md-12">   
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Website: <span class="input-required">*</span></label>
                                    {{ Form::text('website',$user->website, ['class' => 'form-control', 'id' => 'website']) }}
                                </div>  
                            </div>                         
                            <div class="col-md-5">
                                <div class="form-group"><label class="control-label">Company SST certificate :<span class="input-required">*</span></label>
                                {{ Form::text('sst_certificate',$user->sst_certificate, ['class' => 'form-control', 'id' => 'sst_certificate']) }}
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group"><label class="control-label">Status:</label>
                                    {{ Form::select('status',["Active" => "Active","Inactive" => "Inactive"],'',['class' => 'form-control', 'id' => 'status']) }}
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="col-md-12">                                                          
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <a href="{{route('merchantlist')}}" class="btn btn-white">Cancel</a>
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