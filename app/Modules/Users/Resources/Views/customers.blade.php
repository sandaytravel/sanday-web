@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <div class="col-lg-4">
            <h3> Customers</h3>
        </div>
        <div class="col-sm-3 ">
            {!! Form::open(['url'=>'customer/search','id'=>'search'])!!}
            {{ csrf_field() }}
            <div class="input-group"><input type="text" name="searchterm" value="{{ $searchterm or ''}}" placeholder="Search" class="input-sm form-control"> <span class="input-group-btn">
                    <button type="submit" class="btn btn-sm btn-primary"> Go!</button> 
                    <a href="{{route('customers')}}" class="btn btn-default btn-circle" title="Reset"><i class="fa fa-refresh"></i></a></span>
            </div>
            {{ Form::close() }}

        </div>
        <div class="col-lg-2 pull-right">
            <!-- <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary pull-right">Back</a> -->
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
                                    <th class="control-label">First Name</th>
                                    <th class="control-label">Family Name</th>
                                    <th class="control-label">Email</th>
                                    <th class="control-label">Phone Number</th>
                                    <th class="control-label">Registration Date</th>
                                    <th class="control-label">Registration Type</th>
                                    <th class="control-label">Enable/Disable</th>
                                    <th class="control-label">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($customers))
                                @foreach($customers as $key => $value)
                                <tr>
                                    <td class="table-data">{{($value->name) ? $value->name : "--"}}</td> 
                                    <td class="table-data">{{($value->family_name) ? $value->family_name : "--"}}</td> 
                                    <td class="table-data">{{($value->email) ? $value->email : "--"}}</td>
                                    <td class="table-data">{{($value->country_code) ? $value->country_code : " "}} {{($value->mobile_number) ? $value->mobile_number : "--"}}</td>
                                    <td class="table-data">
                                    @php
                                    $timestamp = "$value->created_at";
                                    $datetime = explode(" ",$timestamp);
                                    $date = $datetime[0];
                                    @endphp
                                    {{($date) ? $date : "--"}}</td>
                                    <td class="table-data">
                                        @if($value->registration_type == 1)
                                        <span class="label label-success">Normal </span>
                                        @else
                                        <span class="label label-primary">Facebook</span>
                                        @endif
                                    </td>                                    
                                    <td class="table-data">
                                        @if($value->status == "Active")
                                        <span class="label label-primary status-change" data-type="customer" data-id="{{$value->id}}">Active</span>
                                        @else
                                        <span class="label status-change" data-type="customer" data-id="{{$value->id}}">Inactive</span><!--<input type="checkbox" class="i-checks merchant-status" name="status" data-type="merchant" data-id="{{$value->id}}">-->
                                        @endif
                                    </td>
                                    <td class="table-data">
                                        <div class="btn-group table-group-button">
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false">Action <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('viewcustomer',[$value->id])}}" class="font-bold">View</a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{url('orders/search?customerid='.$value->id)}}" class="font-bold">View Orders</a></li>
                                                <li class="divider"></li>
                                                <li><a href="{{route('deletecustomer',[$value->id])}}" class="font-bold delete-row" data-id='customer'>Delete</a></li>
                                            </ul>
                                        </div>
                                        <!-- <div class="btn-group table-group-button">
                                            <button data-toggle="dropdown" class="btn btn-primary " aria-expanded="false"><a href="{{route('deletecustomer',[$value->id])}}" data-id="customer" class="font-bold delete-row color_white" data-id='system user'>Delete</a></button>
                                        </div> -->
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="no-records">No customers found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>                    
                </div>
                @if(isset($customers)){!! $customers->render() !!}@endif
            </div>
        </div>

    </div>
</div>
@endsection
