@extends('layouts.base')
@section('content')
<div class="wrapper wrapper-content">
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
        <div class="pd-lr15" id="dashboard-counter">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title dashboard-title">
                        <span class="label label-success pull-right">Weekly</span>
                        <h5>Customers</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$usersCount}}</h1>
                        <small>Total customers</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title dashboard-title">
                        <span class="label label-info pull-right">Weekly</span>
                        <h5>Orders</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$ordersCount}}</h1>
                        <small>Total orders</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title dashboard-title">
                        <span class="label label-primary pull-right">Weekly</span>
                        <h5>Activities</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$activityCount}}</h1>
                        <small>Total activities</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title dashboard-title">
                        <span class="label label-danger pull-right">Weekly</span>
                        <h5>Revenue</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">RM {{number_format($totalRevenue,2)}}</h1>
                        <small>Total revenue</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="pd-lr15">   
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title dashboard-title">
                        <h5>Recent Customer Sign Up</h5>
                        <div class="ibox-tools">
                            <a href="{{route('customers')}}" class="btn btn-primary btn-xs btn-rounded pull-right">View More</a>
                        </div>
                    </div>
                    <div class="ibox-content dashboard-content">
                        <div>
                            @if(count($customer))
                            @foreach($customer as $key => $value)
                            <div class="feed-activity-list">
                                <div class="feed-element">
                                    <div class="media-body ">
                                        <strong>{{$value->name}} </strong> <br>
                                        {{$value->email}} <br>
                                        <small class="text-muted">{{date('Y-m-d', strtotime($value->created_at))}}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title dashboard-title">
                        <h5>Recent Orders</h5>
                        <div class="ibox-tools">
                            <a href="{{route('Orders')}}" class="btn btn-primary btn-xs btn-rounded pull-right">View More</a>
                        </div>
                    </div>

                    <div class="ibox-content dashboard-content">
                        <div>
                            @if(count($orders))
                            <div class="feed-activity-list">
                                @foreach($orders as $key => $value)
                                <div class="feed-element">
                                    <div>
                                        <span class="label label-success pull-right">RM {{number_format($value->order_total,2)}}</span>
                                        <a href="{{route('orderview',[$value->id]) }}" class="font-bold " data-id='Order'>
                                            <strong>{{(isset($value->activity)) ? $value->activity->title : ""}}</strong>
                                        </a>
                                        <div>
                                            @php
                                            foreach ($value->oredr_ietms as $orderkey => $ordervalue) {
                                            if($orderkey == 0){
                                            echo $ordervalue->activitypackageoptions->package_title;
                                            echo '</br>';
                                            }
                                            echo $ordervalue->packagequantity->name.' ('.$ordervalue->quantity.').';
                                            echo '</br>';
                                            }
                                            @endphp
                                        </div>
                                        <small class="text-muted">{{date('Y-m-d', strtotime($value->created_at))}}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title dashboard-title">
                        <h5>Recent Activities</h5>
                        <div class="ibox-tools">
                            <a href="{{route('activity')}}" class="btn btn-primary btn-xs btn-rounded pull-right">View More</a>
                        </div>
                    </div>
                    <div class="ibox-content dashboard-content">
                        <div>
                            @if(count($activity))
                            <div class="feed-activity-list">
                                @foreach($activity as $key => $value)
                                <div class="feed-element">
                                    <div class="media-body ">
                                        <a href="{{route('updateactivity',[$value->id])}}" class="font-bold">
                                            <strong>{{$value->title}} </strong>
                                        </a> <br>
                                        @php
                                        foreach ($value->activitypackageoptions as $optionkey => $optionvalue) {
                                        if($optionkey == 0){
                                        if($optionvalue->display_price > 0){
                                        echo $optionvalue->display_price;
                                        }else{
                                        echo $optionvalue->actual_price;
                                        }
                                        echo '</br>';
                                        }
                                        }
                                        @endphp
                                        <small class="text-muted">{{date('Y-m-d', strtotime($value->created_at))}}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection