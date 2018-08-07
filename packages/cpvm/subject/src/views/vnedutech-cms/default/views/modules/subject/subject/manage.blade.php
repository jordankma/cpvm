@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('cpvm-subject::language.titles.subject.manage') }}@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/datatables/css/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/tables.css') }}" rel="stylesheet" type="text/css"/>
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

    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left">
                        <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        {{ $title }}
                    </h4>
                    <div class="pull-right">
                        @if ($USER_LOGGED->canAccess('cpvm.subject.subject.create'))
                        <a href="{{ route('cpvm.subject.subject.create') }}" class="btn btn-sm btn-default">
                            <span class="glyphicon glyphicon-plus"></span> 
                            {{ trans('cpvm-subject::language.buttons.create') }}
                        </a>
                        @endif
                    </div>
                </div>
                <br/>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                            <tr class="filters">
                                <th class="fit-content">{{ trans('cpvm-subject::language.table.stt') }}</th>
                                <th>{{ trans('cpvm-subject::language.table.subject.name') }}</th>
                                <th>{{ trans('cpvm-subject::language.table.subject.background_color') }}</th>
                                <th style="width: 130px">{{ trans('cpvm-subject::language.table.subject.icon') }}</th>
                                <th>{{ trans('cpvm-subject::language.table.subject.background_color_mobile') }}</th>
                                <th style="width: 130px">{{ trans('cpvm-subject::language.table.subject.icon_mobile') }}</th>
                                <th>{{ trans('cpvm-subject::language.table.subject.classes') }}</th>
                                <th>{{ trans('cpvm-subject::language.table.action') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>    <!-- row-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>

    <script>
        $(function () {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('cpvm.subject.subject.data') }}',
                columns: [
                    { data: 'rownum', name: 'rownum' },
                    { data: 'name', name: 'name' },
                    { data: 'background_color', name: 'background_color'},
                    { data: 'icon', name: 'icon'},
                    { data: 'background_color_mobile', name: 'background_color_mobile'},
                    { data: 'icon_mobile', name: 'icon_mobile'},
                    { data: 'classes', name: 'classes'},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'fit-content'}
                ]
            });
            table.on('draw', function () {
                $('.livicon').each(function () {
                    $(this).updateLivicon();
                });
            });
        });

    </script>

    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"></div>
        </div>
    </div>
    <div class="modal fade" id="log" tabindex="-1" role="dialog" aria-labelledby="user_log_title"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"></div>
        </div>
    </div>
    <script>
        $(function () {
            $('body').on('hidden.bs.modal', '.modal', function () {
                $(this).removeData('bs.modal');
            });
        });
    </script>
@stop
