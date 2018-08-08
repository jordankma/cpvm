@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('cpvm-block::language.titles.block.update') }}@stop

{{-- page styles --}}
@section('header_styles')
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
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
                <form action="{{ route('cpvm.block.block.update') }}" method="post" id="form-add-block">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="block_id" value="{{ $block->block_id }}"/>
                    <div class="row">
                        <div class="col-sm-3">
                            <label>{{ trans('cpvm-block::language.label.block.name') }}</label>
                            <div class="form-group">
                                <input type="text" name="name" value="{{ $block->name }}" class="form-control" placeholder="{{ trans('cpvm-block::language.placeholder.block.name') }}">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label>{{ trans('cpvm-block::language.label.block.classes') }}</label>
                            <div class="form-group">
                                <select class="form-control" name="classes[]" id="classes" multiple="multiple" required="">
                                    @if(!empty($classes))
                                        @foreach($classes as $key => $value)
                                            <option value="{{$value->classes_id}}" @if(in_array($value->classes_id, $class_id)) selected="" @endif>{{$value->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label>{{ trans('cpvm-block::language.label.block.subject') }}</label>
                            <div class="form-group" id="data_subject">
                                <select class="form-control" name="subject_id[]" id="subject_id" multiple="multiple" required="">
                                    @if(!empty($subjects))
                                        @foreach($subjects as $key => $value)
                                            <option value="{{$value->subject_id}}" @if(in_array($value->subject_id, $subject_id)) selected="" @endif>{{$value->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <!-- /.col-sm-8 -->
                        <div class="col-sm-3">
                            <div class="form-group col-xs-12">
                                <label for="blog_category" class="">Actions</label>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">{{ trans('cpvm-block::language.buttons.create') }}</button>
                                    <a href="{!! route('cpvm.block.block.create') !!}"
                                       class="btn btn-danger">{{ trans('cpvm-block::language.buttons.discard') }}</a>
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
    <script src="{{ asset('/vendor/' . $group_name . '/' . $skin .'/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin .'/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <!--end of page js-->
    <script>
        $(document).ready(function() {
            $('#classes').multiselect({
                buttonWidth: '100%',
                nonSelectedText: 'Chọn lớp',
                enableFiltering: true,
            });
            $('#subject_id').multiselect({
                buttonWidth: '100%',
                nonSelectedText: 'Chọn lớp',
                enableFiltering: true,
            });
            $('#form-add-block').bootstrapValidator({
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
                    }
                }
            });
            $('body').on('change', '#classes', function (e) {
                e.preventDefault();
                var class_id = $(this).val();
                $.ajax({
                    url: '{{route('cpvm.subject.subject.list')}}',
                    type: 'GET',
                    cache: false,
                    data: {class:class_id},
                    success:function (data) {
                        var subject = JSON.parse(data);
                        var html = '';
                        $.each(subject, function (sub_id, sub_name) {
                            html += '<option value="' + sub_id + '">' + sub_name + '</option>';
                        });
                        $('#data_subject').html('<select name="subject_id[]" id="subject_id" class="form-control" multiple="multiple"> ' + html + '</select>');
                        $('#subject_id').multiselect({
                            buttonWidth: '100%',
                            nonSelectedText: 'Chọn lớp',
                            enableFiltering: true,
                        });
                    }
                });
            });
        });
    </script>
@stop
