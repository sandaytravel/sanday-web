@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading mlr-0">
    <div class="col-md-12">
        <div class="col-md-3">
            <h3> My Bookings</h3>
        </div>
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
                    <div class="row filter-div">
                        {!! Form::open(['route' => 'search-bookings','id'=>'search-booking'])!!}
                        <div class="col-md-12">            
                            {{ csrf_field() }}
                            <div class="col-md-2">
                                <label>Filter By Booking Date:</label>                
                            </div>
                            <div class="col-md-2">
                                <div class="input-group date form-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="from_date" value="{{$from_date or ''}}" placeholder="From Date" class="form-control" id="booking-from-date" autocomplete="off" readonly="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group date form-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" name="to_date" value="{{$to_date or ''}}" placeholder="To Date" class="form-control" id="booking-to-date" autocomplete="off" readonly="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::select('booking_status',[null => 'All Bookings','0' => "Pending",'1' => "Canceled",'2' => 'Confirmed'] ,(isset($booking_status)) ? $booking_status : '', ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col-md-2 pull-right">
                                <button type="submit" name="button" value="export" class="btn btn-sm btn-primary pull-right"> Export</button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-2">
                                <label>Activity Name:</label>                
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::select('activity',[null => 'All Activity'] + $merchantActivities ,(isset($activity)) ? $activity : '', ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>Package Name:</label>                
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::select('pacakge',[null => 'All Package'] + $packageLists ,(isset($package)) ? $package : '', ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Reference ID:</label>
                                <div class="col-lg-3">
                                    {{ Form::text('reference_number',isset($reference_number) ? $reference_number : '', ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Traveler Name:</label>
                                <div class="col-lg-3">
                                    {{ Form::text('traveler_name',isset($traveler_name) ? $traveler_name : '', ['class' => 'form-control']) }}
                                </div>
                            </div>                
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-2">
                                <button type="submit" name="button" value="search" class="btn btn-sm btn-primary"> Search</button>
                                <a href="{{route('booking-list')}}" class="btn btn-sm btn-default" title="Reset">Reset</a>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Reference#</th>
                                    <th>Booking Date</th>
                                    <th>Activity</th>
                                    <th>Package</th>
                                    <th>Traveler Name</th>
                                    <th>Booking Status</th>
                                    <th>Amount</th>
                                    <th class="control-label">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalEarning = 0; @endphp
                                @if(count($bookings))
                                @foreach($bookings as $key => $value)
                                <tr>
                                    <td class="table-data">{{$value->order_number}}</td>
                                    <td class="table-data">{{$value->booking_date}}</td>
                                    <td class="table-data">{{($value->activity != null) ? $value->activity->title : "--"}}</td>
                                    <td class="table-data">{{($value->oredr_ietms != null) ? $value->oredr_ietms[0]->activitypackageoptions->package_title : "--"}}</td>
                                    <td class="table-data">{{($value->user != null) ? $value->user->name : "--"}}</td>
                                    <td class="table-data">
                                        @if($value->status == 0)
                                        <span class="label label-warning">Pending</span>                                            
                                        @elseif($value->status == 1)
                                        <span class="label label-danger">Canceled</span>
                                        @else
                                        <span class="label label-primary ">Confirmed</span>        
                                        @endif
                                    </td>
                                    <td class="table-data">RM {{$value->order_total}} @php $totalEarning += $value->order_total; @endphp</td>
                                    <td class="table-data">
                                        <div class="btn-group table-group-button">
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false">Action <span class="caret"></span></button>
                                            <ul class="dropdown-menu dropdown-menu-right">  
                                                @if($value->status == 0)
                                                <li><a href="{{route('confirm-booking',[$value->id])}}" class="font-bold confirm-booking">Confirm Booking</a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{route('cancel-booking',[$value->id])}}" class="font-bold cancel-booking">Cancel Booking</a></li>
                                                <li class="divider"></li>
                                                @endif
                                                <li><a href="javascript:void(0)" data-id="{{$value->id}}" class="font-bold view-booking">View Booking</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="6" class="text-right"><b>Total Earning</b></td>
                                    <td><b>RM {{number_format($totalEarning,2)}}</b></td>
                                    <td></td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="8" class="no-records">No bookings found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>                    
                </div>
                @if(isset($bookings)){!! $bookings->render() !!}@endif
            </div>
        </div>

    </div>
</div>
<!--UPDATE SUBCATEGORY MODAL-->
<div class="modal inmodal" id="view-booking-detail" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog" style="min-width: 60%;">
        <div class="modal-content animated fadeIn" id="view-booking-content">
            
        </div>
    </div>
</div>
<!--End UPDATE-->
@endsection