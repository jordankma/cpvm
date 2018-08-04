@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('adtech-core::titles.package.create') }}@stop

{{-- page styles --}}
@section('header_styles')
    <link href="{{ asset('/css/jquery-ui.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <!--end of page css-->
    <style type="text/css">
        .ui-widget-content
        {
            line-height: 30px;
        }
        .ui-widget-content .ui-state-focus
        {
            line-height: 30px;
            background: #EAEAEA;
            /*border: 1px solid #525763;*/
        }
    </style>
@stop


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

                <div class="panel-body">
                    <div class="bs-example">
                        <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                            <li class="{{ ($active == 0) ? 'active' : '' }}">
                                <a href="#new" data-toggle="tab">Create New</a>
                            </li>
                            <li class="{{ ($active == 1) ? 'active' : '' }}">
                                <a href="#tar" data-toggle="tab">Upload file tar</a>
                            </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div class="tab-pane fade active in" id="new">
                                <!-- method 1 -->
                                {!! Form::open(array('url' => route('adtech.core.package.add'), 'method' => 'post', 'class' => 'bf', 'id' => 'packageForm', 'files'=> false)) !!}
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label>Namespace</label>
                                        <div class="form-group {{ $errors->first('space', 'has-error') }}">
                                            {!! Form::select('space', array('Backend' => 'Backend', 'Frontend' => 'Frontend'), null, array('class' => 'form-control select2', 'style' => 'width: 100%')) !!}
                                            <span class="help-block">{{ $errors->first('space', ':message') }}</span>
                                        </div>
                                        <label>Package Name</label>
                                        <div class="form-group {{ $errors->first('package', 'has-error') }}">
                                            {!! Form::text('package', null, array('class' => 'form-control packageInput', 'autofocus'=>'autofocus','placeholder'=> trans('adtech-core::common.package.package_here'))) !!}
                                            <span class="help-block">{{ $errors->first('package', ':message') }}</span>
                                        </div>
                                        <label>Module Name</label>
                                        <div class="form-group {{ $errors->first('module', 'has-error') }}">
                                            {!! Form::text('module', null, array('class' => 'form-control', 'placeholder'=> trans('adtech-core::common.package.module_here'))) !!}
                                            <span class="help-block">{{ $errors->first('module', ':message') }}</span>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::hidden('domain_id', $domain_id) !!}
                                        </div>
                                    </div>
                                    <!-- /.col-sm-8 -->
                                    <div class="col-sm-4">
                                        <div class="form-group col-xs-12">
                                            <label for="blog_category" class="">Actions</label>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success">{{ trans('adtech-core::buttons.create') }}</button>
                                                <a href="{!! route('adtech.core.package.create') !!}"
                                                   class="btn btn-danger">{{ trans('adtech-core::buttons.discard') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-sm-4 --> </div>
                                {!! Form::close() !!}

                            </div>
                            <div class="tab-pane fade" id="tar">
                                <!-- method 2 -->
                                {!! Form::open(array('url' => route('adtech.core.package.add'), 'method' => 'post', 'class' => 'bf', 'id' => 'packageFormFile', 'files'=> true)) !!}
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label>Namespace</label>
                                        <div class="form-group {{ $errors->first('space', 'has-error') }}">
                                            {!! Form::select('space', array('Backend' => 'Backend', 'Frontend' => 'Frontend'), null, array('class' => 'form-control select2', 'style' => 'width: 100%')) !!}
                                            <span class="help-block">{{ $errors->first('space', ':message') }}</span>
                                        </div>
                                        {{--<label>Package Name</label>--}}
                                        {{--<div class="form-group {{ $errors->first('package', 'has-error') }}">--}}
                                            {{--{!! Form::text('package', null, array('class' => 'form-control input-lg packageInput', 'autofocus'=>'autofocus','placeholder'=> trans('adtech-core::common.package.package_here'))) !!}--}}
                                            {{--<span class="help-block">{{ $errors->first('package', ':message') }}</span>--}}
                                        {{--</div>--}}
                                        {{--<label>Module Name (One or multiple modules. E.g: module1, module2, module3)</label>--}}
                                        {{--<div class="form-group {{ $errors->first('module', 'has-error') }}">--}}
                                            {{--{!! Form::text('module', null, array('class' => 'form-control input-lg', 'autofocus'=>'autofocus','placeholder'=> trans('adtech-core::common.package.module_here'))) !!}--}}
                                            {{--<span class="help-block">{{ $errors->first('module', ':message') }}</span>--}}
                                        {{--</div>--}}
                                        <label>File Upload</label>
                                        <div class="form-group {{ $errors->first('file_upload', 'has-error') }}">
                                            {!! Form::file('file_upload'); !!}
                                            <span class="help-block">{{ $errors->first('file_upload', ':message') }}</span>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::hidden('check_file', 1) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::hidden('package', 'ignore') !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::hidden('module', 'ignore') !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::hidden('domain_id', $domain_id) !!}
                                        </div>
                                    </div>
                                    <!-- /.col-sm-8 -->
                                    <div class="col-sm-4">
                                        <div class="form-group col-xs-12">
                                            <label for="blog_category" class="">Actions</label>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success">{{ trans('adtech-core::buttons.create') }}</button>
                                                <a href="{!! route('adtech.core.package.create') !!}"
                                                   class="btn btn-danger">{{ trans('adtech-core::buttons.discard') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-sm-4 --> </div>
                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--main content ends-->
    </section>
@stop

{{-- page scripts --}}
@section('footer_scripts')
    <!-- begining of page js -->
    <!--edit package-->
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/select2/js/select2.js') }}"></script>
    <script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/js/pages/add_package.js') }}" type="text/javascript"></script>
    <!--end of page js-->
    <script>
        $(function () {
            $(".select2").select2({
                theme:"bootstrap"
            });
            var availableTags = $.parseJSON('{!! $arrPackage !!}');
            $( ".packageInput" ).autocomplete({
                source: availableTags,
                minLength: 3,
                select: function(e, ui) {

                }
            });
        });
    </script>
@stop
