@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('cpvm-subject::language.titles.subject.create') }}@stop

{{-- page styles --}}
@section('header_styles')
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin .'/vendors/colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin .'/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/vendor/' . $group_name . '/' . $skin .'/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css') }}">
@stop
<!--end of page css-->


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>{{ $title }}</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('backend.homepage') }}">
                    <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                    {{ trans('adtech-core::labels.home') }}
                </a>
            </li>
            <li class="active"><a href="#">{{ $title }}</a></li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content paddingleft_right15">
        <!--main content-->
        <div class="row">
            <div class="the-box no-border">
                <!-- errors -->
                <form action="{{ route('cpvm.subject.subject.add') }}" method="post" class="bf" id="form-add-subject">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="row">
                        <div class="col-sm-4">
                            <label>{{ trans('cpvm-subject::language.label.subject.name') }} <span style="color: red">(*)</span></label>
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="{{trans('cpvm-subject::language.placeholder.subject.name')}}">
                            </div>
                            <label>{{trans('cpvm-subject::language.label.subject.background_color')}}<span style="color: red">(*)</span></label>
                            <div class="form-group">
                                <input id="mycp1" type="text" name="background_color" class="form-control colorpicker-element" placeholder="{{trans('cpvm-subject::language.placeholder.subject.background_color')}}">
                            </div>
                            <label>{{trans('cpvm-subject::language.label.subject.icon')}}<span style="color: red">(*)</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                   <span class="input-group-btn">
                                     <a id="lfm1" data-input="thumbnail1" data-preview="holder1" class="btn btn-primary">
                                       <i class="fa fa-picture-o"></i> {{trans('cpvm-subject::language.label.subject.choise_image')}}
                                     </a>
                                   </span>
                                   <input type="text" name="icon" id="thumbnail1" class="form-control">
                                 </div>
                                 <img id="holder1" style="margin-top:15px;max-height:100px;">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label>{{ trans('cpvm-subject::language.label.subject.priority') }} <span style="color: red">(*)</span></label>
                            <div class="form-group">
                                <input type="number" min="0" name="priority" class="form-control" placeholder="{{trans('cpvm-subject::language.placeholder.subject.priority')}}">
                            </div>
                            <label>{{trans('cpvm-subject::language.label.subject.background_color_mobile')}}<span style="color: red">(*)</span></label>
                            <div class="form-group">
                                <input id="mycp2" type="text" name="background_color_mobile" class="form-control" placeholder="{{trans('cpvm-subject::language.placeholder.subject.background_color_mobile')}}" autofocus="">
                            </div>
                            <label>{{trans('cpvm-subject::language.label.subject.icon_mobile')}}<span style="color: red">(*)</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                   <span class="input-group-btn">
                                     <a id="lfm2" data-input="thumbnail2" data-preview="holder2" class="btn btn-primary">
                                       <i class="fa fa-picture-o"></i> {{trans('cpvm-subject::language.label.subject.choise_image')}}
                                     </a>
                                   </span>
                                   <input type="text" name="icon_mobile" id="thumbnail2" class="form-control">
                                 </div>
                                 <img id="holder2" style="margin-top:15px;max-height:100px;">
                            </div>
                        </div>
                        <!-- /.col-sm-8 -->
                        <div class="col-sm-4">
                            <label>{{trans('cpvm-subject::language.label.subject.classes')}} <span style="color: red">(*)</span></label>
                            <div class="form-group">
                                <select id="classes" class="form-control" name="classes[]" required="" multiple="multiple">
                                    @if(!empty($classes))
                                    @foreach($classes as $cl)
                                        <option value="{{$cl->classes_id}}">{{$cl->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="blog_category" class="">Actions</label>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">{{ trans('cpvm-subject::language.buttons.create') }}</button>
                                    <a href="{!! route('cpvm.subject.subject.manage') !!}"
                                       class="btn btn-danger">{{ trans('cpvm-subject::language.buttons.discard') }}</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-sm-4 -->
                    </div>
                </form>
            </div>
        </div>
        <!--main content ends-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page js -->
    <script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/bootstrap-switch/js/bootstrap-switch.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/colorpicker/js/bootstrap-colorpicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/vendor/laravel-filemanager/js/lfm.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('/vendor/' . $group_name . '/' . $skin .'/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin .'/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <!--end of page js-->
    <script>
        $(document).ready(function() {
            var domain = "/admin/laravel-filemanager/";
            $("#lfm1").filemanager('image', {prefix: domain});
            $("#lfm2").filemanager('image', {prefix: domain});

            $('#mycp1,#mycp2').colorpicker();
            $('#classes').multiselect({
                buttonWidth: '100%',
                nonSelectedText: 'Chọn lớp',
                enableFiltering: true,
            });

            $('#form-add-subject').bootstrapValidator({
                feedbackIcons: {
                    // validating: 'glyphicon glyphicon-refresh',
                },
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Trường này không được bỏ trống'
                            },
                            stringLength: {
                                max: 200,
                                message: 'Trường này không được quá dài'
                            }
                        }
                    },
                    background_color: {
                        trigger: 'change keyup',
                        validators: {
                            notEmpty: {
                                message: 'Trường này không được bỏ trống'
                            }
                        }
                    },
                    icon: {
                        trigger: 'change keyup',
                        validators: {
                            notEmpty: {
                                message: 'Trường này không được bỏ trống'
                            }
                        }
                    },
                    background_color_mobile: {
                        trigger: 'change keyup',
                        validators: {
                            notEmpty: {
                                message: 'Trường này không được bỏ trống'
                            }
                        }
                    },
                    icon_mobile: {
                        trigger: 'change keyup',
                        validators: {
                            notEmpty: {
                                message: 'Trường này không được bỏ trống'
                            }
                        }
                    },
                    classes: {
                        trigger: 'change keyup',
                        validators: {
                            notEmpty: {
                                message: 'Trường này không được bỏ trống'
                            }
                        }
                    }
                }
            }); 
        });
    </script>
@stop
