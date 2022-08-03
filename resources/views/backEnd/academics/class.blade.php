@extends('backEnd.master')
@section('title') 
@lang('common.class')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('common.class')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('academics.academics')</a>
                    <a href="#">@lang('common.class')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            @if(isset($sectionId))
                @if(userPermission(266))
                    <div class="row">
                        <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                            <a href="{{route('class')}}" class="primary-btn small fix-gr-bg">
                                <span class="ti-plus pr-2"></span>
                                @lang('common.add')
                            </a>
                        </div>
                    </div>
                @endif
            @endif
            <div class="row">
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30">@if(isset($sectionId))
                                        @lang('academics.edit_class')
                                    @else
                                        @lang('academics.add_class')
                                    @endif
                                   
                                </h3>
                            </div>
                            @if(isset($sectionId))
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'class_update', 'method' => 'POST']) }}
                            @else
                                @if(userPermission(266))

                                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'class_store', 'method' => 'POST']) }}
                                @endif
                            @endif
                            <div class="white-box">
                                <div class="add-visitor">
                                    <div class="row">
                                        <div class="col-lg-12"> 
                                            <div class="input-effect">
                                                <input class="primary-input form-control{{ @$errors->has('name') ? ' is-invalid' : '' }}"
                                                       type="text" name="name" autocomplete="off"
                                                       value="{{isset($classById)? @$classById->class_name: ''}}">
                                                <input type="hidden" name="id"
                                                       value="{{isset($classById)? $classById->id: ''}}">
                                                <label>@lang('common.name') <span>*</span></label>
                                                <span class="focus-border"></span>
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ @$errors->first('name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                  
                                     <div class="row mt-30">
                            <div class="col-lg-12">
                                <div class="input-effect">
                                     <select name="district_name" onchange="get_school_info(this);" class="niceSelect w-100 bb form-control {{ $errors->has('district_name') ? ' is-invalid' : '' }}" id="district_name">
                                        <option data-display="@lang('select district *')" value="{{old('district_name')}}">@lang('district')<span>*</span></option>
                                         @foreach($districts as $district)
                                          
                                            <option value="{{$district->district_id}}" {{ (old("district_name") ==  $district->district_id? "selected":"") }} @if(isset($classById->district_idFk) && $classById->district_idFk == $district->district_id) {{"selected"}} @endif>{{$district->district_name}} </option>
                                             
                                        @endforeach
                                    </select>
                                    @if ($errors->has('district_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('district_name') }}</strong>
                                    </span>
                                    @endif

                                 </div>
                    </div>
                </div>
                    <div class="row mt-30">
                            <div class="col-lg-12 school_information">
                     
                                <div class="input-effect">
                                     <select name="school_name" onchange="get_section_info(this);" class="nice-select   w-100 bb form-control school_data {{ $errors->has('school_name') ? ' is-invalid' : '' }}" id="school_name" style="color: #828bb2;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;">
                                    <option data-display="@lang('select school')" value="{{old('school_name')}}">@lang('select school')<span>*</span></option>
                                        @if(isset($classById->school_id))
                                        @foreach($sm_Schools as $school)
                                    <option value="{{$school->id}}" @if($classById->school_id == $school->id) {{"selected"}} @endif >{{$school->school_name}}</option>
                                        @endforeach
                                        @endif
                                         
                                    </select>
                                    @if ($errors->has('school_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('school_name') }}</strong>
                                    </span>
                                    @endif

                                 </div>
                    </div> 
                </div> 
                     
                    <div class="row mt-30">
                                        <div class="col-lg-12">
                                            <label>@lang('common.section')*</label><br>
                                            
                                                    
                                                        <div class="section_data"></div>
                                                    
                                                </div>
                                          
                                            @if($errors->has('section'))
                                                <span class="text-danger validate-textarea-checkbox" role="alert">
                                                <strong>{{ @$errors->first('section') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    @php
                                        $tooltip = "";
                                        if(userPermission(266)){
                                              $tooltip = "";
                                          }else{
                                              $tooltip = "You have no permission to add";
                                          }
                                    @endphp
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip"
                                                    title="{{$tooltip}}">
                                                <span class="ti-check"></span>
                                                @if(isset($sectionId))
                                                    @lang('academics.update_class')
                                                @else
                                                    @lang('academics.save_class')
                                                @endif
                                              
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('academics.class_list')</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">

                            <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                                <thead>
                            
                                <tr>
                                    <th>@lang('common.class')</th>
                                    <th>@lang('common.section')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($classes as $class)
                                    <tr>
                                        <td valign="top">{{@$class->class_name}}</td>
                                        <td>
                                            @if(@$class->groupclassSections)
                                                @foreach ($class->groupclassSections as $section)
                                                {{@$section->sectionName->section_name}} ,
                                                @endforeach
                                            @endif
                                        </td>

                                        <td valign="top">
                                            <div class="dropdown">
                                                <button type="button" class="btn dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    @lang('common.select')
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if(userPermission(263))
                                                        <a class="dropdown-item"
                                                           href="{{route('class_edit', [@$class->id])}}">@lang('common.edit')</a>
                                                    @endif
                                                    @if(userPermission(264))
                                                        <a class="dropdown-item" data-toggle="modal"
                                                           data-target="#deleteClassModal{{@$class->id}}"
                                                           href="{{route('class_delete', [@$class->id])}}">@lang('common.delete')</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>


                                    <div class="modal fade admin-query" id="deleteClassModal{{@$class->id}}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">@lang('academics.delete_class')</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="text-center">
                                                        <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                    </div>

                                                    <div class="mt-40 d-flex justify-content-between">
                                                        <button type="button" class="primary-btn tr-bg"
                                                                data-dismiss="modal">@lang('common.cancel')</button>
                                                        <a href="{{route('class_delete', [$class->id])}}"
                                                           class="text-light">
                                                            <button class="primary-btn fix-gr-bg"
                                                                    type="submit">@lang('common.delete')</button>
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script>
    $( document ).ready(function() {
        var school_id = $('.school_data').val();

        get_section_edit_info(school_id);
    });
 </script>
<script type="text/javascript">
 
    
 
    
 
function get_school_info(sel)  
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
        $('.school_data').html('<option>select school</option>');
        $('.section_data').html('');
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

function get_section_info(sel)  
    {
        var id = sel.value;
     $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        type : "POST",
        url: '<?=route("districtWisesection")?>',
        dataType : "JSON",
        data : {id:id},
        success: function(data){
        $('.section_data').html('');

        var len = data.length;  
            for (var i = 0; i < len; i++) {
                            var id = data[i]['id'];
                            var name = data[i]['section_name'];
                            
                            // $('.section_data').append($('<option>',
                            //  {
                            //     value: id,
                            //     text : name 
                            // }));
                            $('.section_data').append('<input type="checkbox" id="section'+id+'"'+
                                                                   'class="common-checkbox  form-control"'+
                                                                   'name="section[]" value="'+id+'">'+
                                                            '<label for="section'+id+'">'+name+' section<span  ></span>  </label>');
            }
        
        // alert(data[0].school_name)
      }
    }); 
}
function get_section_edit_info(sel)  
    {
        var id = sel; 
         
     $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
        type : "POST",
        url: '<?=route("districtWisesection")?>',
        dataType : "JSON",
        data : {id:id},
        success: function(data){
        $('.section_data').html('');

        var len = data.length;  
            for (var i = 0; i < len; i++) {
                            var id = data[i]['id'];
                            var name = data[i]['section_name'];
                            
                            // $('.section_data').append($('<option>',
                            //  {
                            //     value: id,
                            //     text : name 
                            // }));
                            $('.section_data').append('<input type="checkbox"  checked id="section'+id+'"'+
                                                                   'class="common-checkbox  form-control"'+
                                                                   'name="section[]" value="'+id+'">'+
                                                            '<label for="section'+id+'">'+name+' section<span  ></span>  </label>');
            }
        
        // alert(data[0].school_name)
      }
    }); 
}    
</script>