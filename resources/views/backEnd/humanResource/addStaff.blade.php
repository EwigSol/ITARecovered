@extends('backEnd.master')
@section('title') 
@lang('hr.add_new_staff')
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('public/backEnd/')}}/css/croppie.css">
@endsection
@section('mainContent')
<style type="text/css">
    .form-control:disabled{
        background-color: #FFFFFF;
    }
</style>

<input type="text" hidden id="urlStaff" value="{{ route('staffPicStore') }}">
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('hr.add_new_staff')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="{{ route('staff_directory') }}">@lang('hr.human_resource')</a>
                <a href="#">@lang('hr.add_new_staff')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('hr.staff_information') </h3>
                </div>
            </div>
            <div class="col-lg-4 text-md-right text-left col-md-6 mb-30-lg">
                <a href="{{route('staff_directory')}}" class="primary-btn small fix-gr-bg">
                    @lang('hr.all_staff_list')
                </a>
            </div>
        </div>
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'staffStore', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-lg-12">
              <div class="white-box">
                <div class="">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h4>@lang('hr.basic_info')</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-20">
                        <div class="col-lg-12">
                            <hr>
                        </div>
                    </div>

                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                     @if( moduleStatusCheck('MultiBranch') && isset($branches ))
                     <div class="row mb-30">
                            <div class="col-lg-3">
                                <div class="input-effect">
                                    <select class="niceSelect w-100 bb form-control{{ $errors->has('branch_id') ? ' is-invalid' : '' }}" name="branch_id" id="branch_id">
                                         <option data-display="@lang('hr.branch') *" value="">@lang('hr.branch') *</option>
                                            @foreach($branches as $branch)
                                            <option value="{{$branch->id}}" {{isset($branch_id)? ($branch->id == $branch_id? 'selected':''): ''}}>{{$branch->branch_name}}</option>
                                            @endforeach
                                    </select>
                                     <span class="focus-border"></span>
                                        @if ($errors->has('branch_id'))
                                        <span class="invalid-feedback invalid-select" role="alert">
                                            <strong>{{ $errors->first('branch_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                     </div> 
                        @endif
                    <div class="row mb-30">
                        <div class="col-lg-3">
                            <div class="input-effect">
                                <input class="primary-input form-control{{ $errors->has('staff_no') ? ' is-invalid' : '' }}" type="text"  name="staff_no" value="{{$max_staff_no != ''? $max_staff_no + 1 : 1}}" readonly>
                                <span class="focus-border"></span>
                                <label>@lang('hr.staff_no') <span>*</span> </label>
                                @if ($errors->has('staff_no'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('staff_no') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="input-effect">
                                <select class="niceSelect w-100 bb form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}" onchange="get_role(this);" name="role_id" id="role_id">
                                    <option data-display="@lang('hr.role') *" value="">@lang('common.select')</option>
                                    @foreach($roles as $key=>$value)
                                    <option value="{{$value->id}}" {{ (old("role_id") ==  $value->id? "selected":"") }}>{{$value->name}}</option>
                                    @endforeach
                                </select>
                                <span class="focus-border"></span>
                                @if ($errors->has('role_id'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('role_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="input-effect">
                                <select class="niceSelect w-100 bb form-control{{ $errors->has('department_id') ? ' is-invalid' : '' }}" name="department_id" id="department_id">
                                    <option data-display="@lang('hr.department') *" value="">@lang('common.select') </option>
                                    @foreach($departments as $key=>$value)
                                    <option value="{{$value->id}}" {{ old('department_id')==$value->id? 'selected': '' }}  >{{$value->name}}</option>
                                    @endforeach
                                </select>
                                <span class="focus-border"></span>
                                @if ($errors->has('department_id'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('department_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-effect">
                                <select class="niceSelect w-100 bb form-control{{ $errors->has('designation_id') ? ' is-invalid' : '' }}" name="designation_id" id="designation_id">
                                    <option data-display="@lang('hr.designations') *" value="">@lang('common.select') </option>
                                    @foreach($designations as $key=>$value)
                                    <option value="{{$value->id}}" {{ old('designation_id')==$value->id? 'selected': '' }} >{{$value->title}}</option>
                                    @endforeach
                                </select>
                                <span class="focus-border"></span>
                                @if ($errors->has('designation_id'))
                                <span class="invalid-feedback invalid-select" role="alert">
                                    <strong>{{ $errors->first('designation_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                      
                    </div>



                    <div class="row mb-30">
                        <div class="col-lg-3">
                            <div class="input-effect">
                                <input class="primary-input form-control {{$errors->has('first_name') ? 'is-invalid' : ' '}}" type="text"  name="first_name" value="{{old('first_name')}}">
                                <span class="focus-border"></span>
                                <label>@lang('hr.first_name') <span>*</span> </label>
                                @if ($errors->has('first_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-effect">
                                <input class="primary-input form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" type="text"  name="last_name" value="{{old('last_name')}}">
                                <span class="focus-border"></span>
                                <label>@lang('hr.last_name') <span>*</span> </label>
                                @if ($errors->has('last_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-effect">
                                <input class="primary-input form-control{{ $errors->has('fathers_name') ? ' is-invalid' : '' }}" type="text"  name="fathers_name" value="{{old('first_name')}}">
                                <span class="focus-border"></span>
                                <label>@lang('student.father_name')</label>
                                @if ($errors->has('fathers_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('fathers_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-effect">
                                <input class="primary-input form-control{{ $errors->has('mothers_name') ? ' is-invalid' : '' }}" type="text" name="mothers_name" value="{{old('mothers_name')}}">
                                <span class="focus-border"></span>
                                <label>@lang('hr.mother_name')</label>
                                @if ($errors->has('mothers_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('mothers_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mb-30">
                       <div class="col-lg-3">
                        <div class="input-effect">
                            <input onkeyup="emailCheck(this)" class="primary-input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email"  name="email" value="{{old('email')}}">
                            <span class="focus-border"></span>
                            <label>@lang('common.email') <span>*</span> </label>
                            @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="input-effect">
                            <select class="niceSelect w-100 bb form-control{{ $errors->has('gender_id') ? ' is-invalid' : '' }}" name="gender_id">
                                <option data-display="@lang('common.gender') *" value="">@lang('common.gender') *</option>
                                @foreach($genders as $gender)
                                <option value="{{$gender->id}}" {{old('gender_id') == $gender->id? 'selected': ''}}>{{$gender->base_setup_name}}</option>
                                @endforeach
                            </select>
                            <span class="focus-border"></span>
                            @if ($errors->has('gender_id'))
                            <span class="invalid-feedback invalid-select" role="alert">
                                <strong>{{ $errors->first('gender_id') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    @if($role_id != 10)
                    <div class="col-lg-3">
                                <div class="input-effect">
                                     <select name="district_name" onchange="get_school(this);" class="niceSelect w-100 bb form-control {{ $errors->has('district_name') ? ' is-invalid' : '' }}" id="district_name">
                                        <option data-display="@lang('select district')" value="{{old('district_name')}}">@lang('district')<span>*</span></option>
                                         @foreach($districts as $district)
                                         @if($role_id == 11  )
                                            <option value="{{$district->district_id}}" selected="selected">{{$district->district_name}} </option>
                                        
                                        @else
                                            <option value="{{$district->district_id}}" {{ (old("district_name") ==  $district->district_id? "selected":"") }}>{{$district->district_name}} </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if ($errors->has('district_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('district_name') }}</strong>
                                    </span>
                                    @endif

                                 </div>
                    </div>
                    @endif
                    <div class="col-lg-3 school_information">
                                <div class="input-effect">
                                     <select name="school_name" class="nice-select   w-100 bb form-control school_data {{ $errors->has('school_name') ? ' is-invalid' : '' }}" id="school_name" style="color: #828bb2;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;">
                                        <option data-display="@lang('select school')" value="{{old('school_name')}}">@lang('select school')<span>*</span></option>
                                        @if($role_id == 11 || $role_id == 10)
                                            @foreach($sm_stuff as $school)
                                            <option value="{{$school->id}}" {{ $role_id == 11 ? 'selected="selected"' : '' }} >{{$school->school_name}} 
                                            </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if ($errors->has('school_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('school_name') }}</strong>
                                    </span>
                                    @endif

                                 </div>
                    </div> <br>
                    <br>
                    <br>
                    <div class="col-lg-3  ">
                                <div class="input-effect">
                                     <select name="program_name" class="nice-select   w-100 bb form-control program_name {{ $errors->has('program_name') ? ' is-invalid' : '' }}" id="program_name" style="color: #828bb2;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;">
                                        <option data-display="@lang('select program')" value="{{old('program_name')}}">@lang('select programs')<span>*</span></option>
                                        
                                            @foreach($program as $programs)
                                            <option value="{{$programs->p_id}}"  >{{$programs->p_name}} 
                                            </option>
                                            @endforeach
                                        
                                    </select>
                                    @if ($errors->has('program_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('program_name') }}</strong>
                                    </span>
                                    @endif

                                 </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="col-lg-3">
                        <div class="no-gutters input-right-icon">
                            <div class="col">
                                <div class="input-effect">
                                    <input class="primary-input date form-control{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                     name="date_of_birth" value="{{ old('date_of_birth') }}" autocomplete="off">
                                    <span class="focus-border"></span>
                                    <label>@lang('common.date_of_birth')</label>
                                    @if ($errors->has('date_of_birth'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('date_of_birth') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="" type="button">
                                    <i class="ti-calendar" id="start-date-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3">
                        <div class="no-gutters input-right-icon">
                            <div class="col">
                                <div class="input-effect">
                                    <input class="primary-input date form-control{{ $errors->has('date_of_joining') ? ' is-invalid' : '' }}" id="date_of_joining" type="text"
                                     name="date_of_joining" value="{{date('m/d/Y')}}">
                                    <span class="focus-border"></span>
                                    <label>@lang('hr.date_of_joining') </label>
                                    @if ($errors->has('date_of_joining'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('date_of_joining') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="" type="button">
                                    <i class="ti-calendar" id="date_of_joining"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-20">
                 <div class="col-lg-3">
                    <div class="input-effect">
                        <input oninput="phoneCheck(this)" class="primary-input form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" type="text"  name="mobile" value="{{old('mobile')}}">
                        <span class="focus-border"></span>
                        <label>@lang('common.mobile')  </label>
                        @if ($errors->has('mobile'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('mobile') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-effect">
                        <select class="niceSelect w-100 bb form-control" name="marital_status">
                            <option data-display="@lang('hr.marital_status')" value="">@lang('hr.marital_status')</option>

                            <option {{old('marital_status') == 'married'? 'selected': ''}} value="married">@lang('hr.married')</option>
                            <option {{old('marital_status') == 'unmarried'? 'selected': ''}} value="unmarried">@lang('hr.unmarried')</option>

                        </select>
                        <span class="focus-border"></span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-effect">
                        <input oninput="phoneCheck(this)" class="primary-input form-control{{ $errors->has('emergency_mobile') ? ' is-invalid' : '' }}" type="text"  name="emergency_mobile" value="{{old('emergency_mobile')}}">
                        <span class="focus-border"></span>
                        <label>@lang('hr.emergency_mobile') <span></span></label>
                        @if ($errors->has('emergency_mobile'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('emergency_mobile') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="input-effect">
                        <input class="primary-input form-control{{ $errors->has('driving_license') ? ' is-invalid' : '' }}" type="text"  name="driving_license" value="{{old('driving_license')}}">
                        <span class="focus-border"></span>
                        <label>@lang('hr.driving_license')</label>
                        @if ($errors->has('driving_license'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('driving_license') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>


            </div>
            <div class="row mb-20">
                <div class="col-lg-6">
                    <div class="row no-gutters input-right-icon">
                        <div class="col">
                            <div class="input-effect">

                                <input class="primary-input form-control {{ $errors->has('staff_photo') ? ' is-invalid' : '' }}" type="text" id="placeholderStaffsName"
                                placeholder="{{isset($editData->file) && $editData->file != '' ? getFilePath3($editData->file):trans('hr.staff_photo')}}"
                                disabled>
                                <span class="focus-border"></span>

                                @if ($errors->has('staff_photo'))
                                     <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('staff_photo') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="primary-btn-small-input" type="button">
                                <label class="primary-btn small fix-gr-bg" for="staff_photo">@lang('common.browse')</label>
                                <input type="file" class="d-none" name="staff_photo" id="staff_photo">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-30">
                <div class="col-lg-6">
                    <div class="input-effect">
                        <textarea class="primary-input form-control {{ $errors->has('current_address') ? 'is-invalid' : ''}}" cols="0" rows="4" name="current_address" id="current_address">{{ old('current_address') }}</textarea>
                        <label>@lang('hr.current_address') <span>*</span> </label>
                        <span class="focus-border textarea"></span>

                        @if ($errors->has('current_address'))
                         <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('current_address') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="input-effect">
                        <textarea class="primary-input form-control {{ $errors->has('permanent_address') ? 'is-invalid' : ''}}" cols="0" rows="4"  name="permanent_address" id="permanent_address">{{ old('permanent_address') }}</textarea>
                        <label>@lang('hr.permanent_address') <span></span> </label>
                        <span class="focus-border textarea"></span>
                         @if ($errors->has('permanent_address'))
                         <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('permanent_address') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row md-20">
                <div class="col-lg-6">
                    <div class="input-effect">
                        <textarea class="primary-input form-control" cols="0" rows="4" name="qualification" id="qualification">{{ old('qualification') }}</textarea>
                        <label>@lang('hr.qualifications') </label>
                        <span class="focus-border textarea"></span>
                        @if ($errors->has('qualification'))
                        <span class="danger text-danger">
                            <strong>{{ $errors->first('qualification') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="input-effect">
                        <textarea class="primary-input form-control" cols="0" rows="4"  name="experience" id="experience" value="{{old('experience')}}"></textarea>
                        <label>@lang('hr.experience') </label>
                        <span class="focus-border textarea"></span>
                        @if ($errors->has('experience'))
                        <span class="danger text-danger">
                            <strong>{{ $errors->first('experience') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="main-title">
                        <h4>@lang('hr.payroll_details')</h4>
                    </div>
                </div>
            </div>
            <div class="row mb-30">
                <div class="col-lg-12">
                    <hr>
                </div>
            </div>
            <div class="row mb-20">
                <div class="col-lg-3">
                   <div class="input-effect">
                    <input class="primary-input form-control{{ $errors->has('epf_no') ? ' is-invalid' : '' }}" type="text"  name="epf_no" value="{{old('epf_no')}}">
                    <label>@lang('hr.epf_no')</label>
                    <span class="focus-border"></span>
                    @if ($errors->has('epf_no'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('epf_no') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="col-lg-3">
               <div class="input-effect">
                   <input oninput="numberCheck(this)" class="primary-input form-control{{ $errors->has('basic_salary') ? ' is-invalid' : '' }}" type="text"  name="basic_salary" value="{{old('basic_salary')}}" autocomplete="off">
                   <label>@lang('hr.basic_salary') *</label>
                   <span class="focus-border"></span>
                   @if ($errors->has('basic_salary'))
                   <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('basic_salary') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="col-lg-3">
            <div class="input-effect">
                <select class="niceSelect w-100 bb form-control" name="contract_type">
                    <option data-display="@lang('hr.contract_type')" value=""> @lang('hr.contract_type')</option>
                    <option value="permanent">@lang('hr.permanent') </option>
                    <option value="contract"> @lang('hr.contract')</option>
                </select>
                <span class="focus-border"></span>

            </div>
        </div>

<!--         <div class="col-lg-3">
           <div class="input-effect">
            <input class="primary-input form-control{{ $errors->has('location') ? ' is-invalid' : '' }}" type="text" value="{{ old('location') }}" name="location">
            <label>@lang('hr.location')</label>
            <span class="focus-border"></span>
            @if ($errors->has('location'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('location') }}</strong>
            </span>
            @endif
        </div>
    </div> -->
    
</div>
</div>

<div class="row mt-40">
    <div class="col-lg-12">
        <div class="main-title">
            <h4>@lang('hr.bank_info_details')</h4>
        </div>
    </div>
</div>
<div class="row mb-30">
    <div class="col-lg-12">
        <hr>
    </div>
</div>
<div class="row mb-20">
    <div class="col-lg-3">
       <div class="input-effect">
        <input class="primary-input form-control{{ $errors->has('bank_account_name') ? ' is-invalid' : '' }}" type="text"  name="bank_account_name" value="{{old('bank_account_name')}}">
        <label>@lang('hr.bank_account_name')</label>
        <span class="focus-border"></span>

    </div>
</div>

<div class="col-lg-3">
   <div class="input-effect">
    <input onkeyup="numberCheck(this)" class="primary-input form-control{{ $errors->has('bank_account_no') ? ' is-invalid' : '' }}" type="text"  name="bank_account_no" value="{{old('bank_account_no')}}">
        <label>@lang('accounts.account_no')</label>
    <span class="focus-border"></span>

</div>
</div>

<div class="col-lg-3">
   <div class="input-effect">
    <input class="primary-input form-control{{ $errors->has('bank_name') ? ' is-invalid' : '' }}" type="text"  name="bank_name" value="{{old('bank_name')}}">
    <label>@lang('accounts.bank_name')</label>
    <span class="focus-border"></span>

</div>
</div>

<div class="col-lg-3">
   <div class="input-effect">
    <input class="primary-input form-control{{ $errors->has('bank_brach') ? ' is-invalid' : '' }}" type="text"  name="bank_brach" value="{{old('bank_brach')}}">
    <label>@lang('accounts.branch_name')</label>
    <span class="focus-border"></span>

</div>
</div>

</div>

<div class="row mt-40">
    <div class="col-lg-12">
        <div class="main-title">
            <h4>@lang('hr.social_links_details')</h4>
        </div>
    </div>
</div>
<div class="row mb-30">
    <div class="col-lg-12">
        <hr>
    </div>
</div>
<div class="row mb-20">
    <div class="col-lg-3">
       <div class="input-effect">
        <input class="primary-input form-control{{ $errors->has('facebook_url') ? ' is-invalid' : '' }}" type="text" name="facebook_url" value={{old('facebook_url')}}>
        <label>@lang('hr.facebook_url')</label>
        <span class="focus-border"></span>

    </div>
</div>

<div class="col-lg-3">
   <div class="input-effect">
    <input class="primary-input form-control{{ $errors->has('twiteer_url') ? ' is-invalid' : '' }}" type="text"  name="twiteer_url" value="{{old('twiteer_url')}}">
    <label>@lang('hr.twitter_url')</label>
    <span class="focus-border"></span>

</div>
</div>

<div class="col-lg-3">
   <div class="input-effect">
    <input class="primary-input form-control{{ $errors->has('linkedin_url') ? ' is-invalid' : '' }}" type="text"  name="linkedin_url" value="{{old('linkedin_url')}}">
    <label>@lang('hr.linkedin_url')</label>
    <span class="focus-border"></span>

</div>
</div>

<div class="col-lg-3">
   <div class="input-effect">
    <input class="primary-input form-control{{ $errors->has('instragram_url') ? ' is-invalid' : '' }}" type="text"  name="instragram_url" value="{{old('instragram_url')}}">
    <label>@lang('hr.instragram_url')</label>
    <span class="focus-border"></span>

</div>
</div>

</div>

<div class="row mt-40">
    <div class="col-lg-12">
        <div class="main-title">
            <h4>@lang('hr.document_info')</h4>
        </div>
    </div>
</div>
<div class="row mb-30">
    <div class="col-lg-12">
        <hr>
    </div>
</div>

<div class="row mb-20">
   <div class="col-lg-4">
    <div class="row no-gutters input-right-icon">
        <div class="col">
            <div class="input-effect">
                <input class="primary-input" type="text" id="placeholderResume" placeholder="{{isset($editData->resume) && $editData->resume != ""? getFilePath3($editData->resume):trans('hr.resume')}}"
                readonly>
                <span class="focus-border"></span>
            </div>
        </div>
        <div class="col-auto">
            <button class="primary-btn-small-input" type="button">
                <label class="primary-btn small fix-gr-bg" for="resume">@lang('common.browse')</label>
                <input type="file" class="d-none" name="resume" id="resume">
            </button>
        </div>
    </div>
</div>
<div class="col-lg-4">
    <div class="row no-gutters input-right-icon">
        <div class="col">
            <div class="input-effect">
                <input class="primary-input" type="text" id="placeholderJoiningLetter" placeholder="{{isset($editData->joining_letter) && $editData->joining_letter != ""? getFilePath3($editData->joining_letter):trans('hr.joining_letter')}}"
                readonly>
                <span class="focus-border"></span>
            </div>
        </div>
        <div class="col-auto">
            <button class="primary-btn-small-input" type="button">
                <label class="primary-btn small fix-gr-bg" for="joining_letter">@lang('common.browse')</label>
                <input type="file" class="d-none" name="joining_letter" id="joining_letter">
            </button>
        </div>
    </div>
</div>
<div class="col-lg-4">
    <div class="row no-gutters input-right-icon">
        <div class="col">
            <div class="input-effect">
                <input class="primary-input" type="text" id="placeholderOthersDocument" placeholder="{{isset($editData->other_document) && $editData->other_document != ""? getFilePath3($editData->other_document):trans('hr.other_documents')}}"
                readonly>
                <span class="focus-border"></span>
            </div>
        </div>
        <div class="col-auto">
            <button class="primary-btn-small-input" type="button">
                <label class="primary-btn small fix-gr-bg" for="other_document">@lang('common.browse')</label>
                <input type="file" class="d-none" name="other_document" id="other_document">
            </button>
        </div>
    </div>
</div>
</div>

{{-- Custom Field Start --}}
<div class="row mt-40">
    <div class="col-lg-12">
        <div class="main-title">
            <h4>@lang('hr.custom_field')</h4>
        </div>
    </div>
</div>
<div class="row mb-30">
    <div class="col-lg-12">
        <hr>
    </div>
</div>
@include('backEnd.studentInformation._custom_field')
{{-- Custom Field End --}}

<div class="row mt-40">
    <div class="col-lg-12 text-center">
        <button class="primary-btn fix-gr-bg submit">
            <span class="ti-check"></span>
            @lang('hr.save_staff')

        </button>
    </div>
</div>
</div>
</div>
</div>
</div>
{{ Form::close() }}
</div>
</section>

<div class="modal" id="LogoPic">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">@lang('hr.crop_image_and_upload')</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="resize"></div>
                <button class="btn rotate float-lef" data-deg="90" >
                <i class="ti-back-right"></i></button>
                <button class="btn rotate float-right" data-deg="-90" >
                <i class="ti-back-left"></i></button>
                <hr>

                <a href="javascript:;" class="primary-btn fix-gr-bg pull-right" id="upload_logo">@lang('hr.crop')</a>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $(document).on('change','.cutom-photo',function(){
            let v = $(this).val();
            let v1 = $(this).data("id");
            console.log(v,v1);
            getFileName(v, v1);
        });

        function getFileName(value, placeholder){
            if (value) {
                var startIndex = (value.indexOf('\\') >= 0 ? value.lastIndexOf('\\') : value.lastIndexOf('/'));
                var filename = value.substring(startIndex);
                if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                    filename = filename.substring(1);
                }
                $(placeholder).attr('placeholder', '');
                $(placeholder).attr('placeholder', filename);
            }
        }
    })
</script>
<script src="{{asset('public/backEnd/')}}/js/croppie.js"></script>
<script src="{{asset('public/backEnd/')}}/js/editStaff.js"></script>
<script>
    $(document).ready(function(){
        
        $(document).on('change','.cutom-photo',function(){
            let v = $(this).val();
            let v1 = $(this).data("id");
            console.log(v,v1);
            getFileName(v, v1);

        });

        function getFileName(value, placeholder){
            if (value) {
                var startIndex = (value.indexOf('\\') >= 0 ? value.lastIndexOf('\\') : value.lastIndexOf('/'));
                var filename = value.substring(startIndex);
                if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                    filename = filename.substring(1);
                }
                $(placeholder).attr('placeholder', '');
                $(placeholder).attr('placeholder', filename);
            }
        }
        var selected_role_id =$('#role_id').val();
 
      
        if ( selected_role_id == 10) {  
            // if (District Admin) {
            $('.school_information').hide();
         }else{
            $('.school_information').show();
         }
        var selected_district_id =$('#district_name').val(); 
          var id = selected_district_id;
          
     $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
     $.ajax({
      type : "POST",
       url: '<?=route("districtWischool")?>',
      dataType : "JSON",
      data : {id:id},
      success: function(data){
         

       var len = data.length;  
       for (var i = 0; i < len; i++) {
                        var id = data[i]['id'];
                        var name = data[i]['school_name'];
                        
                        $('.school_data').append($('<option>',
                         {
                            value: id,
                            text : name 
                        }));
                    }
        
        // alert(data[0].school_name)
      }
    });
    })
    
    function get_school(sel)
    {
        var id = sel.value;
          
     $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
     $.ajax({
      type : "POST",
       url: '<?=route("districtWischool")?>',
      dataType : "JSON",
      data : {id:id},
      success: function(data){
        $('.school_data').html('');

       var len = data.length;  
       for (var i = 0; i < len; i++) {
                        var id = data[i]['id'];
                        var name = data[i]['school_name'];
                        
                        $('.school_data').append($('<option>',
                         {
                            value: id,
                            text : name 
                        }));
                    }
        
        // alert(data[0].school_name)
      }
    }); 
    }

    function get_role(data){
    var selectedText = data.options[data.selectedIndex].innerHTML;
        var selectedValue = data.value;
       
         if ( selectedValue == 10) {  
            // if (District Admin) {
            $('.school_information').hide();
         }else{
            $('.school_information').show();
         }
}
</script>
@endsection
