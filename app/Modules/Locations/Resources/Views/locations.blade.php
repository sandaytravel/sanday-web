@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <div class="col-lg-4">
            <h3> Locations</h3>
        </div>
        <div class="col-md-4 text-right pull-right">
            <a href="javascript:void(0);" data-toggle="modal" data-target="#add-continent" type="button" class="btn btn-outline btn-primary">Add Continent</a>
            <a href="{{route('addcity')}}" type="button" class="btn btn-outline btn-primary">Add City</a>
            <!-- <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary">Back</a> -->
        </div>
        <div class="col-sm-3 pull-right">
            {!! Form::open(['url'=>'location/search','id'=>'search'])!!}
            {{ csrf_field() }}
            <div class="input-group"><input type="text" name="searchterm" value="{{ $searchterm or ''}}" placeholder="Search" class="input-sm form-control "> <span class="input-group-btn">
                    <button type="submit" class="btn btn-sm btn-primary"> Go!</button> 
                    <a href="{{route('locations')}}" class="btn btn-default btn-circle" title="Reset"><i class="fa fa-refresh"></i></a></span>
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
                    <?php               
                       
                        // foreach ($locationarray as $key => $value) {
                        //     # code...
                        //     //echo $value['continetname'];
                        //     foreach ($value['countryname'] as $countrykey => $countryvalue) {
                        //         foreach ($countryvalue['city'] as $citykey => $cityvalue) {
                        //          if ($cityvalue['cityname'] = $searchterm){
                        //             // echo 'hi';
                        //          }  
                        //         }
                        //     }
                        // }
                        ?>
                        @if(count($locations)) 
                        @foreach($locations as $key => $value)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-md-10">
                                            <h4 class="panel-title">                                    
                                                <a role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                                                    <h4 class="panel-title">
                                                        <i class="more-less glyphicon glyphicon-plus"></i>
                                                        {{$value->name}}
                                                    </h4>
                                                </a>
                                            </h4>
                                        </div>
                                        <div class="col-md-2">
                                            <a class="btn btn-success btn-circle pull-right add-country" data-continent='{{$value->name}}' data-id='{{$value->id}}' type="button" title="Add Country"><i class="fa fa-plus"></i></a>
                                            @if(in_array($value->id, $continentarray))
                                                <a href="{{route('deletecontinent',[$value->id])}}" class="btn btn-danger btn-circle pull-right non-deleteable delete-continent" data-id='continent' type="button" title="Delete Continent"><i class="fa fa-remove"></i></a>
                                            @else
                                                <a href="{{route('deletecontinent',[$value->id])}}" class="btn btn-danger btn-circle pull-right delete-row delete-continent" data-id='continent' type="button" title="Delete Continent"><i class="fa fa-remove"></i></a>
                                            @endif
                                            <a class="btn btn-primary btn-circle pull-right edit-continent" data-continent='{{$value->name}}' data-id='{{$value->id}}' type="button" title="Edit Continent"><i class="fa fa-pencil"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse{{$key}}" class="panel-collapse collapse">
                                <div class="panel-body">
                                    @if(count($value->contries))
                                    @foreach($value->contries as $countrykey => $countryvalue)
                                    <div class="location-panel-body">
                                        <div class="panel-group" id="accordion21">
                                            <div class="panel">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="col-md-10">
                                                            <a role="button" data-toggle="collapse" data-parent="#accordion{{$countrykey}}{{$key}}" href="#collapse{{$countrykey}}{{$key}}" aria-expanded="true" aria-controls="collapse{{$countrykey}}{{$key}}" class="serach-active">
                                                                <h4 class="panel-title">
                                                                    <!--<i class="more-less glyphicon glyphicon-plus"></i>-->
                                                                    {{$countryvalue->country}}
                                                                </h4>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-2">                                            
                                                            <a class="btn btn-primary btn-circle edit-country" data-status='{{$countryvalue->status}}' data-continentid='{{$value->id}}' data-continent='{{$value->name}}' data-countryid='{{$countryvalue->id}}' data-countryname='{{$countryvalue->country}}' type="button" title="Edit Country"><i class="fa fa-pencil"></i></a>
                                                            @if(in_array($countryvalue->id, $countryarray))
                                                                <a href="{{route('deletecountry',[$countryvalue->id])}}" class="btn btn-danger btn-circle non-deleteable" data-id='country' type="button" title="Delete Country"><i class="fa 
                                                                fa-remove"></i></a>
                                                            @else
                                                                <a href="{{route('deletecountry',[$countryvalue->id])}}" class="btn btn-danger btn-circle delete-row" data-id='country' type="button" title="Delete Country"><i class="fa 
                                                                fa-remove"></i></a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row country-panel-heading">
                                                    <div class="col-lg-12">
                                                        <div class="col-md-10">
                                                            <div id="collapse{{$countrykey}}{{$key}}" class="panel-collapse collapse">

                                                                @if(count($countryvalue->city))
                                                                @foreach($countryvalue->city as $citykey => $cityvalue)
                                                                <div class="panel-body city-panel-body">
                                                                    <div class="row">
                                                                        <div class="col-lg-12">
                                                                            <div class="col-md-10">
                                                                                <div class="panel-group">
                                                                                    <div class="panel">
                                                                                        <span>{{$cityvalue->city}}</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                @if(in_array($cityvalue->id, $cityarray))
                                                                                    <a href="javascript:void(0)" class="btn btn-danger btn-circle non-deleteable pull-right" data-id='city' type="button" title="Delete City"><i class="fa fa-remove"></i></a>
                                                                                @else
                                                                                    <a href="{{route('deletecity',[$cityvalue->id])}}" class="btn btn-danger btn-circle delete-row pull-right" data-id='city' type="button" title="Delete City"><i class="fa fa-remove"></i></a>
                                                                                @endif
                                                                                <a href="{{route('editcity',[$cityvalue->id])}}" class="btn btn-primary btn-circle pull-right edit-city" type="button" title="Edit City"><i class="fa fa-pencil"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                                @else
                                                                <span class="no-records">No location found</span>
                                                                @endif

                                                            </div>
                                                        </div>     
                                                    </div>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <span class="no-records">No location found</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <span class="no-records">No location found</span>
                        @endif
                    </div>
                    <!-- panel-group -->
                </div>
            </div>
        </div>

    </div>
</div>
<!--ADD CONTINENT MODAL-->
<div class="modal inmodal" id="add-continent" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            {!! Form::open(['url'=>'countitnent/add','id'=>'add-countitnent-form'])!!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Add Continent</h4>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Enter Continent: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" name="actionname" id="actionname" value="add" />
                        <input type="hidden" name="continent_id" id="continent_id" value="empty" />
                        {{ Form::text('continent[]','',['class' => 'form-control','placeholder' => 'Enter continent name']) }}                      
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary addButton" type="button"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="row form-group hide" id="optionTemplate">
                    <div class="col-md-4">
                        <label class="control-label">Enter Continent: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" name="actionname" id="actionname" value="add" />
                        <input type="hidden" name="continent_id" id="continent_id" value="empty" />
                        {{ Form::text('continent[]','',['class' => 'form-control continent-name','placeholder' => 'Enter continent name']) }}                      
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
<!--UPDATE CONTINENT MODAL-->
<div class="modal inmodal" id="update-continent" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            {!! Form::open(['url'=>'countitnent/update','id'=>'update-countitnent-form'])!!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Update Continent</h4>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Enter Continent: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" name="update_continent_id" id="update_continent_id" value="" />
                        {{ Form::text('continent','',['class' => 'form-control','id' => 'continent','placeholder' => 'Enter continent name']) }}                      
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
<!--ADD COUNTRY MODAL-->
<div class="modal inmodal" id="add-country-modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            {!! Form::open(['url'=>'country/add','id'=>'add-country-form'])!!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title add-country-title"></h4>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Enter Country: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" name="actionname" id="actionname" value="add" />
                        <input type="hidden" name="country_continent_id" id="country_continent_id" value="empty" />
                        <input type="hidden" name="country_id" id="country_id" value="empty" />
                        {{ Form::text('country[]','',['class' => 'form-control country-name','placeholder' => 'Enter country name']) }}                      
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary addButton" type="button"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="row form-group hide" id="countryTemplate">
                    <div class="col-md-4">
                        <label class="control-label">Enter Country: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        {{ Form::text('country[]','',['class' => 'form-control country-name','placeholder' => 'Enter country name']) }}                      
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
<!--UPDATE COUNTRY MODAL-->
<div class="modal inmodal" id="update-country-modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            {!! Form::open(['url'=>'country/update','id'=>'update-country-form'])!!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title update-country-title"></h4>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Update Country: <span class="input-required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" name="country_continent_id" id="country_continent_id" value="" />
                        <input type="hidden" name="country_id" id="country_id" value="" />
                        {{ Form::text('country','',['class' => 'form-control country-name','id' => 'country-name','placeholder' => 'Enter country name']) }}                      
                    </div>                    
                </div>                               
                <!--                <div class="row form-group">
                                    <div class="col-md-4">
                                        <label class="control-label">Status:</label>
                                    </div>
                                    <div class="col-md-6">
                                        {{ Form::select('status',['Active' => 'Active','Inactive' => 'Inactive'],'',['class' => 'form-control','id' => 'status']) }}                      
                                    </div>                    
                                </div>-->
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
        function toggleIcon(e) {
            $(e.target)
                    .prev('.panel-heading')
                    .find(".more-less")
                    .toggleClass('glyphicon-plus glyphicon-minus');
        }
        $('.panel-group').on('hidden.bs.collapse', toggleIcon);
        $('.panel-group').on('shown.bs.collapse', toggleIcon);

        $( ".panel-heading .panel-title a" ).trigger( "click" );
        var locationsearch = $('input[name=searchterm]').val();
        
        var locationarray = <?php echo $locationarray ?>;
        if(locationarray.length > 0){
            $.each( locationarray, function( key, value ) {
                 //console.log("val => "+key);
                
                    $.each( value, function( ky, val ) {
                    //console.log('ky => '+val);
                        if(ky == 'countryname'){
                            // console.log(val);
                            $.each( val, function( countrykey, countryval ) {
                                //console.log(countrykey);
                                $.each( countryval, function( countrysubkey, countrysubval ) {
                                   // console.log(countrysubval);
                                    if(countrysubkey == 'city'){
                                        $.each( countrysubval, function( citykey, cityval ) {
                                            //console.log(cityval);
                                            $.each( cityval, function( citysubkey, citysubval ) {
                                               //console.log(citysubval);
                                                //console.log(locationsearch);
                                                console.log(citysubval);
                                                if(!$.isNumeric(citysubval)) {
                                                    // console.log(citysubval);
                                                    var serach = citysubval.toLowerCase()
                                                    var location = locationsearch.toLowerCase()
                                                    // console.log(location);
                                                    if(serach.indexOf(location) > -1){
                                                    // if(citysubval == locationsearch){
                                                    // console.log('ad');
                                                    // $('.serach-active[data-parent="#accordion10"]').trigger( "click" );
                                                    //console.log(countrykey);
                                                    //console.log(countrykey);
                                                    //console.log(citykey);
                                                    $('.serach-active[data-parent="#accordion' + countrykey +key+ '"]').trigger( "click" );
                                                    }
                                                }
                                            });
                                        });
                                    }
                                });
                            });
                        }
                    });
            });
        }
    });
</script>
@endsection

