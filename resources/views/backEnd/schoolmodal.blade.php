@extends('backEnd.master')

@section('title')
    {{@Auth::user()->roles->name}}  @lang('common.dashboard')
@endsection


@section('mainContent')
 <!--    <section class="mb-40">
         
                    <div class="col-lg-3">
                                <div class="input-effect">
                                     <select name="school_name" onchange="get_school(this);" class="niceSelect w-100 bb form-control {{ $errors->has('school_name') ? ' is-invalid' : '' }}" id="school_name">
                                        <option data-display="@lang('select school')" value="">@lang('school')</option>
                                        @foreach($school_info as $school)
                                            <option value="{{$school->id}}"  >{{$school->school_name}} 
                                            </option>
                                            @endforeach
                                    </select>
                                    @if ($errors->has('school_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('school_name') }}</strong>
                                    </span>
                                    @endif

                                 </div>
                    </div>
                  
    </section> -->
     


    <div id="fullCalModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modalTitle" class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span> <span class="sr-only">@lang('common.close')</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="" alt="There are no image" id="image" class="" height="150" width="auto">
                    <div id="modalBody"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="primary-btn tr-bg" data-dismiss="modal">@lang('common.close')</button>
                </div>
            </div>
        </div>
    </div>

    


@endsection

@section('script')

    

    <script type="text/javascript">
        if ($('.common-calendar').length) {
            $('.common-calendar').fullCalendar({
                locale: _locale,
                rtl : _rtl,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                eventClick:  function(event, jsEvent, view) {
                    $('#modalTitle').html(event.title);
                    $('#modalBody').html(event.description);
                    let url = event.url;
                    let description = event.description;
                    if(url.includes('lead')){
                        $('#image').addClass('d-none');
                        $('#modalBody').html(event.description);
                    }else{
                        $('#image').attr('src',event.url);
                    }
                    $('#fullCalModal').modal();
                    return false;
                },
                height: 650,
                events:   ,
            });
        }
    </script>
    
@endsection

