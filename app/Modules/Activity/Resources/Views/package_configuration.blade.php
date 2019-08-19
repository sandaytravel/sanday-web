@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h3>Package Configuration For Activity "{{$activity->title}}"</h3>
    </div>
    <div class="col-lg-2 pull-right">
        <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary pull-right">Back</a>
    </div>
</div>
<div class="wrapper wrapper-content add-wrapper"> 
{{ Form::open(['route' => ['set_package_configuration',$activity->id], 'method' => 'POST', 'id'=>'pacakge-config']) }}

<input type="hidden" name="actionname" value="{{Route::current()->getName()}}" id="actionname"/>
<input type="hidden" name="id" value="{{$activity->id}}" id="activity-id"/>
<?php $randnum = rand(1, 100); ?>

        <!--Start Package Options--> 
        @if(count($packageList))
        <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title activity-ibox-titles collapse-link">
                            <h5>Package options</h5>
                            <div class="ibox-tools">
                                <a>
                                    <i class="fa fa-chevron-up"></i>
                                </a>                        
                            </div>
                        </div>
                    <div class="ibox-content" id="package_options_content">    
            @foreach($packageList as $key => $value)
            <?php $randnum = rand(1, 100); ?>
                       
                           
                            <div class="row package-row" >
                            <input type="hidden" name="data[{{$randnum}}][package_id]" value="{{$value->id}}" class="package_id">
                                <div class="col-md-12">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label">Package Title: <span class="input-required">*</span></label>
                                            {{ Form::text("data[$randnum][package_title]",$value->package_title, ['class' => 'form-control package_title', 'id' => 'package_title','rows' => 6]) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                            {{ Form::text("data[$randnum][package_actual_price]",$value->actual_price,['class' => 'form-control package_actual_price', 'id' => 'package_actual_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Display Price: </label>
                                            {{ Form::text("data[$randnum][package_display_price]",$value->display_price,['class' => 'form-control package_display_price', 'id' => 'package_display_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Validity: </label>    
                                        <div class="input-group">
                                            {{ Form::text("data[$randnum][package_validity]",$value->validity, ['class' => 'form-control package_validity', 'id' => 'package_validity','autocomplete' => 'false']) }}
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                    @if($key == 0)
                                        <a class="btn btn-success btn-circle pull-right add-more-package" type="button" title="Add More Package"><i class="fa fa-plus"></i></a>
                                    @else
                                    <a class="btn btn-danger btn-circle pull-right remove-package" type="button" data-id="{{$value->id}}" title="Remove Package"><i class="fa fa-minus"></i></a>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Package Description: </label>
                                            {{ Form::textarea("data[$randnum][package_description]",$value->description, ['class' => 'form-control package_description', 'id' => 'package_description','rows' => 6]) }}
                                        </div>
                                    </div>                                
                                </div>
                                <!---------- booking Detail------------->
                                @if(count($value->packagequantity))
                                    @foreach($value->packagequantity as $quantityKey => $quantityValue)
                                    <?php 
                                    print_r($quantityValue->toArray);
                                    ?>
                                        <div class="col-md-12 booking-details">
                                        {{ Form::hidden("data[$randnum][child][booking_id][]",$quantityValue->id, ['id' => 'booking_id']) }}
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Booking Title: <span class="input-required">*</span></label>
                                                    {{ Form::text("data[$randnum][child][booking_title][]",$quantityValue->name, ['class' => 'form-control booking_title', 'id' => 'booking_title','rows' => 6,'placeholder' => 'e.g Adult, Child, Senior']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                                    {{ Form::text("data[$randnum][child][booking_actual_price][]",$quantityValue->actual_price,['class' => 'form-control booking_actual_price', 'id' => 'booking_actual_price']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Display Price: </label>
                                                    {{ Form::text("data[$randnum][child][booking_display_price][]",$quantityValue->display_price,['class' => 'form-control booking_display_price', 'id' => 'booking_display_price']) }}
                                                </div>  
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Minimum Quantity: <span class="input-required">*</span></label>
                                                    {{ Form::text("data[$randnum][child][minimum_quantity][]",$quantityValue->minimum_quantity, ['class' => 'form-control minimum_quantity']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Maximum Quantity: </label>
                                                    {{ Form::text("data[$randnum][child][maximum_quantity][]",$quantityValue->maximum_quantity, ['class' => 'form-control maximum_quantity']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                @if($quantityKey == 0)
                                                    <a class="btn btn-primary btn-circle pull-right add-booking" type="button" title="Add More Booking"><i class="fa fa-plus"></i></a>
                                                @else
                                                    <a class="btn btn-danger btn-circle pull-right remove-booking" type="button" title="Remove Booking" data-id='{{$quantityValue->id}}'><i class="fa fa-minus"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                        <!----------End booking Detail------------->
                                    @endforeach
                                @endif
                                <!--HIDDEN BOOKING DETAIL-->
                                <div class="col-md-12 hide booking-details" id="booking_detail_clone">
                                
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Booking Title: <span class="input-required">*</span></label>
                                            {{ Form::text("data[$randnum][child][booking_title][]",'', ['class' => 'form-control booking_title', 'id' => 'booking_title','rows' => 6,'placeholder' => 'e.g Adult, Child, Senior']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                            {{ Form::text("data[$randnum][child][booking_actual_price][]", '',['class' => 'form-control booking_actual_price', 'id' => 'booking_actual_price']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Display Price: </label>
                                            {{ Form::text("data[$randnum][child][booking_display_price][]",'',['class' => 'form-control booking_display_price', 'id' => 'booking_display_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Minimum Quantity: <span class="input-required">*</span></label>
                                            {{ Form::text("data[$randnum][child][minimum_quantity][]",'', ['class' => 'form-control minimum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Maximum Quantity: </label>
                                            {{ Form::text("data[$randnum][child][maximum_quantity][]",'', ['class' => 'form-control maximum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a class="btn btn-danger btn-circle pull-right remove-booking" type="button" title="Remove Package"><i class="fa fa-minus"></i></a>
                                    </div>
                                </div>
                                <!-- END HIDDEN BOOKING DETAIL-->
                            </div>
                            <!--HIDDEN PACKAGE DETAIL-->
                            <div class="row package-row hide" id="package_option_clone">
                                <div class="col-md-12">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label">Package Title: <span class="input-required">*</span></label>
                                            {{ Form::text('','', ['class' => 'form-control package_title', 'id' => 'package_title']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                            {{ Form::text('', '',['class' => 'form-control package_actual_price', 'id' => 'package_actual_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Display Price: </label>
                                            {{ Form::text('','',['class' => 'form-control package_display_price', 'id' => 'package_display_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Validity: </label>    
                                        <div class="input-group">
                                            {{ Form::text('','', ['class' => 'form-control package_validity', 'id' => 'package_validity','autocomplete' => 'false']) }}
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a class="btn btn-danger btn-circle pull-right remove-package" type="button" title="Remove Package"><i class="fa fa-minus"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Package Description: </label>
                                            {{ Form::textarea('','', ['class' => 'form-control package_description_clone', 'id' => 'package_description','rows' => 6]) }}
                                        </div>
                                    </div>                                
                                </div>
                                <div class="col-md-12 booking-details">
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Booking Title: <span class="input-required">*</span></label>
                                            {{ Form::text('','', ['class' => 'form-control booking_title', 'id' => 'booking_title','rows' => 6,'placeholder' => 'e.g Adult, Child, Senior']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                            {{ Form::text('', '',['class' => 'form-control booking_actual_price', 'id' => 'booking_actual_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Display Price: </label>
                                            {{ Form::text('','',['class' => 'form-control booking_display_price', 'id' => 'booking_display_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Minimum Quantity: <span class="input-required">*</span></label>
                                            {{ Form::text("",'', ['class' => 'form-control minimum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Maximum Quantity: </label>
                                            {{ Form::text("",'', ['class' => 'form-control maximum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a class="btn btn-primary btn-circle pull-right add-booking" type="button" title="Add More Booking"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <!--HIDDEN BOOKING DETAIL-->
                                <div class="col-md-12 hide booking-details" id="booking_detail_clone">
                                
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Booking Title: <span class="input-required">*</span></label>
                                            {{ Form::text('','', ['class' => 'form-control booking_title', 'id' => 'booking_title','rows' => 6,'placeholder' => 'e.g Adult, Child, Senior']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                            {{ Form::text('', '',['class' => 'form-control booking_actual_price', 'id' => 'booking_actual_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Display Price: </label>
                                            {{ Form::text('','',['class' => 'form-control booking_display_price', 'id' => 'booking_display_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Minimum Quantity: <span class="input-required">*</span></label>
                                            {{ Form::text("",'', ['class' => 'form-control minimum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Maximum Quantity: </label>
                                            {{ Form::text("",'', ['class' => 'form-control maximum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a class="btn btn-danger btn-circle pull-right remove-booking" type="button" title="Remove Booking"><i class="fa fa-minus"></i></a>
                                    </div>
                                </div>
                                <!-- END HIDDEN BOOKING DETAIL-->
                            </div>
                       
                   
            <!--End Basic Details--> 
            @endforeach
            </div>
            </div>
                </div>        
            </div>
        @else
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title activity-ibox-titles collapse-link">
                            <h5>Package Options</h5>
                            <div class="ibox-tools">
                                <a id="package_options_collapse_link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>                        
                            </div>
                        </div>
                        <div class="ibox-content" id="package_options_content">
                            <div class="row package-row" >
                                <div class="col-md-12">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label">Package Title: <span class="input-required">*</span></label>
                                            {{ Form::text("data[$randnum][package_title]",'', ['class' => 'form-control package_title', 'id' => 'package_title','rows' => 6]) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                            {{ Form::text("data[$randnum][package_actual_price]", '',['class' => 'form-control package_actual_price', 'id' => 'package_actual_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Display Price: </label>
                                            {{ Form::text("data[$randnum][package_display_price]",'',['class' => 'form-control package_display_price', 'id' => 'package_display_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Validity: </label>    
                                        <div class="input-group">
                                            {{ Form::text("data[$randnum][package_validity]",'', ['class' => 'form-control package_validity', 'id' => 'package_validity','autocomplete' => 'false']) }}
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a class="btn btn-success btn-circle pull-right add-more-package" type="button" title="Add More Package"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Package Description: </label>
                                            {{ Form::textarea("data[$randnum][package_description]",'', ['class' => 'form-control package_description', 'id' => 'package_description','rows' => 6]) }}
                                        </div>
                                    </div>                                
                                </div>
                                <!---------- booking Detail------------->
                                <div class="col-md-12 booking-details">
                                
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Booking Title: <span class="input-required">*</span></label>
                                            {{ Form::text("data[$randnum][child][booking_title][]",'', ['class' => 'form-control booking_title', 'id' => 'booking_title','rows' => 6,'placeholder' => 'e.g Adult, Child, Senior']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                            {{ Form::text("data[$randnum][child][booking_actual_price][]", '',['class' => 'form-control booking_actual_price', 'id' => 'booking_actual_price']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Display Price: </label>
                                            {{ Form::text("data[$randnum][child][booking_display_price][]",'',['class' => 'form-control booking_display_price', 'id' => 'booking_display_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Minimum Quantity: <span class="input-required">*</span></label>
                                            {{ Form::text("data[$randnum][child][minimum_quantity][]",'', ['class' => 'form-control minimum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Maximum Quantity: </label>
                                            {{ Form::text("data[$randnum][child][maximum_quantity][]",'', ['class' => 'form-control maximum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a class="btn btn-primary btn-circle pull-right add-booking" type="button" title="Add More Booking"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <!----------End booking Detail------------->
                                <!--HIDDEN BOOKING DETAIL-->
                                <div class="col-md-12 hide booking-details" id="booking_detail_clone">
                                    
                                <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Booking Title: <span class="input-required">*</span></label>
                                            {{ Form::text("data[$randnum][child][booking_title][]",'', ['class' => 'form-control booking_title', 'id' => 'booking_title','rows' => 6,'placeholder' => 'e.g Adult, Child, Senior']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                            {{ Form::text("data[$randnum][child][booking_actual_price][]", '',['class' => 'form-control booking_actual_price', 'id' => 'booking_actual_price']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Display Price: </label>
                                            {{ Form::text("data[$randnum][child][booking_display_price][]",'',['class' => 'form-control booking_display_price', 'id' => 'booking_display_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Minimum Quantity: <span class="input-required">*</span></label>
                                            {{ Form::text("data[$randnum][child][minimum_quantity][]",'', ['class' => 'form-control minimum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Maximum Quantity: </label>
                                            {{ Form::text("data[$randnum][child][maximum_quantity][]",'', ['class' => 'form-control maximum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a class="btn btn-danger btn-circle pull-right remove-booking" type="button" title="Remove Package"><i class="fa fa-minus"></i></a>
                                    </div>
                                </div>
                                <!-- END HIDDEN BOOKING DETAIL-->
                            </div>
                            <!--HIDDEN PACKAGE DETAIL-->
                            <div class="row package-row hide" id="package_option_clone">
                                <div class="col-md-12">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label">Package Title: <span class="input-required">*</span></label>
                                            {{ Form::text('','', ['class' => 'form-control package_title', 'id' => 'package_title']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                            {{ Form::text('', '',['class' => 'form-control package_actual_price', 'id' => 'package_actual_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Display Price: </label>
                                            {{ Form::text('','',['class' => 'form-control package_display_price', 'id' => 'package_display_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">Validity: </label>    
                                        <div class="input-group">
                                            {{ Form::text('','', ['class' => 'form-control package_validity', 'id' => 'package_validity','autocomplete' => 'false']) }}
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a class="btn btn-danger btn-circle pull-right remove-package" type="button" title="Remove Package"><i class="fa fa-minus"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Package Description: </label>
                                            {{ Form::textarea('','', ['class' => 'form-control package_description', 'id' => 'package_description','rows' => 6]) }}
                                        </div>
                                    </div>                                
                                </div>
                                <div class="col-md-12 booking-details">
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Booking Title: <span class="input-required">*</span></label>
                                            {{ Form::text('','', ['class' => 'form-control booking_title', 'id' => 'booking_title','rows' => 6,'placeholder' => 'e.g Adult, Child, Senior']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                            {{ Form::text('', '',['class' => 'form-control booking_actual_price', 'id' => 'booking_actual_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Display Price: </label>
                                            {{ Form::text('','',['class' => 'form-control booking_display_price', 'id' => 'booking_display_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Minimum Quantity: <span class="input-required">*</span></label>
                                            {{ Form::text("",'', ['class' => 'form-control minimum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Maximum Quantity: </label>
                                            {{ Form::text("",'', ['class' => 'form-control maximum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a class="btn btn-primary btn-circle pull-right add-booking" type="button" title="Add More Booking"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <!--HIDDEN BOOKING DETAIL-->
                                <div class="col-md-12 hide booking-details" id="booking_detail_clone">
                                
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Booking Title: <span class="input-required">*</span></label>
                                            {{ Form::text('','', ['class' => 'form-control booking_title', 'id' => 'booking_title','rows' => 6,'placeholder' => 'e.g Adult, Child, Senior']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                            {{ Form::text('', '',['class' => 'form-control booking_actual_price', 'id' => 'booking_actual_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Display Price: </label>
                                            {{ Form::text('','',['class' => 'form-control booking_display_price', 'id' => 'booking_display_price']) }}
                                        </div>  
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Minimum Quantity: <span class="input-required">*</span></label>
                                            {{ Form::text("",'', ['class' => 'form-control minimum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Maximum Quantity: </label>
                                            {{ Form::text("",'', ['class' => 'form-control maximum_quantity']) }}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a class="btn btn-danger btn-circle pull-right remove-booking" type="button" title="Remove Booking"><i class="fa fa-minus"></i></a>
                                    </div>
                                </div>
                                <!-- END HIDDEN BOOKING DETAIL-->
                            </div>
                        </div>
                    </div>
                </div>        
            </div>
        @endif
        <!--End Package Options-->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-sm-4">
                        <a href="{{route('activity')}}" class="btn btn-white">Cancel</a>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>
@endsection
@section('javascript')
<script>
    $(document).ready(function () {
        $(".package_description").summernote({
            callbacks: {
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    // Firefox fix
                    setTimeout(function () {
                        document.execCommand('insertText', false, bufferText);
                    }, 10);
                }
            }
        });
    });
</script>
@endsection
