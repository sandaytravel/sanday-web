@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading mlr-0">
    <div class="col-md-12">
        <div class="col-md-3">
            <h3> Activities</h3>
        </div>
        <div class="col-md-6 text-right pull-right">
            <a href="{{route('multiplestatusactivity',['replaceId'])}}" class="btn btn-outline btn-primary delete-selected status-selected-activity i-check-show" id="status-selected-activity" data-status="Active">Active</a>
            <a href="{{route('multiplestatusactivity',['replaceId'])}}" class="btn btn-outline btn-warning delete-selected status-selected-activity i-check-show" id="status-selected-activity" data-status="Inactive">Inactive</a>
            <a href="{{route('multipledeleteactivity',['replaceId'])}}" class="btn btn-outline btn-danger delete-selected i-check-show" id="delete-selected-activity">Delete</a>
            <a href="{{route('addactivity')}}" type="button" class="btn btn-outline btn-primary">Add Activity</a>
            <!-- <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary">Back</a> -->
        </div>
       
    </div>
    <div class="col-md-12 mt-15">
        <!-- <div class="col-md-1">
        </div> -->
        <div class="col-md-12">
            {!! Form::open(['url'=>'activity/search','id'=>'categoryname'])!!}
            {{ csrf_field() }}
                <div class="col-md-3">
                    <div class="form-group"><input type="text" name="searchterm" value="{{ $searchterm or ''}}" placeholder="Search" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::select('location',[null => '--Select Location--'] + $location, (isset($location_id)) ? $location_id :'', ['class' => 'form-control', 'id' => 'activty-location']) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::select('category',[null => '--Select Category--'] + $categories, (isset($category_id)) ? $category_id :'', ['class' => 'form-control', 'id' => 'activty-category']) }}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {{ Form::select('statusactivity',[null => '--Select status--'] + $statusactivity, (isset($statusactivity)) ? $statusactivity :'', ['class' => 'form-control', 'id' => 'activty-statusactivity']) }}
                    </div>
                </div>
                <div class="col-md-2 input-group-btn">
                    <button type="submit" class="btn btn-sm btn-primary"> Go!</button><a href="{{route('activity')}}" class="btn btn-default btn-circle" title="Reset"><i class="fa fa-refresh"></i></a>
                </div>
            {{ Form::close() }}
        </div>
        <!-- <div class="col-md-3">
            {!! Form::open(['url'=>'activity/search','id'=>'search'])!!}
            {{ csrf_field() }}
            <div class="input-group"><input type="text" name="searchterm" value="{{ $searchterm or ''}}" placeholder="Search" class="input-sm form-control"> <span class="input-group-btn">
                    <button type="submit" class="btn btn-sm btn-primary"> Go!</button> 
                    <a href="{{route('activity')}}" class="btn btn-default btn-circle" title="Reset"><i class="fa fa-refresh"></i></a></span>
            </div>
           
            {{ Form::close() }}
        </div>
         -->
    </div>
</div>
<div class="wrapper wrapper-content">    
    <div class="row">
        <div class="col-md-12">
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
                                    <th><input type="checkbox" class="i-checks " name="select_all" value="1" id="example-select-all" type="checkbox"  /></th>
                                    <th class="control-label" width="30%">Title</th>
                                    <!-- <th class="control-label">Package Configuration</th> -->
                                    <th class="control-label">Post By</th>
                                    <th class="control-label">City</th>
                                    <th class="control-label">Category </th>
                                    <!-- <th class="control-label">Actual Price</th>
                                    <th class="control-label">Display Price</th> -->
                                    <th class="control-label">Activity Status</th>
                                    <th class="control-label">Status</th>
                                    <!-- <th class="control-label">Admin Approve</th> -->
                                    <th class="control-label">Created Date</th>
                                    <th class="control-label">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($activities))
                                @foreach($activities as $key => $value)
                                <tr>
                                    
                                    <td class="table-data">
                                        
                                        <input type="checkbox" class="i-checks checkall i-event-show" id="sub_chk" data-id='{{$value->id}}' name="id[]" value='{{$value->id}}'>
                                    </td>
                                    <td class="table-data">{{$value->title}}</td>
                                    <!-- <td class="table-data">
                                        @if(in_array($value->id,$packageoptionarray))
                                        Package configuration set
                                        @else
                                        Please set package configuration
                                        @endif
                                    </td> -->
                                    <td class="table-data">
                                        @if($value->merchant->role_id == "1")
                                            Admin
                                        @else
                                            {{$value->merchant->name}}
                                        @endif
                                    </td>
                                    <td class="table-data">{{($value->city != null) ? $value->city->city : "--"}}</td>
                                    <td class="table-data">{{($value->category != null) ? $value->category->name : "--"}}</td> 
                                    <!-- <td class="table-data">{{$value->actual_price}}</td>
                                    <td class="table-data">{{$value->display_price}}</td> -->
                                    <td class="table-data">
                                        @if($value->admin_approve == "1")
                                            <span class="label label-success ">Published</span>            
                                        @else
                                            <span class="label label-warning">Draft  </span>
                                        @endif
                                    </td>

                                    <td class="table-data">
                                        @if($value->status == "Active")
                                        <span class="label label-primary status-change" data-type="activity" data-id="{{$value->id}}">Active</span>
                                        @else
                                        <span class="label status-change" data-type="activity" data-id="{{$value->id}}">Inactive</span><!--<input type="checkbox" class="i-checks merchant-status" name="status" data-type="merchant" data-id="{{$value->id}}">-->
                                        @endif                                        
                                    </td>
                                     <!-- <td class="table-data">
                                        @if($value->admin_approve == "1")
                                            <span class="label label-success ">Approve</span>            
                                        @elseif($value->admin_approve == "0")
                                            <span class="label label-warning">Pending</span>
                                        @else
                                            <span class="label label-danger">Decline</span>
                                        @endif
                                    </td>  -->
                                    <td class="table-data">{{date("Y-m-d",strtotime($value->created_at))}}</td>
                                    <td class="table-data">
                                        <div class="btn-group table-group-button">
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false">Action <span class="caret"></span></button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="{{route('updateactivity',[$value->id])}}" class="font-bold">Edit</a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{route('deleteactivity',[$value->id])}}" class="font-bold delete-row" data-id='activity'>Delete</a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{route('set_package_configuration',[$value->id])}}" class="font-bold">Package Configuration</a></li>
                                                 <!-- <li class="divider"></li> -->
                                                 <?php if (Auth::user()->can('activities', 'read') || Auth::user()->role_id == 1) {?>
                                                <!-- <li><a href="{{route('approvedeclineactivity',[$value->id,'approve']) }}" class="font-bold ad-activity" data-id="approved">Approved</a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{route('approvedeclineactivity',[$value->id,'decline']) }}" class="font-bold ad-activity" data-id='decline' data-vid="{{$value->id}}">Decline</a></li>  -->
                                                 <?php } ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="9" class="no-records">No activity found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>                    
                </div>
                @if(isset($activities)){!! $activities->render() !!}@endif
            </div>
        </div>

    </div>
</div>

<!--ADD COUNTRY MODAL-->
<div class="modal inmodal" id="decline-activity-modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            {!! Form::open(['route' => ['approvedeclineactivity', '6' ,'decline'],'id'=>'decline-activity'])!!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title decline-activity-title">Reason For decline</h4>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Reason For decline: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        {{ Form::textarea('reasondecline','',['class' => 'form-control decline-name','placeholder' => 'Please enter decline reason']) }}
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

@endsection