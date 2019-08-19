@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h3> Update Email Template</h3>
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
                    {{ Form::open(['route' => ['updateemailtemplate',$template->id], 'method' => 'POST', 'id'=>'email-template-form']) }}
                    <input type="hidden" name="id" value="{{$template->id}}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Subject: <span class="input-required">*</span></label>
                                    {{ Form::text('subject',$template->subject, ['class' => 'form-control', 'id' => 'subject']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Message:</label>
                                    {{ Form::textarea('content',$template->content, ['class' => 'form-control', 'id' => 'content']) }}
                                    @ckeditor('content')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <a href="{{route('emailtemplate')}}" class="btn btn-white">Cancel</a>
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection