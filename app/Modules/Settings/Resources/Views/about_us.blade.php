@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <div class="col-lg-4">
            <h3> About us</h3>
        </div>
        <div class="col-lg-3 pull-right">
            <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary pull-right">Back</a>
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
                 {!! Form::open(['url'=>'settings/about-us/update','id'=>'update-aboutus-form','enctype' => 'multipart/form-data'])!!}      
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="control-label">Description: </label>
                            </div>
                            <div class="col-md-12">
                            <?php
                                $description ='';
                                if(!empty($aboutus['content'])){
                                    $description = $aboutus['content'];
                                }   
                            ?>
                            {{ Form::textarea('description',$description,['id' => 'description']) }}                    
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Update</button>
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

@section('javascript')
<script>
    $(document).ready(function () {
        $('#description').summernote({
        height: 200,
        })
    });
</script>
@endsection
