@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h3> Edit System User</h3>
    </div>
    <div class="col-lg-2 pull-right">
        <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary pull-right">Back</a>
    </div>
</div>
<div class="wrapper wrapper-content">    
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>All form elements with * are required fields.</h5>
                </div>
                <div class="ibox-content">
                    {{ Form::open(['route' => ['update', $user->id], 'method' => 'POST', 'id'=>'user-form']) }}

                    <input type="hidden" name="actionname" value="{{Route::current()->getName()}}" id="actionname"/>
                    <input type="hidden" name="id" value="{{$user->id}}" id="user-id"/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Name: <span class="input-required">*</span></label>
                                    {{ Form::text('name',$user->name, ['class' => 'form-control', 'id' => 'name']) }}
                                </div>
                            </div>                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Email: <span class="input-required">*</span></label>
                                    @if(Auth::user()->id == $user->id)
                                    {{ Form::text('email',$user->email, ['class' => 'form-control', 'id' => 'email','readonly']) }}
                                    @else
                                    {{ Form::text('email',$user->email, ['class' => 'form-control', 'id' => 'email']) }}
                                    @endif
                                </div>  
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-2">
                                <div class="form-group"><label class="control-label">Status:</label>                                
                                    @if(Auth::user()->id == $user->id)
                                    {{ Form::select('status',["Active" => "Active","Inactive" => "Inactive"],$user->status ,['class' => 'form-control', 'id' => 'status','disabled']) }}
                                    @else
                                    {{ Form::select('status',["Active" => "Active","Inactive" => "Inactive"],$user->status ,['class' => 'form-control', 'id' => 'status']) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <a href="{{route('users')}}" class="btn btn-white">Cancel</a>
                                    <button class="btn btn-primary" type="submit">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

</div>
@endsection