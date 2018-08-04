@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('adtech-core::titles.role.update') }}@stop

{{-- page styles --}}
@section('header_styles')
    {{--<link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css"/>--}}
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <!--end of page css-->
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>{{ $title }}</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('backend.homepage') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
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
                {!! Form::model($role, ['url' => route('adtech.core.role.update'), 'method' => 'put', 'class' => 'bf', 'files'=> true]) !!}
                <div class="row">
                    <div class="col-sm-8">
                        <label>Role Name</label>
                        <div class="form-group {{ $errors->first('name', 'has-error') }}">
                            {!! Form::text('name', null, array('class' => 'form-control', 'autofocus'=>'autofocus', 'placeholder'=>trans('adtech-core::common.role.name_here'))) !!}
                            <span class="help-block">{{ $errors->first('name', ':message') }}</span>
                        </div>

                        <div class="form-group">
                            {!! Form::hidden('role_id') !!}
                        </div>

                        {{--<label>Role Sort</label>--}}
                        {{--<div class="form-group {{ $errors->first('sort', 'has-error') }}">--}}
                            {{--{!! Form::number('sort', null, array('min' => 0, 'max' => 99, 'class' => 'form-control', 'placeholder'=>trans('adtech-core::common.role.sort_here'))) !!}--}}
                            {{--<span class="help-block">{{ $errors->first('sort', ':message') }}</span>--}}
                        {{--</div>--}}

                        {{--<div class="row">--}}
                            {{--<div class="col-sm-2">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label>Status</label>--}}
                                    {{--<div class="form-group">--}}
                                        {{--{!! Form::checkbox('status', null, ($role->status == 1) ? true : false, array('data-size'=> 'mini')) !!}--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-2">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label>Permission Lock</label>--}}
                                    {{--<div class="form-group">--}}
                                        {{--{!! Form::checkbox('permission_locked', null, ($role->permission_locked == 1) ? true : false, array('data-size'=> 'mini')) !!}--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                    <!-- /.col-sm-8 -->
                    <div class="col-sm-4">
                        <label for="blog_category" class="">Actions</label>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">{{ trans('adtech-core::buttons.save') }}</button>
                            <a href="{!! route('adtech.core.role.manage') !!}"
                               class="btn btn-danger">{{ trans('adtech-core::buttons.discard') }}</a>
                        </div>
                    </div>
                    <!-- /.col-sm-4 --> </div>
                <!-- /.row -->
                {!! Form::close() !!}
            </div>
            @if ( $errors->any() )
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
        <!--main content ends-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page js -->
    {{--<script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/bootstrap-switch/js/bootstrap-switch.js') }}" type="text/javascript"></script>--}}
    {{--<script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>--}}
    {{--<script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/js/pages/add_role.js') }}" type="text/javascript"></script>--}}
    <script>
        $(function () {
            // $("[name='permission_locked'], [name='status']").bootstrapSwitch();
        })
    </script>
@stop
