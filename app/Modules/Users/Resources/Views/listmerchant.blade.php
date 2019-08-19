@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <div class="col-lg-4">
            <h3>Merchant Users</h3>
        </div>
        <div class="col-md-3 text-right pull-right">
            <!-- <a href="{{route('multipledelete',['replaceId'])}}" class="btn btn-outline btn-danger delete-selected" id="delete-selected-user">Delete</a> -->
            <a href="{{route('getmerchant')}}" type="button" class="btn btn-outline btn-primary">Add Merchant User</a>
            <!-- <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary">Back</a> -->
        </div>
        
        <div class="col-sm-3 pull-right">
            {!! Form::open(['url'=>'merchant/search','id'=>'search'])!!}
            {{ csrf_field() }}
            <div class="input-group"><input type="text" name="searchterm" value="{{ $merchantterm or ''}}" placeholder="Search" class="input-sm form-control"> <span class="input-group-btn">
                    <button type="submit" class="btn btn-sm btn-primary"> Go!</button> 
                    <a href="{{route('merchantlist')}}" class="btn btn-default btn-circle" title="Reset"><i class="fa fa-refresh"></i></a></span>
            </div>
            {{ Form::close() }}
        </div>
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
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="control-label">Name </th>
                                    <th class="control-label">Email</th>
                                    <th class="control-label">Phone</th>
                                    <!-- <th class="control-label">Registration Type</th> -->
                                    <th class="control-label">Status</th>
                                    <th class="control-label">Action</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($merchants))
                                @foreach($merchants as $key => $value)
                                <tr>
                                    <td class="table-data">{{($value->name) ? $value->name : "--"}}</td> 
                                    <td class="table-data">{{($value->email) ? $value->email : "--"}}</td>
                                    <td class="table-data">{{($value->mobile_number) ? $value->country_code .' '. $value->mobile_number : "--"}}</td>
                                    <!-- <td class="table-data">
                                        @if($value->registration_type == 1)
                                        <span class="label label-success">Normal </span>
                                        @else
                                        <span class="label label-primary">Facebook</span>
                                        @endif
                                    </td> -->
                                    
                                    <td class="table-data">
                                        @if($value->status == "Active")
                                        <span class="label label-primary status-change" data-type="merchant" data-id="{{$value->id}}">Active</span>
                                        @else
                                        <span class="label status-change" data-type="merchant" data-id="{{$value->id}}">Inactive</span><!--<input type="checkbox" class="i-checks merchant-status" name="status" data-type="merchant" data-id="{{$value->id}}">-->
                                        @endif
                                    </td>
                                    <td class="table-data">
                                        <div class="btn-group table-group-button">
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false">Action <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('editmerchant',[$value->id])}}" class="font-bold">Edit</a></li>
                                                @if(Auth::user()->id != $value->id)
                                                <li class="divider"></li>
                                                 <li><a href="{{route('deletemerchant',[$value->id])}}" class="font-bold delete-row" data-id='merchant user'>Delete</a></li>
                                                @endif
                                                <li class="divider"></li>
                                                <li><a href="javascript:void(0)" class="font-bold create-merchant-password" data-id="{{$value->id}}">Change Password</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="no-records">No merchants found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>                    
                </div>
                @if(isset($merchants)){!! $merchants->render() !!}@endif
            </div>
        </div>

    </div>
</div>
<!--ADD CATEGORY MODAL-->
<div class="modal inmodal" id="create-password-modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            {!! Form::open(['route'=>'createpassword','id' => 'resetpassword-form'])!!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Change Password</h4>
            </div>
            <div class="modal-body">                
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Password: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" name="id" id="merchant-id" value="" />
                        {{ Form::password('newpassword',['class' => 'form-control','placeholder' => 'Enter password','id' => 'newpassword']) }}                      
                    </div>                    
                </div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Confirm Password: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        {{ Form::password('confirmpassword',['class' => 'form-control','placeholder' => 'Enter confirm password','id' => 'confirmpassword']) }}                              
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary submit-button">Create Now!</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!--End Add--> 
@endsection
