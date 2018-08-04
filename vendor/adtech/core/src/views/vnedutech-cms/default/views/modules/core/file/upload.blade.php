@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('adtech-core::titles.file.manage') }}@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>{{ $title }}</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('backend.homepage') }}"> <i class="livicon" data-name="home" data-size="16"
                                                             data-color="#000"></i>
                    {{ trans('adtech-core::labels.home') }}
                </a>
            </li>
            <li class="active"><a href="#">{{ $title }}</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content paddingleft_right15">
        <!--main content-->
        <div class="row">
            <div class="panel panel-primary">

                <div class="panel-body">
                    <div class="bs-example">

                        <!-- method 2 -->
                        <div class="row">
                            <div class="col-sm-8">
                                <label>File Upload</label>
                                    <input type="file" id="i_file" value="">
                            </div>
                            <!-- /.col-sm-8 -->
                            <div class="col-sm-4">
                                <div class="form-group col-xs-12">
                                    {!! Form::open(array('url' => route('adtech.core.file.upload-test'), 'method' => 'post', 'class' => 'bf', 'id' => 'uploadFormFile', 'files'=> true)) !!}
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                                    <div class="form-group">
                                        {!! Form::hidden('file_real_path', null, ['id' => 'file_real_path_id']) !!}
                                    </div>

                                    <label for="blog_category" class="">Actions</label>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">{{ trans('adtech-core::buttons.create') }}</button>
                                        <a href="{!! route('adtech.core.file.upload-test') !!}"
                                           class="btn btn-danger">{{ trans('adtech-core::buttons.discard') }}</a>
                                    </div>

                                    {!! Form::close() !!}
                                </div>
                            </div>
                            <!-- /.col-sm-4 --> </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('footer_scripts')
    <script>
        $('#i_file').change( function(event) {
            var tmppath = URL.createObjectURL(event.target.files[0]);
            $("#file_real_path_id").attr('value', tmppath);
        });
    </script>
@stop
