@extends('layouts.base')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h3> Edit City</h3>
    </div>
    <div class="col-lg-2 pull-right">
        <a href="{{ URL::previous() }}" type="button" class="btn btn-outline btn-primary pull-right">Back</a>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">    
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>All form elements with * are required fields.</h5>
                </div>
                <div class="ibox-content">
                    {{ Form::open(['route' => ['editcity',$city->id], 'method' => 'POST', 'id'=>'update-city-form','enctype' => 'multipart/form-data']) }}

                    <input type="hidden" name="actionname" value="{{Route::current()->getName()}}" id="actionname"/>
                    <input type="hidden" name="id" value="{{$city->id}}" id="city-id"/>
                    <div class="row">
                        <div class="col-md-12">   
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Select Country: <span class="input-required">*</span></label>
                                    {{ Form::select('country',[null => 'Select Country'] + $country,$city->country_id, ['class' => 'form-control', 'id' => 'country']) }}
                                </div>  
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">City Name: <span class="input-required">*</span></label>
                                    {{ Form::text('city',$city->city, ['class' => 'form-control', 'id' => 'city']) }}
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-7">
                                <div class="col-md-7">
                                    <div class="form-group"><label class="col-sm-2 control-label category-label">Category:<span class="input-required">*</span></label>
                                        <div class="col-sm-10">
                                            @if(count($categories))
                                            @foreach($categories as $categorykey => $categoryvalue)
                                            @php $match = 0; @endphp
                                            <div class="checkbox cnone">
                                                <label>       
                                                    @if(count($city->categories))
                                                    
                                                    @foreach($city->categories as $ckey => $cvalue)
                                                    @if($cvalue->category_id == $categorykey)
                                                    @php $match = 1; @endphp
                                                    <input type="checkbox" checked="" name="category[]" value="{{$categorykey}}" /> {{$categoryvalue}}
                                                    @endif
                                                    @endforeach
                                                    @endif 
                                                    
                                                    @if($match == 0)
                                                    <input type="checkbox" name="category[]" value="{{$categorykey}}" /> {{$categoryvalue}}
                                                    @endif
                                                </label>
                                            </div>
                                            @endforeach
                                            @endif
                                        </div> 
                                    </div>
                                </div>
                                <?php 
                                    $des_active = '';
                                     if($city->popular_destination == 1 ){
                                         $des_active = 'checked';
                                    }
                                    ?>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label>Popular Destination:
                                        <input type="checkbox" class="i-checks " {{$des_active}} name="popular_destination" value="1">
                                        </label>
                                    </div>                                  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="control-label">Description: </label>
                                    {{ Form::textarea('description',$city->description, ['class' => 'form-control', 'id' => 'description','rows' => 4]) }}
                                </div>  
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">   
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Image:</label>
                                    {{ Form::file('image', ['id' => 'image', 'class' => 'form-control-file',"onchange" =>  "readURL(this)"]) }}
                                    <img id="image-preview" class="image-priview-activity" src="{{url('public/img/cityimages/resized/'.$city->image)}}" alt="" />
                                </div>  
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Timezone: <span class="input-required">*</span></label>
                                    {{ Form::select('timezone',[null => 'Select Timezone'] + $timezone,$city->timezone.'--'.$city->zone_name,['class' => 'form-control', 'id' => 'city']) }}
                                </div>
                            </div> 
                        </div>
                    </div>                                                            
                    <div class="form-group">
                        <div class="col-sm-4">
                            <a href="{{route('locations')}}" class="btn btn-white">Cancel</a>
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

