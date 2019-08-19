@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <div class="col-lg-5">
            <h3> Categories</h3>
        </div>
        <div class="col-md-4 text-right pull-right">
            <a href="javascript:void(0);" data-toggle="modal" data-target="#add-category" type="button" class="btn btn-outline btn-primary">Add Category</a>
            <a href="javascript:void(0);" data-toggle="modal" data-target="#add-subcategory" type="button" class="btn btn-outline btn-primary">Add Subcategory</a>
            <!-- <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary">Back</a> -->
        </div>
        <div class="col-sm-3 pull-right">
            {!! Form::open(['url'=>'category/search','id'=>'search'])!!}
            {{ csrf_field() }}
            <div class="input-group"><input type="text" name="searchterm" value="{{ $searchterm or ''}}" placeholder="Search" class="input-sm form-control"> <span class="input-group-btn">
                    <button type="submit" class="btn btn-sm btn-primary"> Go!</button> 
                    <a href="{{route('categories')}}" class="btn btn-default btn-circle" title="Reset"><i class="fa fa-refresh"></i></a></span>
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
                <div class="ibox-content" id="location-list">
                    <div class="panel-group" id="accordion1">
                        @if(count($categories))
                        @foreach($categories as $key => $value)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-md-10">
                                            <h4 class="panel-title">                                    
                                                <a role="button" class="links" data-toggle="collapse" data-parent="#accordion1" href="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                                                    <h4 class="panel-title">
                                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                                        {{$value->name}}    
                                                        @if($value->icon != "")
                                                        <img src="{{admin_asset('img/icons/resized/'.$value->icon)}}" style="width: 30px;">
                                                        @else
                                                        {{"--"}}
                                                        @endif
                                                    </h4>
                                                </a>
                                            </h4>
                                        </div>
                                        <div class="col-md-2">
                                            @if (count($value->activities))
                                                <a href="javascript:void(0)" class="btn btn-danger btn-circle pull-right non-deleteable" data-id='category' type="button" title="Delete Category"><i class="fa fa-remove"></i></a>
                                            @else
                                                <a href="{{route('deletecategory',[$value->id])}}" class="btn btn-danger btn-circle pull-right delete-row delete-category" data-id='category' type="button" title="Delete Category"><i class="fa fa-remove"></i></a>
                                                
                                            @endif
                                            <a class="btn btn-primary btn-circle pull-right edit-category" data-category='{{$value->name}}' data-id='{{$value->id}}' data-image="{{admin_asset('img/icons/fullsized/'.$value->icon)}}" type="button" title="Edit Category"><i class="fa fa-pencil"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse{{$key}}" class="panel-collapse collapse">
                                <div class="panel-body">
                                    @if(count($value->subcategories))
                                    @foreach($value->subcategories as $subcategorykey => $subcategoryvalue)
                                    <div class="category-panel-body">
                                        <div class="panel-group" id="accordion21">
                                            <div class="panel">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="col-md-10">
                                                            <a role="button" data-toggle="collapse" data-parent="#accordion{{$subcategorykey}}{{$key}}" href="#collapse{{$subcategorykey}}{{$key}}" aria-expanded="true" aria-controls="collapse{{$subcategorykey}}{{$key}}">
                                                                <h4 class="panel-title">
                                                                    <!--<i class="more-less glyphicon glyphicon-plus"></i>-->
                                                                    {{$subcategoryvalue->name}}
                                                                </h4>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a class="btn btn-primary btn-circle edit-subcategory" data-id="{{$subcategoryvalue->id}}" data-subcategory="{{$subcategoryvalue->name}}" type="button" title="Edit Subcategory"><i class="fa fa-pencil"></i></a>
                                                            @if (count($subcategoryvalue->activities))
                                                                <a href="javascript void(0)" class="btn btn-danger btn-circle non-deleteable" data-id='subcategory' type="button" title="Delete Subcategory"><i class="fa fa-remove"></i></a>
                                                            @else
                                                                <a href="{{route('deletesubcategory',[$subcategoryvalue->id])}}" class="btn btn-danger btn-circle delete-row" data-id='subcategory' type="button" title="Delete Subcategory"><i class="fa fa-remove"></i></a>
                                                            @endif   
                                                                
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <span class="no-records">No subcategory found</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <span class="no-records">No categories found</span>
                        @endif
                    </div>
                    <!-- panel-group -->
                </div>
            </div>
        </div>

    </div>
</div>
<!--End Add--> 
<!--ADD CATEGORY MODAL-->
<div class="modal inmodal" id="add-category" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            {!! Form::open(['url'=>'category/add','id'=>'add-category-form','enctype' => 'multipart/form-data'])!!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add Category</h4>
            </div>
            <div class="modal-body">                
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Category: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" name="actionname" id="actionname" value="add" />
                        <input type="hidden" name="category_id" id="category_id" value="empty" />
                        {{ Form::text('category','',['class' => 'form-control','placeholder' => 'Enter category name']) }}                      
                    </div>                    
                </div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Icon: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        {{ Form::file('image','',['class' => 'form-control-file']) }}                      
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Subcategory: </label>
                    </div>
                    <div class="col-md-6">
                        {{ Form::text('subcategory[]','',['class' => 'form-control','placeholder' => 'Enter subcategory name']) }}                      
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary addButton" type="button"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="row form-group hide" id="optionTemplate">
                    <div class="col-md-4">
                        <label class="control-label">Subcategory: </label>
                    </div>
                    <div class="col-md-6">
                        {{ Form::text('subcategory[]','',['class' => 'form-control subcategory-name','placeholder' => 'Enter subcategory name']) }}                      
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-danger removeButton" type="button"><i class="fa fa-minus"></i></button>
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary submit-button">Save</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!--End Add--> 
<!--UPDATE CATEGORY MODAL-->
<div class="modal inmodal" id="update-category" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            {!! Form::open(['url'=>'category/update','id'=>'update-category-form','enctype' => 'multipart/form-data'])!!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Update Category</h4>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Category: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" name="category_id" id="category_id" value="" />
                        {{ Form::text('category','',['class' => 'form-control','id' => 'category','placeholder' => 'Enter category name']) }}                      
                    </div>                    
                </div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Icon: </label>
                    </div>
                    <div class="col-md-4">
                        {{ Form::file('image','',['class' => 'form-control-file' , "onchange" =>  "readURL(this)"]) }}
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <img class="img-responsive" id="category-image"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary submit-button">Save</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!--End UPDATE-->
<!--ADD SUBCATEGORY MODAL-->
<div class="modal inmodal" id="add-subcategory" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            {!! Form::open(['url'=>'subcategory/add','id'=>'add-subcategory-form'])!!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add Sub Category</h4>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Category: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" name="actionname" id="actionname" value="add" />
                        <input type="hidden" name="subcategory_id" id="subcategory_id" value="empty" />
                        {{ Form::select('category',[null => '--Select Category--'] + $categoriesArray,'',['class' => 'form-control']) }}                      
                    </div>                    
                </div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Subcategory: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        {{ Form::text('subcategory[]','',['class' => 'form-control','placeholder' => 'Enter subcategory name']) }}                      
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary addButton" type="button"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="row form-group hide" id="optionTemplate2">
                    <div class="col-md-4">
                        <label class="control-label">Subcategory: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        {{ Form::text('subcategory[]','',['class' => 'form-control subcategory-name','placeholder' => 'Enter subcategory name']) }}                      
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-danger removeButton" type="button"><i class="fa fa-minus"></i></button>
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary submit-button">Save</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!--End ADD-->
<!--UPDATE SUBCATEGORY MODAL-->
<div class="modal inmodal" id="update-subcategory" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            {!! Form::open(['url'=>'subcategory/update','id'=>'update-subcategory-form'])!!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Update Category</h4>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Subcategory: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" name="subcategory_id" id="subcategory_id" value="" />
                        {{ Form::text('subcategory','',['class' => 'form-control','id' => 'subcategory','placeholder' => 'Enter subcategory name']) }}                      
                    </div>                    
                </div>               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary submit-button">Save</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!--End UPDATE-->
@endsection
@section('javascript')
<script>
    $(document).ready(function () {
        function toggleIcon(e) {
            $(e.target)
                    .prev('.panel-heading')
                    .find(".more-less")
                    .toggleClass('glyphicon-plus glyphicon-minus');
        }
        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);
        
        var searchterm = "<?php if(isset($searchterm)) { echo "Yes"; } else { echo "No"; } ?>";
        if(searchterm == "Yes"){
            $(".links").trigger('click');
        }
    });
</script>
@endsection


