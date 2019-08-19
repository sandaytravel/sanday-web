@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h3> Explore</h3>
    </div>
    <div class="col-lg-2"></div>
</div>
<div class="wrapper wrapper-content">    
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
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
                <div class="ibox-title">
                    <h5>All form elements with * are required fields.</h5>
                </div>
                <div class="ibox-content">
                    {{ Form::open(['route' => ['updateexplore',$explore->id], 'method' => 'POST', 'id'=>'explore-form','files' => true]) }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Title: <span class="input-required">*</span></label>
                                    {{ Form::text('title',$explore->title, ['class' => 'form-control', 'id' => 'title']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Description:</label>
                                    {{ Form::textarea('description',$explore->description, ['class' => 'form-control', 'id' => 'description','rows' => 4]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Image:</label>
                                    {{ Form::file('image[]', ['class' => 'form-control-file','id' => 'explore-images','accept="image/*"','multiple' => true]) }}
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 gallery">
                            @if(count($explore->images))
                            @foreach($explore->images as $imageKey => $imageValue)
                            <div class="preview-div">
                                <img src="{{admin_asset('img/explore/fullsized/'.$imageValue->image)}}" class="preview-image"/>
                                <a href="javascript:void(0)" data-id="{{$imageValue->id}}" class="image-delete" title="Remove Image"><i class="fa fa-trash-o"></i></a>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <a href="{{route('explore')}}" class="btn btn-white">Cancel</a>
                                    <button class="btn btn-primary" type="submit">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection