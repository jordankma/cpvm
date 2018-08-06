@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('cpvm-classes::language.titles.classes.create') }}@stop

{{-- page styles --}}
@section('header_styles')
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin .'/vendors/colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet" type="text/css"/>
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
                <form action="{{ route('cpvm.classes.classes.add') }}" method="post" class="bf" id="form-add-classes">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <div class="row">
                        <div class="col-sm-4">
                            <label>{{trans('cpvm-classes::language.label.classes.name')}} <span style="color: red">(*)</span></label>
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="{{trans('cpvm-classes::language.placeholder.classes.name')}}">
                            </div>
                            <label>{{trans('cpvm-classes::language.label.classes.type')}}</label>
                            <div class="form-group">
                                <div class="form-control" style="border: none;">
                                <input type="radio" id="class" name="type" value="class" checked="checked">
                                <label for="class">{{trans('cpvm-classes::language.label.classes.class')}}</label>
                                <input type="radio" id="exam" name="type" value="exam">
                                <label for="exam">{{trans('cpvm-classes::language.label.classes.exam')}}</label>
                                </div>
                            </div>
                            <label>{{trans('cpvm-classes::language.label.classes.level')}}</label>
                            <div class="form-group">
                                <select class="form-control" name="level">
                                @if(!empty($levels))
                                @foreach($levels as $level)
                                    <option value="{{$level->level_id}}">{{$level->name}}</option>
                                @endforeach
                                @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label>{{trans('cpvm-classes::language.label.classes.priority')}}</label>
                            <div class="form-group">
                                <input type="number" name="priority" class="form-control" min="0" placeholder="{{trans('cpvm-classes::language.placeholder.classes.priority')}}">
                            </div>
                            <label>{{trans('cpvm-classes::language.label.classes.color_mobile')}}</label>
                            <div class="form-group">
                                <input id="mycp" type="text" name="color_mobile" class="form-control" placeholder="{{trans('cpvm-classes::language.placeholder.classes.color_mobile')}}" autofocus="">
                            </div>
                            <label>{{trans('cpvm-classes::language.label.classes.background_mobile')}}<span style="color: red">(*)</span></label>
                            <div class="form-group">
                                <div class="input-group">
                                   <span class="input-group-btn">
                                     <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                       <i class="fa fa-picture-o"></i> {{trans('cpvm-classes::language.label.classes.choise_image')}}
                                     </a>
                                   </span>
                                   <input type="text" name="background_mobile" id="thumbnail" class="form-control">
                                 </div>
                                 <img id="holder" style="margin-top:15px;max-height:100px;">
                            </div>
                        </div>
                        <!-- /.col-sm-8 -->
                        <div class="col-sm-4">
                            <div class="form-group col-xs-12">
                                <label for="blog_category" class="">Actions</label>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">{{ trans('cpvm-classes::language.buttons.create') }}</button>
                                    <a href="{!! route('cpvm.classes.classes.create') !!}"
                                       class="btn btn-danger">{{ trans('cpvm-classes::language.buttons.discard') }}</a>
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
            $("#lfm").filemanager('image', {prefix: domain});
            $('#mycp').colorpicker();
            $('#form-add-classes').bootstrapValidator({
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
                    background_mobile: {
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
                    priority: {
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
