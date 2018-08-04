@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('adtech-core::titles.user.manage') }}@stop

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
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li class="active">{{ $title }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"><i class="livicon" data-name="users" data-size="16"
                                                     data-loop="true" data-c="#fff" data-hc="white"></i>
                    {{ $title }}
                </h4>
                <div class="pull-right">
                    @if ($USER_LOGGED->canAccess('adtech.core.user.create'))
                        <a href="{{ route('adtech.core.user.create') }}" class="btn btn-sm btn-default"><span
                                class="glyphicon glyphicon-plus"></span> {{ trans('adtech-core::buttons.create') }}</a>
                    @endif
                </div>
            </div>
            <br />
            <div class="panel-body">
                <div class="table-responsive">
                <table class="table table-bordered width100" id="table">
                    <thead>
                        <tr class="filters">
                            <th class="fit-content">{{ trans('adtech-core::common.sequence') }}</th>
                            <th>{{ trans('adtech-core::common.user.email') }}</th>
                            <th>{{ trans('adtech-core::common.user.contact_name') }}</th>
                            <th>{{ trans('adtech-core::common.user.role') }}</th>
                            <th style="width: 120px">{{ trans('adtech-core::common.created_at') }}</th>
                            <th style="width: 120px">{{ trans('adtech-core::common.update_at') }}</th>
                            <th class="fit-content">{{ trans('adtech-core::common.user.status') }}</th>
                            <th>{{ trans('adtech-core::common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
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
    $(function() {
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('adtech.core.user.data') !!}',
            columns: [
                { data: 'DT_Row_Index', name: 'user_id' },
                { data: 'email', name: 'email' },
                { data: 'contact_name', name: 'contact_name' },
                { data: 'role_name', name: 'role_name' },
                { data: 'created_at', name:'created_at'},
                { data: 'updated_at', name:'updated_at'},
                { data: 'status', name: 'status' },
                {data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'fit-content'}
            ],
            language: $.parseJSON('{!! $DATATABLE_TRANS !!}')
        });
        table.on( 'draw', function () {
            $('.livicon').each(function(){
                $(this).updateLivicon();
            });
        } );
    });

</script>

<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
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
