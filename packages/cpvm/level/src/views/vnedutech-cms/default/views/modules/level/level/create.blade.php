@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('cpvm-level::language.titles.level.create') }}@stop

{{-- page styles --}}
@section('header_styles')
    {{-- <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css"/> --}}
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin .'/vendors/colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
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
                <form action="{{route('cpvm.level.level.add')}}" method="post" class="bf" id="form-add-level">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="row">
                        <div class="col-sm-4">
                            <label>{{trans('cpvm-level::language.label.level.name')}}<span style="color: red">(*)</span></label>
                            <div class="form-group {{ $errors->first('name', 'has-error') }}">
                                <input type="text" name="name" class="form-control" placeholder="{{trans('cpvm-level::language.placeholder.level.name')}}" autofocus="">
                                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
                            </div>
                            <label>{{trans('cpvm-level::language.label.level.color')}}<span style="color: red">(*)</span></label>
                            <div class="form-group {{ $errors->first('color', 'has-error') }}">
                                <input id="mycp1" type="text" name="color" class="form-control colorpicker-element" placeholder="{{trans('cpvm-level::language.placeholder.level.color')}}">
                                <span class="help-block">{{ $errors->first('color', ':message') }}</span>
                            </div>
                            <label>{{trans('cpvm-level::language.label.level.background')}}<span style="color: red">(*)</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                   <span class="input-group-btn">
                                     <a id="lfm1" data-input="thumbnail1" data-preview="holder1" class="btn btn-primary">
                                       <i class="fa fa-picture-o"></i> {{trans('cpvm-level::language.label.level.choise_image')}}
                                     </a>
                                   </span>
                                   <input type="text" name="background" id="thumbnail1" class="form-control">
                                 </div>
                                 <img id="holder1" style="margin-top:15px;max-height:100px;">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label>{{trans('cpvm-level::language.label.level.type')}}<span style="color: red">(*)</span></label>
                            <div class="form-group">
                                <div class="form-control">
                                <input type="radio" id="mamnon" name="type" value="1">
                                <label for="mamnon">{{trans('cpvm-level::language.label.level.mamnon')}}</label>
                                <input type="radio" id="khac" name="type" value="2" checked="checked">
                                <label for="khac">{{trans('cpvm-level::language.label.level.khac')}}</label>
                                </div>
                            </div>
                            <label>{{trans('cpvm-level::language.label.level.color_mobile')}}<span style="color: red">(*)</span></label>
                            <div class="form-group {{ $errors->first('color_mobile', 'has-error') }}">
                                <input id="mycp2" type="text" name="color_mobile" class="form-control" placeholder="{{trans('cpvm-level::language.placeholder.level.color_mobile')}}" autofocus="">
                                <span class="help-block">{{ $errors->first('color_mobile', ':message') }}</span>
                            </div>
                            <label>{{trans('cpvm-level::language.label.level.background_mobile')}}<span style="color: red">(*)</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                   <span class="input-group-btn">
                                     <a id="lfm2" data-input="thumbnail2" data-preview="holder2" class="btn btn-primary">
                                       <i class="fa fa-picture-o"></i> {{trans('cpvm-level::language.label.level.choise_image')}}
                                     </a>
                                   </span>
                                   <input type="text" name="background_mobile" id="thumbnail2" class="form-control">
                                 </div>
                                 <img id="holder2" style="margin-top:15px;max-height:100px;">
                            </div>
                        </div>
                        <!-- /.col-sm-8 -->
                        <div class="col-sm-2">
                            <div class="form-group col-xs-12">
                                <label for="blog_category" class="">Actions</label>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">{{ trans('cpvm-level::language.buttons.create') }}</button>
                                    <a href="{!! route('cpvm.level.level.manage') !!}"
                                       class="btn btn-danger">{{ trans('cpvm-level::language.buttons.discard') }}</a>
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
    <!--end of page js-->
    <script>
        $(document).ready(function() {
            var domain = "/admin/laravel-filemanager/";
            $("#lfm1").filemanager('image', {prefix: domain});
            $("#lfm2").filemanager('image', {prefix: domain});

            $('#mycp1,#mycp2').colorpicker();

            $('#form-add-level').bootstrapValidator({
                feedbackIcons: {
                    // validating: 'glyphicon glyphicon-refresh', vao db a xem cai
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
                    color: {
                        trigger: 'change keyup',
                        validators: {
                            notEmpty: {
                                message: 'Trường này không được bỏ trống'
                            }
                        }
                    },
                    background: {
                        trigger: 'change keyup',
                        validators: {
                            notEmpty: {
                                message: 'Trường này không được bỏ trống'
                            }
                        }
                    },
                    color_mobile: {
                        trigger: 'change keyup',
                        validators: {
                            notEmpty: {
                                message: 'Trường này không được bỏ trống'
                            }
                        }
                    },
                    background_mobile: {
                        trigger: 'change keyup',
                        validators: {
                            notEmpty: {
                                message: 'Trường này không được bỏ trống'
                            }
                        }
                    },
                }
            }); 
        });
    </script>
@stop
