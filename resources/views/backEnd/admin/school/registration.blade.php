@extends('backEnd.master')
@section('title')
@lang('school registration')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('school registration')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="{{route('general-settings')}}">@lang('School registration')</a>
              </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-6">
                <div class="main-title">
                    <h3 class="mb-30">
                        @lang('Create')
                   </h3>
                </div>
            </div>
        </div> 
        @if(isset($editData))
            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => ['edit-school-registration',$editData->id], 'method' => 'patch', 'enctype' => 'multipart/form-data']) }}
        @else
            @if(userPermission(409))
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'add-school-registration', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
            @endif
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">


                        <div class="row mb-40">
                            <div class="col-lg-3">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('school_name') ? ' is-invalid' : '' }}"
                                    type="text" name="school_name" autocomplete="off" value="{{isset($editData)? @$editData->school_name : old('school_name')}}">
                                    <label>@lang('common.school_name') <span>*</span></label>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('school_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('school_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('school_code') ? ' is-invalid' : '' }}"
                                    type="text" name="school_code" autocomplete="off" value="{{isset($editData)? @$editData->school_code: old('school_code')}}">
                                    <label>@lang('system_settings.school_code') <span>*</span></label>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('school_code'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('school_code') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        
                            <div class="col-lg-3">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                    type="text" name="phone" autocomplete="off" value="{{ isset($editData) ? @$editData->phone : old('phone')}}">
                                    <label>@lang('common.phone') <span>*</span></label>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="row mb-40">
                            
                            <div class="col-lg-3">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    type="text" name="email" autocomplete="off" value="{{isset($editData)? @$editData->email: old('email')}}">
                                    <label>@lang('common.email') <span>*</span></label>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            
                        <div class="col-lg-3">
                                <div class="input-effect">
                                    <input class="primary-input form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                    type="text" name="address" autocomplete="off" value="{{isset($editData) ? @$editData->address : old('address')}}">
                                    <label>@lang('common.address') <span>*</span></label>
                                    <span class="focus-border"></span>
                                    @if ($errors->has('address'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                        </div>
                        
                            
                     
                            <div class="col-lg-3">
                                <div class="input-effect">
                                     <select name="district_name" onchange="get_school(this);" class="niceSelect w-100 bb form-control {{ $errors->has('district_name') ? ' is-invalid' : '' }}" id="district_name">
                                        <option data-display="@lang('select district')" value="{{old('district_name')}}">@lang('district')<span>*</span></option>
                                         @foreach($districts as $district)
                                          
                                            <option value="{{$district->district_id}}"  {{(isset($editData->district_idFk) && $editData->district_idFk ==  $district->district_id? "selected":"")}} >{{$district->district_name}} </option>
                                 
                                        @endforeach
                                    </select>
                                    @if ($errors->has('district_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('district_name') }}</strong>
                                    </span>
                                    @endif

                                 </div>
                    </div>
                        
                        <!-- <div class="col-lg-3">
                                <div class="input-effect">
                                     <select name="district_name" onchange="get_school(this);" class="niceSelect w-100 bb form-control {{ $errors->has('district_name') ? ' is-invalid' : '' }}" id="district_name">
                                        <option data-display="@lang('select district')" value="">@lang('district')</option>
                                        @if(isset($editData))
                                            <option value="{{!empty($editData->district_id)??''}}" selected="selected">{{!empty($editData->district_name)??''}} </option>
                                        @else
                                            @foreach($districts as $district)
                                            <option value="{{$district->district_id}}">{{$district->district_name}} </option>
                                             @endforeach
                                        @endif

                                    </select>
                                    @if ($errors->has('district_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('district_name') }}</strong>
                                    </span>
                                    @endif

                                 </div>
                    </div> -->

                        </div>

                        
                    
                        <div class="row mb-30 mt-20">
                          

                    
                    <div class="row mt-40">
                        <div class="col-lg-12 text-center">

                            @if(isset($editData))
                                <span class="d-inline-block" > <button class="primary-btn small fix-gr-bg  "   type="submit" > @lang('common.update')</button></span>
                            @else
                                @if(userPermission(409))
                                <button type="submit" class="primary-btn fix-gr-bg submit">
                                    <span class="ti-check"></span>
                                    @lang('common.add')
                                </button>
                                @endif
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        {{ Form::close() }}
    </div>

</div>
</section>
<div class="modal fade admin-query question_image_preview"  >
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('system_settings.layout_image')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <img src="" width="100%" class="question_image_url" alt="">
            </div>

        </div>
    </div>
</div>
<script>
    $(document).on('click', '.layout_image', function(){
        // $('.question_image_url').src(this.src);
        $('.question_image_url').attr('src',this.src);   
        $('.question_image_preview').modal('show');
    })

    $('#fees_enable').change(function() {
        $('#newFees').modal('show');
    });
</script>
@endsection
