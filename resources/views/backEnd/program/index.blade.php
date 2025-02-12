@extends('backEnd.master')
@section('title')
@lang('programs')
@endsection 
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('Programs')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('hr.human_resource')</a>
                <a href="#">@lang('Program')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        @if(isset($program))
         @if(userPermission(376))
                        
        <div class="row">
            <div class="offset-lg-10 col-lg-2 text-right col-md-12 mb-20">
                <a href="{{route('district_module')}}" class="primary-btn small fix-gr-bg">
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
                            <h3 class="mb-30">@if(isset($program))
                                    @lang('Edit Program')
                                @else
                                    @lang('Add Program')
                                @endif
                             
                            </h3>
                        </div>
                        @if(isset($program))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => array('program-update',$program->p_id), 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
                        @else
                         @if(userPermission(376))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'program_submit',
                        'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        @endif
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row">
                                    <div class="col-lg-12">
                                        
                                        <div class="input-effect">
                                            <input class="primary-input form-control{{ $errors->has('program_name') ? ' is-invalid' : '' }}"
                                                type="text" name="program_name" autocomplete="off" value="{{isset($program)? $program->p_name:''}}"  >
                                            <input type="hidden" name="id" value="{{isset($program)? $program->p_id: ''}}">
                                            <label>@lang('Program Name') <span>*</span></label>
                                            <span class="focus-border"></span>
                                            @if ($errors->has('program_name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('program_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                               @php 
                                  $tooltip = "";
                                  if(userPermission(376)){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                <div class="row mt-40">
                                    <div class="col-lg-12 text-center">
                                      <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{$tooltip}}">
                                            <span class="ti-check"></span>
                                          @isset($program)
                                              @lang('Update Program')
                                          @else
                                              @lang('Save Program')
                                          @endisset
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
                            <h3 class="mb-0">@lang('Program List')</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <table id="table_id" class="display school-table" cellspacing="0" width="100%">

                            <thead>
                               
                                <tr>
                                    <th>@lang('program')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($programs as $program) 
                                <tr>
                                    <td>{{$program->p_name}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                @lang('common.select')
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if(userPermission(186))

                                                <a class="dropdown-item" href="{{route('program-edit', [$program->p_id
                                                    ])}}">@lang('common.edit')</a>
                                               @endif
                                               @if(userPermission(187))

                                               <a class="dropdown-item" data-toggle="modal" data-target="#deleteHumanDepartModal{{$program->p_id}}"
                                                    href="#">@lang('common.delete')</a>
                                            @endif
                                                </div>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade admin-query" id="deleteHumanDepartModal{{$program->p_id}}" >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">@lang('Delete program')</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                </div>

                                                <div class="mt-40 d-flex justify-content-between">
                                                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.cancel')</button>
                                                     {{ Form::open(['route' => array('program-delete',$program->p_id), 'method' => 'DELETE', 'enctype' => 'multipart/form-data']) }}
                                                    <button class="primary-btn fix-gr-bg" type="submit">@lang('common.delete')</button>
                                                     {{ Form::close() }}
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
