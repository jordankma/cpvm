@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('adtech-core::titles.locale.manage') }}@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/datatables/css/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/tables.css') }}" rel="stylesheet" type="text/css"/>
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
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <div class="pull-left" style="margin-right: 5px">
                        <select class="form-control select2" id="select_domain">
                            @foreach($domains as $domain)
                                <option value="{{$domain->domain_id}}" {{ ($domain->domain_id == $domain_id) ? 'selected' : '' }}>{{$domain->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pull-right">
                        @if ($USER_LOGGED->canAccess('adtech.core.locale.create'))
                            <a href="{{ route('adtech.core.locale.create', ['domain_id' => $domain_id]) }}" class="btn btn-sm btn-default"><span
                                    class="glyphicon glyphicon-plus"></span> {{ trans('adtech-core::buttons.create') }}</a>
                        @endif
                    </div>
                </div>
                <br/>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                            <tr class="filters">
                                <th class="fit-content">{{ trans('adtech-core::common.sequence') }}</th>
                                <th>{{ trans('adtech-core::common.locale.name') }}</th>
                                <th>{{ trans('adtech-core::common.locale.alias') }}</th>
                                <th>{{ trans('adtech-core::common.locale.icon') }}</th>
                                <th>{{ trans('adtech-core::common.locale.status') }}</th>
                                <th style="width: 120px">{{ trans('adtech-core::common.created_at') }}</th>
                                <th style="width: 120px">{{ trans('adtech-core::common.update_at') }}</th>
                                <th class="fit-content">{{ trans('adtech-core::common.action') }}</th>
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
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/select2/js/select2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>

    <script>
        $(function () {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('adtech.core.locale.data', ['domain_id' => $domain_id]) }}',
                columns: [
                    { data: 'DT_Row_Index', name: 'locale_id' },
                    { data: 'name', name: 'name' },
                    { data: 'alias', name: 'alias' },
                    { data: 'icon', name: 'icon', className: 'fit-content'},
                    { data: 'status', name: 'status', className: 'fit-content'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'updated_at', name: 'updated_at'},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'fit-content'}
                ]
            });
            table.on('draw', function () {
                $('.livicon').each(function () {
                    $(this).updateLivicon();
                });
            });

            $("#select_domain").select2({
                theme:"bootstrap",
                placeholder:"select a domain"
            }).on("change", function (e) {
                var domain_id = this.options[this.selectedIndex].value;
                var url = window.location.pathname;

                if (url.indexOf('?') > -1){
                    url += '&domain_id=' + domain_id
                }else{
                    url += '?domain_id=' + domain_id
                }
                window.location.href = url;
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
