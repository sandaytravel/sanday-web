@extends('layouts.base')
@section('content')
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
                <div class="ibox-title pagetitle">
                    <div class="row">
                        <div class="col-md-9">
                            <h3>Email Templates</h3>
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                        </div>
                        <div class="col-lg-2 pull-right">
                            <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary pull-right">Back</a>
                        </div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="control-label">Template For</th>
                                    <th class="control-label">Subject</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($templates))
                                @foreach($templates as $key => $value)
                                <tr>
                                    <td class="table-data">{{$value->name}}</td>
                                    <td class="table-data">{{$value->subject}}</td>
                                    <td class="table-data">
                                        <div class="btn-group table-group-button">
                                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" aria-expanded="false">Action <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('editemailtemplate',[$value->id])}}" class="font-bold">Edit</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="3" class="no-records">No templates found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>                    
                </div>
                @if(isset($templates)){!! $templates->render() !!}@endif
            </div>
        </div>

    </div>
</div>
@endsection