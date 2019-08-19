@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading mlr-0">
    <div class="col-md-12">
        <div class="col-md-3">
            <h3> Orders</h3>
        </div>
        <div class="col-md-3 pull-right">
            <!-- <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary pull-right">Back</a> -->
        </div>
        
    </div>
    
    <div class="col-md-12 mt-15">
        <!-- <div class="col-md-1">
        </div> -->
        <div class="col-md-12">
            {!! Form::open(['url'=>'orders/search','id'=>'orderserch'])!!}
            {{ csrf_field() }}
                <div class="col-md-2">
                    <label>Transaction Id
                    </label>
                    <div class="form-group"><input type="text" name="transction_id" value="{{ $transction_id or ''}}" placeholder="Transction Id" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <label>Order #
                    </label>
                    <div class="form-group"><input type="text" name="order_id" value="{{ $order_id or ''}}" placeholder="Order #" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <label>Payment Status
                    </label>
                     {{ Form::select('payment_status',[null => '-Select Status-'] + $paymentstatus_list, (isset($orderstatus_id)) ? $orderstatus_id :'', ['class' => 'form-control', 'id' => 'payment-status']) }}
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                    <label>Booking from
                    </label>
                     {{ Form::text("participate_from",'', ['class' => 'form-control parti_from_validity', 'id' => 'parti_from_validity','autocomplete' => 'false']) }}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                    <label>Booking to
                    </label>
                     {{ Form::text("participate_to",'', ['class' => 'form-control parti_to_validity', 'id' => 'parti_to_validity','autocomplete' => 'false']) }}
                    </div>
                </div>
                <div class="col-md-2 input-group-btn">
                    <div style="display: table-cell;opacity: 0;height: 24px;">Participate to</div>
                    <button type="submit" class="btn btn-sm btn-primary"> Go!</button><a href="{{route('Orders')}}" class="btn btn-default btn-circle" title="Reset"><i class="fa fa-refresh"></i></a>
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
                                    <th>
                                    <!-- <input type="checkbox" class="i-checks " name="select_all" value="1" id="example-select-all" type="checkbox"  /></th> -->
                                    <th class="control-label">Transaction ID</th>
                                    <th class="control-label">Order #</th>
                                    <th class="control-label">Buyer</th>
                                    <!-- <th class="control-label">Activity Description</th> -->
                                    <th class="control-label">Amount </th>
                                    <!-- <th class="control-label">Actual Price</th>
                                    <th class="control-label">Display Price</th> -->
                                    <!-- <th class="control-label">Participate Date</th> -->
                                    <th class="control-label">Booking date</th> 
                                    <th class="control-label">Payment Status</th> 
                                    <th class="control-label">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($orderget))
                                @foreach($orderget as $key => $value)
                                <tr>    
                                    <td class="table-data">
                                        <!-- <input type="checkbox" class="i-checks checkall i-event-show" id="sub_chk" data-id='{{$value->id}}' name="id[]" value='{{$value->id}}'> -->
                                    </td>
                                    <td class="table-data">{{$value->transaction_number}}</td>
                                    <td class="table-data">
                                        @foreach ($value->orders as $orderkey => $ordervalue)
                                        {{$ordervalue->order_number}}
                                        </br>
                                        @endforeach
                                    </td>
                                    <td class="table-data">
                                        @foreach ($value->orders as $orderkey => $ordervalue)
                                            @if($orderkey == 0 )
                                                {{$ordervalue->user->name}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="table-data">
                                        @php
                                        $sum = 0;
                                        foreach ($value->orders as $orderkey => $ordervalue){
                                            $sum += $ordervalue->order_total;
                                        }
                                        @endphp
                                        RM {{$sum}}
                                    </td>
                                    <td class="table-data">
                                        {{date("Y-m-d",strtotime($value->created_at))}}
                                    </td>
                                    <td class="table-data">
                                        @if($value->paymet_status == "Pending")
                                            <span class="label label-info">Pending</span>            
                                        @elseif($value->paymet_status == "Completed")
                                            <span class="label label-success">Completed</span>
                                        @else
                                            <span class="label label-danger">Failed</span>
                                        @endif

                                    </td>
                                    <td class="table-data">
                                        <div class="btn-group table-group-button">
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false">Action <span class="caret"></span></button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="{{route('orderview',[$value->id]) }}" class="font-bold " data-id='Order'>View Order</a></li>
                                                <!-- <li class="divider"></li>
                                                <li><a href="{{route('orderStatusUpdate',[$value->id,'pending']) }}" class="font-bold order-statusac" data-id='Pending'>Pending </a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{route('orderStatusUpdate',[$value->id,'canceled']) }}" class="font-bold order-statusac" data-id='Canceled'>Canceled</a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{route('orderStatusUpdate',[$value->id,'confirmed']) }}" class="font-bold order-statusac" data-id='Confirmed'>Confirmed</a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{route('orderStatusUpdate',[$value->id,'expired']) }}" class="font-bold order-statusac" data-id='Expired'>Expired</a></li> -->
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                               {{-- @foreach($orderget as $key => $value)
                                <tr>
                                    
                                    <td class="table-data">
                                        
                                        <!-- <input type="checkbox" class="i-checks checkall i-event-show" id="sub_chk" data-id='{{$value->id}}' name="id[]" value='{{$value->id}}'> -->
                                    </td>
                                    <td class="table-data">{{$value->transaction->transaction_number}}</td>
                                    <td class="table-data">{{$value->order_number}}</td>
                                    <td class="table-data">{{$value->user->name}}</td>
                                    <td class="table-data">{{$value->activity->title}}</br>
                                    @php
                                    foreach ($value->oredr_ietms as $orderkey => $ordervalue) {
                                        if ($orderkey == 0 ){
                                            echo $ordervalue->activitypackageoptions->package_title;
                                            echo '</br>';
                                        }
                                        echo $ordervalue->packagequantity->name.' ('.$ordervalue->quantity.').';
                                        echo '</br>';
                                    }
                                    @endphp
                                    </td> 
                                    <td class="table-data">RM {{number_format($value->order_total,2)}}</td>
                                    <td class="table-data">{{$value->booking_date}}</td>
                                    <td class="table-data">{{date("Y-m-d",strtotime($value->created_at))}}</td>
                                    <td class="table-data">
                                        @if($value->status == "0")
                                            <span class="label label-info ">Pending</span>            
                                        @elseif($value->status == "1")
                                            <span class="label label-danger">Canceled</span>
                                        @elseif($value->status == "2")
                                            <span class="label label-success">Confirmed</span>
                                        @elseif($value->status == "3")
                                            <span class="label label-warning">Expired</span>
                                        @endif
                                    </td> 
                                    <td class="table-data">
                                        <div class="btn-group table-group-button">
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false">Action <span class="caret"></span></button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="{{route('orderview',[$value->id]) }}" class="font-bold " data-id='Order'>View Order</a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{route('orderStatusUpdate',[$value->id,'pending']) }}" class="font-bold order-statusac" data-id='Pending'>Pending </a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{route('orderStatusUpdate',[$value->id,'canceled']) }}" class="font-bold order-statusac" data-id='Canceled'>Canceled</a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{route('orderStatusUpdate',[$value->id,'confirmed']) }}" class="font-bold order-statusac" data-id='Confirmed'>Confirmed</a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{route('orderStatusUpdate',[$value->id,'expired']) }}" class="font-bold order-statusac" data-id='Expired'>Expired</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                @endforeach
                               --}}
                                @else
                                <tr>
                                    <td colspan="10" class="no-records">No order found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>                    
                </div>
                @if(isset($orderget)){{$orderget->links() }}@endif
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