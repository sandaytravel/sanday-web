@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading mlr-0">
    <div class="col-md-12">
        <div class="col-md-3">
            <h3>Order Summary</h3>
        </div>
        <div class="col-lg-2 pull-right">
            <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary pull-right">Back</a>
        </div>
    </div>
</div>
<div class="wrapper wrapper-content">
<div class="row">
        <div class="col-lg-12">
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="ibox-content p-xl">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="contact-box">
                                <div class="col-sm-12">
                                    <h3><strong>Transaction #: {{$orderView->transaction->transaction_number}}</strong></h3>
                                    <h3><strong>Order #: {{$orderView->order_number}}</strong></h3>
                                    <p><i class="fa fa-calendar"></i> <strong>Order Date: </strong> {{date('Y-m-d', strtotime($orderView->created_at))}}</p>
                                    <p><strong>Total Price: </strong>RM {{$orderView->order_total}}</p>
                                    <p><strong>Order Status: </strong>
                                    @if($orderView->status == "0")
                                        <span class="label label-info ">Pending</span>            
                                    @elseif($orderView->status == "1")
                                        <span class="label label-danger">Canceled</span>
                                    @elseif($orderView->status == "2")
                                        <span class="label label-success">Confirmed</span>
                                    @elseif($orderView->status == "3")
                                        <span class="label label-warning">Expired</span>
                                    @endif
                                    </p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="col-md-3"></div>
                        <!-- <div class="col-md-4">
                            <div class="contact-box">
                                
                                <div class="col-sm-12">
                                    <h3><strong><i class="fa fa-truck"></i> Activity Information</strong></h3>
                                    <p><i class="fa fa-map-marker"></i> Ahmednagar</p>
                                    <address>
                                        Ahmednagar<br>
                                        Ahmednagar<br>
                                        Maharashtra<br>
                                        Maharashtra414002<br>
                                        <abbr title="Phone">P:</abbr> 6557657657
                                    </address>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div> -->
                    </div>
                    <div class="table-responsive m-t">
                        <table class="table invoice-table">
                            <thead>
                                <tr>
                                    <th width="15%">Customer Name</th>
                                    <th width="17%">Activity Name</th>
                                    <th width="20%">Activity Package</th>
                                    <th width="8%">Activity Image</th>
                                    <th width="10%">Booking Date</th>
                                    <th width="25%">Quantity</th>
                                    <th width="10%">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div><strong>{{$orderView->user->name}}</strong></div>
                                    </td>
                                    <td><div><strong>{{$orderView->activity->title}}</strong></div>
                                    </td>
                                    <td>
                                    @php
                                    foreach ($orderView->oredr_ietms as $orderkey => $ordervalue) {
                                        if ($orderkey == 0 ){
                                            echo $ordervalue->activitypackageoptions->package_title;
                                            echo '</br>';
                                        }
                                    }
                                    @endphp
                                    </td>
                                    <td>
                                        <img id="image-preview" class="image-priview-activity img-pop" src="{{url('public/img/activity/fullsized/'.$orderView->activity->image)}}" alt="" />
                                    </td>
                                    <td>{{date("Y-m-d",strtotime($orderView->booking_date))}}</td>
                                    <td>
                                    @php
                                    foreach ($orderView->oredr_ietms as $orderkey => $ordervalue) {
                                        echo $ordervalue->packagequantity->name.' X ('.$ordervalue->quantity.').';
                                        echo '</br>';
                                    }
                                    @endphp
                                    </td>
                                    <td> 
                                    @php
                                    foreach ($orderView->oredr_ietms as $orderkey => $ordervalue) {
                                            echo 'RM';
                                            echo $ordervalue->package_price;
                                            echo '</br>';
                                        
                                    }
                                    @endphp
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- /table-responsive -->

                    <!-- <table class="table invoice-total">
                        <tbody>
                            <tr>
                                <td><strong>Admin Handling Fee :</strong></td>
                                <td>RM 0.00</td>
                            </tr>
                            <tr>
                                <td><strong>Transaction Fee :</strong></td>
                                <td>RM 0.00</td>
                            </tr>
                            <tr>
                                <td><strong>TOTAL :</strong></td>
                                <td>RM 858.00</td>
                            </tr>
                        </tbody>
                    </table> -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection