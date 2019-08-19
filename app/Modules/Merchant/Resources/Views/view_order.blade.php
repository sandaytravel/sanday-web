<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">Order #:{{$booking->order_number}}</h4>
    <h5>Booking Date: {{date('d M Y h:i A',strtotime($booking->created_at))}}</h5>
</div>
<div class="modal-body">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h4>Booking details</h4>            
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-12 content no-top-border">
                    <div>
                        <div class="row vertical-align">
                            <div class="col-xs-2 text-right">
                                @if($booking->status == 0)
                                <i class="fa fa-clock-o fa-5x"></i>
                                @elseif($booking->status == 1)
                                <i class="fa fa-times fa-5x"></i>
                                @else
                                <i class="fa fa-check fa-5x"></i>
                                @endif
                            </div>
                            <div class="col-xs-4">
                                <h2 class="font-bold margin-zero">
                                    @if($booking->status == 0)
                                    {{"Pending"}}
                                    @elseif($booking->status == 1)
                                    {{"Canceled"}}
                                    @else
                                    {{"Confirmed"}}
                                    @endif
                                </h2>
                            </div>
                            <div class="col-xs-6">
                                <p><b>Customer Name:</b> {{$booking->user->title .' '.$booking->user->name}}</p>
                                <p><b>Phone Number:</b> {{$booking->user->country_code .' '.$booking->user->mobile_number}}</p>
                                <p><b>Email:</b> {{$booking->user->email}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ibox-content ibox-heading">
            <h3>{{$booking->activity->title}}</h3>
            <small><i class="fa fa-map-marker"></i> Participation Date: {{date('d M Y',strtotime($booking->booking_date))}} </small>
        </div>
        <div class="ibox-content inspinia-timeline">
            <div class="timeline-item">
                <div class="row">
                    <div class="col-xs-2">
                        <img src="{{admin_asset('img/activity/resized/'.$booking->activity->image)}}" class="img-responsive"/>
                    </div>
                    <div class="col-xs-7 content no-top-border">
                        <p class="m-b-xs"><strong>Package</strong></p>

                        <p>{{$booking->oredr_ietms[0]->activitypackageoptions->package_title}}</p>
                        @if(count($booking->oredr_ietms))
                        @foreach($booking->oredr_ietms as $key => $value)
                        <div class="col-xs-6">
                            <span>{{$value->quantity}} x {{$value->packagequantity->name}} </span>
                        </div>
                        <div class="col-xs-6">
                            <span>RM {{$value->total}} </span>
                        </div>
                        @endforeach
                        <div class="col-xs-12 blank_row"></div>
                        <div class="col-xs-6 text-right">
                            <span>Payment Amount:</span>
                        </div>
                        <div class="col-xs-6">
                            <span>RM {{$booking->order_total}} </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>               
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
    @if($booking->status == 0)
    <a href="{{route('confirm-booking',[$booking->id])}}" class="btn btn-primary confirm-booking">Confirm</a>
    <a href="{{route('cancel-booking',[$booking->id])}}" class="btn btn-danger cancel-booking">Cancel</a>
    @endif
</div>