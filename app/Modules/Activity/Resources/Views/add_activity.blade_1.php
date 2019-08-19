@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h3> Add Activity</h3>
    </div>
    <div class="col-lg-2 pull-right">
        <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary pull-right">Back</a>
    </div>
</div>
<div class="wrapper wrapper-content add-wrapper">    
    {{ Form::open(['route' => 'addactivity', 'method' => 'POST', 'id'=>'activity-form','enctype' => 'multipart/form-data']) }}

    <input type="hidden" name="actionname" value="{{Route::current()->getName()}}" id="actionname"/>
    <input type="hidden" name="id" value="empty" id="activity-id"/>
    <!--Start Basic Details--> 
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title activity-ibox-titles collapse-link">
                    <h5>Basic Details</h5>
                    <div class="ibox-tools">
                        <a>
                            <i class="fa fa-chevron-up"></i>
                        </a>                        
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Title: <span class="input-required">*</span></label>
                                    {{ Form::text('title','', ['class' => 'form-control', 'id' => 'title']) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Subtitle: </label>
                                    {{ Form::text('subtitle','', ['class' => 'form-control', 'id' => 'subtitle']) }}
                                </div>
                            </div>                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Category: <span class="input-required">*</span></label>
                                    {{ Form::select('category',[null => '--Select Category--'] + $categories, '',['class' => 'form-control', 'id' => 'activty-category']) }}
                                </div>  
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Subcategory: <span class="input-required">*</span></label>
                                    {{ Form::select('subcategory',[null => '--Select Subcategory--'],'',['class' => 'form-control', 'id' => 'activty-subcategory']) }}
                                </div>  
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">City: <span class="input-required">*</span></label>
                                    {{ Form::select('city',[null => '--Select City--'] + $cities,'',['class' => 'form-control', 'id' => 'city']) }}
                                </div>  
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Image: <span class="input-required">*</span></label>
                                    {{ Form::file('image','', ['class' => 'form-control', 'id' => 'image']) }}
                                </div>
                            </div>                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                    {{ Form::text('actual_price', '',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'actual_price']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Display Price: </label>
                                    {{ Form::text('display_price','',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'display_price']) }}
                                </div>  
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <label class="control-label">Description:</label>
                                {{ Form::textarea('description','',['id' => 'description']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
    <!--End Basic Details--> 
    <!--Start General Policies--> 
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title activity-ibox-titles collapse-link">
                    <h5>Terms and Policies</h5>
                    <div class="ibox-tools">
                        <a>
                            <i class="fa fa-chevron-up"></i>
                        </a>                        
                    </div>
                </div>
                <div class="ibox-content collapse">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-10" id="terms-and-policy">
                                @if(count($generalpolicies))
                                @foreach($generalpolicies as $policykey => $policyvalue)
                                <div class="col-md-4">
                                    <div class="checkbox cnone">
                                        <label>
                                            <input type="checkbox" name="general_policy[]" value="{{$policykey}}" /> {{$policyvalue}}
                                        </label>
                                    </div>                                  
                                </div>
                                @endforeach
                                @endif
                            </div>
                            <div class="col-md-2">
                                <a class="btn btn-success btn-circle pull-right" data-toggle='modal' data-target='#activity-policy-modal' type="button" title="Add More Policy"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
    <!--End General Policies-->
    <!--Start Package Options--> 
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
                    <div class="row package-row">
                        <div class="col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="control-label">Package Title: <span class="input-required">*</span></label>
                                    {{ Form::text('package_title[]','', ['class' => 'form-control', 'id' => 'package_title','rows' => 6]) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                    {{ Form::number('package_actual_price[]', '',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'package_actual_price']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Display Price: </label>
                                    {{ Form::number('package_display_price[]','',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'package_display_price']) }}
                                </div>  
                            </div>
                            <div class="col-md-3">
                                <a class="btn btn-success btn-circle pull-right add-more-package" type="button" title="Add More Package"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Package Description: </label>
                                    {{ Form::textarea('package_description[]','', ['class' => 'form-control', 'id' => 'package_description','rows' => 6]) }}
                                </div>
                            </div>                                
                        </div>
                        <div class="col-md-12 booking-details">
                            <div class="col-md-1">
                                <a class="btn btn-primary btn-circle pull-right add-booking" type="button" title="Add More Booking"><i class="fa fa-plus"></i></a>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="control-label">Booking Title: <span class="input-required">*</span></label>
                                    {{ Form::text('booking_title[]','', ['class' => 'form-control', 'id' => 'package_title','rows' => 6,'placeholder' => 'e.g Adult, Child, Senior']) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                    {{ Form::number('booking_actual_price[]', '',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'package_actual_price']) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Display Price: </label>
                                    {{ Form::number('booking_display_price[]','',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'package_display_price']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Is Time Selection? </label>
                                    <input type="checkbox" name="is_time[]">
                                </div>  
                            </div>
                        </div>
                        <!--HIDDEN BOOKING DETAIL-->
                        <div class="col-md-12 hide booking-details" id="booking_detail_clone">
                            <div class="col-md-1">
                                <a class="btn btn-danger btn-circle pull-right remove-booking" type="button" title="Remove Booking"><i class="fa fa-minus"></i></a>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="control-label">Booking Title: <span class="input-required">*</span></label>
                                    {{ Form::text('booking_title[]','', ['class' => 'form-control', 'id' => 'package_title','rows' => 6,'placeholder' => 'e.g Adult, Child, Senior']) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                    {{ Form::number('booking_actual_price[]', '',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'package_actual_price']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Display Price: </label>
                                    {{ Form::number('booking_display_price[]','',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'package_display_price']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Is Time Selection? </label>
                                    <input type="checkbox" name="is_time[]">
                                </div>  
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
                                    {{ Form::text('package_title[]','', ['class' => 'form-control', 'id' => 'package_title','rows' => 6]) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                    {{ Form::number('package_actual_price[]', '',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'package_actual_price']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Display Price: </label>
                                    {{ Form::number('package_display_price[]','',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'package_display_price']) }}
                                </div>  
                            </div>
                            <div class="col-md-3">
                                <a class="btn btn-danger btn-circle pull-right remove-package" type="button" title="Remove Package"><i class="fa fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Package Description: </label>
                                    {{ Form::textarea('package_description[]','', ['class' => 'form-control', 'id' => 'package_description','rows' => 6]) }}
                                </div>
                            </div>                                
                        </div>
                        <div class="col-md-12 booking-details">
                            <div class="col-md-1">
                                <a class="btn btn-primary btn-circle pull-right add-booking" type="button" title="Add More Booking"><i class="fa fa-plus"></i></a>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="control-label">Booking Title: <span class="input-required">*</span></label>
                                    {{ Form::text('booking_title[]','', ['class' => 'form-control', 'id' => 'package_title','rows' => 6,'placeholder' => 'e.g Adult, Child, Senior']) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                    {{ Form::number('booking_actual_price[]', '',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'package_actual_price']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Display Price: </label>
                                    {{ Form::number('booking_display_price[]','',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'package_display_price']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Is Time Selection? </label>
                                    <input type="checkbox" name="is_time[]">
                                </div>  
                            </div>
                        </div>
                        <!--HIDDEN BOOKING DETAIL-->
                        <div class="col-md-12 hide booking-details" id="booking_detail_clone">
                            <div class="col-md-1">
                                <a class="btn btn-danger btn-circle pull-right remove-booking" type="button" title="Remove Booking"><i class="fa fa-minus"></i></a>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="control-label">Booking Title: <span class="input-required">*</span></label>
                                    {{ Form::text('booking_title[]','', ['class' => 'form-control', 'id' => 'package_title','rows' => 6,'placeholder' => 'e.g Adult, Child, Senior']) }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Actual Price: <span class="input-required">*</span></label>
                                    {{ Form::number('booking_actual_price[]', '',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'package_actual_price']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Display Price: </label>
                                    {{ Form::number('booking_display_price[]','',['min' => 1,'oninput' => "validity.valid||(value='');",'onKeyPress' => "if(this.value.length==10) return false;",'class' => 'form-control', 'id' => 'package_display_price']) }}
                                </div>  
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Is Time Selection? </label>
                                    <input type="checkbox" name="is_time[]">
                                </div>  
                            </div>
                        </div>
                        <!-- END HIDDEN BOOKING DETAIL-->
                    </div>
                </div>
            </div>
        </div>        
    </div>
    <!--End Package Options-->
    <!--Start What To Expect--> 
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title activity-ibox-titles collapse-link">
                    <h5>What To Expect</h5>
                    <div class="ibox-tools">
                        <div class="confirmation-check">
                            <input type="checkbox" name="is_what_to_expect" id="is_what_to_expect" class="i-checks" >
                        </div>
                        <a id="what_to_expect_collapse_link">
                            <i class="fa fa-chevron-up"></i>
                        </a>                        
                    </div>
                </div>
                <div class="ibox-content collapse" id="what_to_expect_content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Enter Expectation Details: </label>
                                    {{ Form::textarea('what_to_expect_description','', ['class' => 'form-control', 'id' => 'what_to_expect_description']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
    <!--End What To Expect--> 
    <!--Start Activity Information--> 
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title activity-ibox-titles collapse-link">
                    <h5>Activity Information</h5>
                    <div class="ibox-tools">
                        <div class="confirmation-check">
                            <input type="checkbox" name="is_activity_information" id="is_activity_information" class="i-checks" >
                        </div>
                        <a>
                            <i class="fa fa-chevron-up" id="activity_information_collapse_link"></i>
                        </a>                        
                    </div>
                </div>
                <div class="ibox-content collapse" id="activity_information_content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Enter Activity Information: </label>
                                    {{ Form::textarea('activity_information_description','', ['class' => 'form-control', 'id' => 'activity_information_description','rows' => 4]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
    <!--End Activity Information--> 
    <!--Start How To Use--> 
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title activity-ibox-titles collapse-link">
                    <h5>How to Use</h5>
                    <div class="ibox-tools">
                        <div class="confirmation-check">
                            <input type="checkbox" name="is_how_to_use" id="is_how_to_use" class="i-checks" >
                        </div>
                        <a>
                            <i class="fa fa-chevron-up" id="is_how_to_use_collapse_link"></i>
                        </a>                        
                    </div>
                </div>
                <div class="ibox-content collapse" id="how_to_use_content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Enter Details: </label>
                                    {{ Form::textarea('how_to_use_description','', ['class' => 'form-control', 'id' => 'how_to_use_description','rows' => 4]) }}                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
    <!--End How To Use--> 
    <!--Start Cancellation Policy--> 
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title activity-ibox-titles collapse-link">
                    <h5>Cancellation Policy</h5>
                    <div class="ibox-tools">
                        <div class="confirmation-check">
                            <input type="checkbox" name="is_cancellation_policy" id="is_cancellation_policy" class="i-checks" >
                        </div>
                        <a>
                            <i class="fa fa-chevron-up" id="cancellation_policy_collapse_link"></i>
                        </a>                        
                    </div>
                </div>
                <div class="ibox-content collapse" id="cancellation_policy_content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Enter Policy Details: </label>
                                    {{ Form::textarea('cancellation_policy_description','', ['class' => 'form-control', 'id' => 'cancellation_policy_description','rows' => 4]) }}
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>        
    </div>
    <!--End Cancellation Policy--> 
    <!--Start FAQs--> 
    <!-- <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title activity-ibox-titles collapse-link">
                    <h5>FAQs</h5>
                    <div class="ibox-tools">                        
                        <a>
                            <i class="fa fa-chevron-up"></i>
                        </a>                        
                    </div>
                </div>
                <div class="ibox-content collapse">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Question:</label>
                                    {{ Form::text('question[]','', ['class' => 'form-control', 'id' => 'question']) }}
                                </div>
                            </div>                                                                                    
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Answer:</label>
                                    {{ Form::textarea('answer[]','', ['class' => 'form-control', 'id' => 'answer','rows' => 3]) }}
                                </div>  
                            </div>
                            <div class="col-md-1">
                                <a class="btn btn-success btn-circle pull-right addFaq" type="button" title="Add More Question"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group hide faq-template" id="faq-template">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Question:</label>
                                    {{ Form::text('question[]','', ['class' => 'form-control', 'id' => 'question']) }}
                                </div>
                            </div>                                                                                    
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Answer:</label>
                                    {{ Form::textarea('answer[]','', ['class' => 'form-control', 'id' => 'answer','rows' => 3]) }}
                                </div>  
                            </div>
                            <div class="col-md-1">
                                <a class="btn btn-danger btn-circle pull-right removeFaq" type="button" title="Remove Question"><i class="fa fa-minus"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>        
    </div> -->
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
    <!--End FAQs--> 
    {{ Form::close() }}
</div>
<!--ADD POLICY MODAL-->
<div class="modal inmodal" id="activity-policy-modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            {!! Form::open(['url'=>'activity/addpolicy','id'=>'add-activity-policy-form','enctype' => 'multipart/form-data'])!!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add Policy</h4>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="control-label">Icon: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-9">
                        <input type="hidden" name="policy_id" value="empty">
                        {{ Form::file('imagefile',['class' => 'form-control-file','id' => 'imagefile']) }}                      
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="control-label">Policy: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-9">
                        {{ Form::text('name','',['class' => 'form-control','placeholder' => 'Enter policy name']) }}                      
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
@endsection
@section('javascript')
<script>
    $(document).ready(function () {
        $('#description').summernote();
        $("#package_description").summernote();
        $("#what_to_expect_description").summernote();
        $("#activity_information_description").summernote();
        $("#how_to_use_description").summernote();
        $("#cancellation_policy_description").summernote();
    });
</script>
@endsection
