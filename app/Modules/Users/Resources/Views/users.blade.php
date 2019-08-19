@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <div class="col-lg-4">
            <h3> System Users</h3>
        </div>
        <div class="col-md-5 text-right pull-right">
            <a href="{{route('multipledelete',['replaceId'])}}" class="btn btn-outline btn-danger delete-selected" id="delete-selected-user">Delete</a>
            <a href="{{route('adduser')}}" type="button" class="btn btn-outline btn-primary">Add System User</a>
            <!-- <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary">Back</a> -->
    
        </div>
        <div class="col-sm-3 pull-right">
            {!! Form::open(['url'=>'systemusers/search','id'=>'search'])!!}
            {{ csrf_field() }}
            <div class="input-group"><input type="text" name="searchterm" value="{{ $searchterm or ''}}" placeholder="Search" class="input-sm form-control"> <span class="input-group-btn">
                    <button type="submit" class="btn btn-sm btn-primary"> Go!</button> 
                    <a href="{{route('users')}}" class="btn btn-default btn-circle" title="Reset"><i class="fa fa-refresh"></i></a></span>
            </div>
            {{ Form::close() }}

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
                                    <th><input type="checkbox" class="i-checks" name="select_all" value="1" id="example-select-all" type="checkbox"  /></th>
                                    <th class="control-label">Name </th>
                                    <th class="control-label">Email</th>
                                    <th class="control-label">Status</th>
                                    <th class="control-label">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($users))
                                @foreach($users as $key => $value)
                                <tr>
                                    <td class="table-data">
                                        @if(Auth::user()->id != $value->id)
                                        <input type="checkbox" class="i-checks checkall" id="sub_chk" data-id='{{$value->id}}' name="id[]" value='{{$value->id}}'>
                                        @endif
                                    </td>
                                    <td class="table-data">{{($value->name) ? $value->name : "--"}}</td> 
                                    <td class="table-data">{{($value->email) ? $value->email : "--"}}</td>                                   
                                    <td class="table-data">
                                        @if($value->status == "Active")
                                        @if(Auth::user()->id == $value->id)
                                        <span class="label label-primary" data-type="system user" data-id="{{$value->id}}">Active</span>
                                        @else
                                        <span class="label label-primary status-change" data-type="system user" data-id="{{$value->id}}">Active</span>
                                        @endif
                                        @else
                                        <span class="label status-change" data-type="system user" data-id="{{$value->id}}">Inactive</span><!--<input type="checkbox" class="i-checks merchant-status" name="status" data-type="merchant" data-id="{{$value->id}}">-->
                                        @endif
                                    </td>
                                    <td class="table-data">
                                        <div class="btn-group table-group-button">
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false">Action <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('edituser',[$value->id])}}" class="font-bold">Edit</a></li>
                                                @if(Auth::user()->id != $value->id)
                                                <li class="divider"></li>
                                                <li><a href="{{route('deleteuser',[$value->id])}}" class="font-bold delete-row" data-id='system user'>Delete</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="no-records">No system users found</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>                    
                </div>
                @if(isset($users)){!! $users->render() !!}@endif
            </div>
        </div>

    </div>
</div>
@endsection