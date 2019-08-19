@extends('layouts.base')
@section('content')
<div class="page-wrapper wrapper-content">
    <div class="row wrapper border-bottom white-bg page-heading mlr-0  mb-20">
        <div class="col-md-12">
            <div class="col-md-10">
                <h3>Dashboard</h3>
            </div>
        </div>
    </div>
    <div class="container">
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
        <div class="row">
            <div class="col-lg-3">
                <a href="{{url('merchant/search-bookings?booking_status=0')}}" class="gray-fonts">
                    <div class="widget style1">
                        <div class="row">
                            <div class="col-xs-4 text-center">
                                <i class="fa fa-clock-o fa-5x"></i>
                            </div>
                            <div class="col-xs-8 text-right">
                                <span> Pending Booking </span>
                                <h2 class="font-bold">{{$pendingBooking}}</h2>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="{{url('merchant/search-bookings?booking_status=2')}}" class="white-fonts">
                    <div class="widget style1 navy-bg">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="fa fa-check fa-5x"></i>
                            </div>
                            <div class="col-xs-8 text-right">
                                <span> Confirmed Booking </span>
                                <h2 class="font-bold">{{$confirmedBooking}}</h2>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="{{url('merchant/search-bookings?booking_status=1')}}" class="white-fonts">
                    <div class="widget style1 red-bg">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="fa fa-times fa-5x"></i>
                            </div>
                            <div class="col-xs-8 text-right">
                                <span> Canceled Booking </span>
                                <h2 class="font-bold">{{$canceledBooking}}</h2>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <div class="widget style1 yellow-bg">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-ticket fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <span> Total Sales </span>
                            <h2 class="font-bold">RM {{number_format($totalSales,2)}}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="row ">
            <div class="col-lg-12 wrapper border-bottom white-bg page-heading mb-20">
                <div class="col-lg-10">
                    <h3>My Activity</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-1">Published ({{(count($publish_activity)? count($publish_activity) : 0)}})</a></li>
                        <li class=""><a data-toggle="tab" href="#tab-2">Not Published ({{(count($decline_activity)? count($decline_activity) : 0)}})</a></li>
                        <li class=""><a data-toggle="tab" href="#tab-3">Pending ({{(count($pending_activity)? count($pending_activity) : 0)}})</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane active">
                            <div class="panel-body">
                                <div class="wrapper wrapper-content">    
                                    <!------------------>
                                    <div class="ibox-content">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <!-- <th><input type="checkbox" class="i-checks " name="select_all" value="1" id="example-select-all" type="checkbox"  /></th> -->
                                                        <th class="control-label">Title</th>

                                                        <th class="control-label">City</th>
                                                        <th class="control-label">Category </th>

                                                        <th class="control-label">Status</th>
                                                        <th class="control-label">Created Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($publish_activity))
                                                    @foreach($publish_activity as $key => $value)
                                                    <tr>

<!-- <td class="table-data">
    
    <input type="checkbox" class="i-checks checkall i-event-show" id="sub_chk" data-id='{{$value->id}}' name="id[]" value='{{$value->id}}'>
</td> -->
                                                        <td class="table-data">{{$value->title}}</td>

                                                        <td class="table-data">{{($value->city != null) ? $value->city->city : "--"}}</td>
                                                        <td class="table-data">{{($value->category != null) ? $value->category->name : "--"}}</td> 
                                                        <!-- <td class="table-data">{{$value->actual_price}}</td>
                                                        <td class="table-data">{{$value->display_price}}</td> -->

                                                        <td class="table-data">
                                                            @if($value->admin_approve == "1")
                                                            <span class="label label-success ">Approve</span>            
                                                            @elseif($value->admin_approve == "0")
                                                            <span class="label label-warning">Pending</span>
                                                            @else
                                                            <span class="label label-danger">Decline</span>
                                                            @endif
                                                        </td> 
                                                        <td class="table-data">{{date("Y-m-d",strtotime($value->created_at))}}</td>
                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="8" class="no-records">No activity found</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                            @if(count($publish_activity) >= 5)
                                            <div class="pull-right">
                                                <a href="{{route('searchactivity')}}?statusactivity=1" >View more</a>
                                            </div>
                                            @endif
                                        </div>                    
                                    </div>

                                </div>
                                <!----------------->
                            </div>
                        </div>
                        <div id="tab-2" class="tab-pane">
                            <div class="panel-body">
                                <div class="wrapper wrapper-content">    
                                    <!------------------>
                                    <div class="ibox-content">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <!-- <th><input type="checkbox" class="i-checks " name="select_all" value="1" id="example-select-all" type="checkbox"  /></th> -->
                                                        <th class="control-label">Title</th>

                                                        <th class="control-label">City</th>
                                                        <th class="control-label">Category </th>

                                                        <th class="control-label">Status</th>
                                                        <th class="control-label">Created Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($decline_activity))
                                                    @foreach($decline_activity as $key => $value)
                                                    <tr>

<!-- <td class="table-data">
    
    <input type="checkbox" class="i-checks checkall i-event-show" id="sub_chk" data-id='{{$value->id}}' name="id[]" value='{{$value->id}}'>
</td> -->
                                                        <td class="table-data">{{$value->title}}</td>

                                                        <td class="table-data">{{($value->city != null) ? $value->city->city : "--"}}</td>
                                                        <td class="table-data">{{($value->category != null) ? $value->category->name : "--"}}</td> 
                                                        <!-- <td class="table-data">{{$value->actual_price}}</td>
                                                        <td class="table-data">{{$value->display_price}}</td> -->

                                                        <td class="table-data">
                                                            @if($value->admin_approve == "1")
                                                            <span class="label label-success ">Approve</span>            
                                                            @elseif($value->admin_approve == "0")
                                                            <span class="label label-warning">Pending</span>
                                                            @else
                                                            <span class="label label-danger">Decline</span>
                                                            @endif
                                                        </td> 
                                                        <td class="table-data">{{date("Y-m-d",strtotime($value->created_at))}}</td>

                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="8" class="no-records">No activity found</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>  
                                            @if(count($decline_activity) >= 5)
                                            <div class="pull-right">
                                                <a href="{{route('searchactivity')}}?statusactivity=2" >View more</a>
                                            </div>
                                            @endif
                                        </div>                    
                                    </div>

                                </div>
                                <!----------------->
                            </div>
                        </div>
                        <div id="tab-3" class="tab-pane">
                            <div class="panel-body">
                                <div class="wrapper wrapper-content">    
                                    <!------------------>
                                    <div class="ibox-content">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <!-- <th><input type="checkbox" class="i-checks " name="select_all" value="1" id="example-select-all" type="checkbox"  /></th> -->
                                                        <th class="control-label">Title</th>

                                                        <th class="control-label">City</th>
                                                        <th class="control-label">Category </th>

                                                        <th class="control-label">Status</th>
                                                        <th class="control-label">Created Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($pending_activity))
                                                    @foreach($pending_activity as $key => $value)
                                                    <tr>

<!-- <td class="table-data">
    
    <input type="checkbox" class="i-checks checkall i-event-show" id="sub_chk" data-id='{{$value->id}}' name="id[]" value='{{$value->id}}'>
</td> -->
                                                        <td class="table-data">{{$value->title}}</td>

                                                        <td class="table-data">{{($value->city != null) ? $value->city->city : "--"}}</td>
                                                        <td class="table-data">{{($value->category != null) ? $value->category->name : "--"}}</td> 
                                                        <!-- <td class="table-data">{{$value->actual_price}}</td>
                                                        <td class="table-data">{{$value->display_price}}</td> -->

                                                        <td class="table-data">
                                                            @if($value->admin_approve == "1")
                                                            <span class="label label-success ">Approve</span>            
                                                            @elseif($value->admin_approve == "0")
                                                            <span class="label label-warning">Pending</span>
                                                            @else
                                                            <span class="label label-danger">Decline</span>
                                                            @endif
                                                        </td> 
                                                        <td class="table-data">{{date("Y-m-d",strtotime($value->created_at))}}</td>

                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="8" class="no-records">No activity found</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                            @if(count($pending_activity) >= 5)
                                            <div class="pull-right">
                                                <a href="{{route('searchactivity')}}/?statusactivity=0" >View more</a>
                                            </div>
                                            @endif
                                        </div>                    
                                    </div>

                                </div>
                                <!----------------->
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>
</div>
@endsection
