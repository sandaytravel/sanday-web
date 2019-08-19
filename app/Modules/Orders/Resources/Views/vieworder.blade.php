@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading mlr-0">
    <div class="col-md-12">
        <div class="col-md-3">
            <h3>Order Summary</h3>
        </div>
        <div class="col-md-3 pull-right">
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
                                    <h3><strong>Transaction #: {{$orderView->transaction_number}}</strong></h3>
                                    <!-- <h3><strong>Order #: {{$orderView->order_number}}</strong></h3> -->
                                    <p><i class="fa fa-calendar"></i> <strong>Order Date: </strong> {{date('Y-m-d', strtotime($orderView->created_at))}}</p>
                                    <p><strong>Customer name: </strong>
                                    @foreach ($orderView->orders as $key => $value)
                                        @if($key == 0)
                                            {{$value->user->name}}
                                        @endif
                                    @endforeach
                                    </p>
                                    <p><strong>Payment Status: </strong>
                                    @if($orderView->paymet_status == "Pending")
                                        <span class="label label-info">Pending</span>            
                                    @elseif($orderView->paymet_status == "Completed")
                                        <span class="label label-success">Completed</span>
                                    @else
                                        <span class="label label-danger">Failed</span>
                                    @endif
                                    </p> 
                                    <!-- <p><strong>Order Status: </strong>
                                    @if($orderView->status == "0")
                                        <span class="label label-info ">Pending</span>            
                                    @elseif($orderView->status == "1")
                                        <span class="label label-danger">Canceled</span>
                                    @elseif($orderView->status == "2")
                                        <span class="label label-success">Confirmed</span>
                                    @elseif($orderView->status == "3")
                                        <span class="label label-warning">Expired</span>
                                    @endif
                                    </p> -->
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
                                    <th width="10%">Order #</th>
                                    <th width="15%">Activity Name</th>
                                    <th width="20%">Activity Package</th>
                                    <th width="10%">Activity Image</th>
                                    <th width="15%">Quantity</th>
                                    <th width="10%">Participate Date</th>
                                    <th width="8%">Stauts</th>
                                    <th width="10%">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                               @foreach ($orderView->orders as $key => $value)
                               <tr>
                                    <td><div><strong>{{$value->order_number}}</strong></div>
                                    </td>
                                    <td><div><strong>{{$value->activity->title}}</strong></div>
                                    </td>
                                    <!-- <td><div><strong>{{$value->activity->title}}</strong></div>
                                    </td> -->
                                    <td>
                                    @php
                                    foreach ($value->oredr_ietms as $orderkey => $ordervalue) {
                                        if ($orderkey == 0 ){
                                            echo $ordervalue->activitypackageoptions->package_title;
                                            echo '</br>';
                                        }
                                    }
                                    @endphp
                                    </td>
                                    <td>
                                        <img id="image-preview" class="image-priview-activity img-pop" src="{{url('public/img/activity/fullsized/'.$value->activity->image)}}" alt="" />
                                    </td>
                                    <td>
                                    @php
                                    foreach ($value->oredr_ietms as $orderkey => $ordervalue) {
                                        echo $ordervalue->packagequantity->name.' ('.$ordervalue->quantity.').';
                                        echo '</br>';
                                    }
                                    @endphp
                                    </td>
                                    <td><div><strong>{{$value->booking_date}}</strong></div>
                                    </td>
                                    <td>
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
                                    <td>
                                    RM {{$value->order_total}}
                                    </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- /table-responsive -->

                    <table class="table invoice-total">
                        <tbody>
                            <!-- <tr>
                                <td><strong>Admin Handling Fee :</strong></td>
                                <td>RM 0.00</td>
                            </tr>
                            <tr>
                                <td><strong>Transaction Fee :</strong></td>
                                <td>RM 0.00</td>
                            </tr> -->
                            <tr>
                                <td><strong>TOTAL :</strong></td>
                                <td>
                                @php
                                $sum = 0;
                                foreach($orderView->orders as $key => $value) {
                                    $sum += $value->order_total; 
                                }
                                echo 'RM '.$sum;
                                @endphp
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection