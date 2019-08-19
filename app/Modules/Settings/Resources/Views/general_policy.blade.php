@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <div class="col-lg-4">
            <h3> General Policy</h3>
        </div>
        <div class="col-md-3 text-right pull-right">
            <a type="button" data-toggle="modal" data-target="#policy-modal" class="btn btn-outline btn-primary">Add Policy</a>
            <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary ">Back</a>
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
                                    <th class="control-label">Title </th>
                                    <!-- <th class="control-label">Icon</th> -->
                                    <th class="control-label">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($policy))
                                @foreach($policy as $key => $value)
                                <tr>
                                    <td class="table-data">{{($value->name) ? $value->name : "--"}}</td> 
                                    <!-- <td class="table-data">
                                        @if($value->icon != "")
                                        <img src="{{admin_asset('img/icons/resized/'.$value->icon)}}" style="width: 30px;">
                                        @else
                                        {{"--"}}
                                        @endif
                                    </td>  -->
                                    <td>
                                        <a type="button" data-name='{{$value->name}}' data-id='{{$value->id}}' data-image="{{admin_asset('img/icons/fullsized/'.$value->icon)}}" class="btn btn-primary btn-circle edit-policy" title="Edit Policy"><i class="fa fa-pencil"></i></a>
                                        @if(in_Array($value->id,$policy_array))
                                            <a href="javascript:void(0)" class="btn btn-danger btn-circle non-deleteable" data-id='policy' title="Delete Policy"><i class="fa fa-remove"></i></a>
                                        @else
                                            <a href="{{route('deletepolicy',[$value->id])}}" class="btn btn-danger btn-circle delete-row" data-id='policy' title="Delete Policy"><i class="fa fa-remove"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="3" class="no-records">No policy found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
<!--ADD POLICY MODAL-->
<div class="modal inmodal" id="policy-modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            {!! Form::open(['url'=>'policy/add','id'=>'add-policy-form','enctype' => 'multipart/form-data'])!!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add Policy</h4>
            </div>
            <div class="modal-body">
                <!-- <div class="row form-group">
                    <div class="col-md-3">
                        <label class="control-label">Icon: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-9">
                        {{ Form::file('image','',['class' => 'form-control-file']) }}                      
                    </div>
                </div> -->
                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="control-label">Policy: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-9">
                        {{ Form::text('name','',['class' => 'form-control','placeholder' => 'Enter policy name']) }}                      
                    </div>
                </div>                                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary submit-button">Save</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!--End Add-->
<!--UPDATE POLICY MODAL-->
<div class="modal inmodal" id="update-policy-modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            {!! Form::open(['url'=>'policy/update','id'=>'update-policy-form','enctype' => 'multipart/form-data'])!!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Update Policy</h4>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="control-label">Policy: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-9">
                        <input type="hidden" name="policy_id" id="policy_id" value="">
                        {{ Form::text('name','',['class' => 'form-control','id' => 'policy-name','placeholder' => 'Enter policy name']) }}                      
                    </div>
                </div>
                <!-- <div class="row form-group">
                    <div class="col-md-3">
                        <label class="control-label">Icon: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-9">
                        {{ Form::file('image','',['class' => 'form-control-file']) }}                      
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <img class="img-responsive" id="policy-image"/>
                    </div>
                </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary submit-button">Update</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!--End UPDATE-->
@endsection