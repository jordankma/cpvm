@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('adtech-core::titles.package.manage') }}@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/iCheck/css/all.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/iCheck/css/line/line.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/datatables/css/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/modal/css/component.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/tables.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/toastr/css/toastr.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/toastr.css') }}" rel="stylesheet"/>

    <style type="text/css">
        .selected {
            background: #EAEAEA !important;
        }
    </style>
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
                    <div class="pull-left">
                        <select class="form-control select2" id="select_domain">
                            @foreach($domains as $domain)
                                <option value="{{$domain->domain_id}}" {{ ($domain->domain_id == $domain_id) ? 'selected' : '' }}>{{$domain->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($USER_LOGGED->canAccess('adtech.core.package.create'))
                    <div class="pull-right" id="btnToolbarPackage">
                        <a class="btn btn-sm btn-default" data-href="#search_package" href="#search_package" data-toggle="modal">
                            <span class="glyphicon glyphicon-search"></span> {{ trans('adtech-core::buttons.search') }}
                        </a>
                        <a href="{{ route('adtech.core.package.create', ['id' => $domain_id]) }}" class="btn btn-sm btn-default">
                            <span class="glyphicon glyphicon-plus"></span> {{ trans('adtech-core::buttons.create_package') }}
                        </a>
                    </div>
                    @endif
                </div>
                <br/>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <thead>
                            <tr class="filters">
                                <th style="width: 40px">{{ trans('adtech-core::common.detail') }}</th>
                                {{--<th>{{ trans('adtech-core::common.id') }}</th>--}}
                                <th>{{ trans('adtech-core::common.package.space') }}</th>
                                <th>{{ trans('adtech-core::common.package.package') }}</th>
                                <th>{{ trans('adtech-core::common.package.module') }}</th>
                                <th style="width: 120px">{{ trans('adtech-core::common.created_at') }}</th>
                                <th style="width: 120px">{{ trans('adtech-core::common.update_at') }}</th>
                                <th class="fit-content">{{ trans('adtech-core::common.package.status') }}</th>
                                <th>{{ trans('adtech-core::common.action') }}</th>
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
    <script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/toastr/js/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/modal/js/classie.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/iCheck/js/icheck.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/select2/js/select2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/ajax-process/jquery.ajax-progress.js') }}"></script>

    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="package_delete_confirm_title"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"></div>
        </div>
    </div>
    <div class="modal fade" id="status_confirm" tabindex="-1" role="dialog" aria-labelledby="package_status_confirm_title"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"></div>
        </div>
    </div>
    <div class="modal fade" id="public_confirm" tabindex="-1" role="dialog" aria-labelledby="package_public_confirm_title"
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
    <div class="modal fade in" id="search_package" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Add Package To Domain</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Package</h4>
                            <p>
                                <select class="form-control select2" id="select_package">
                                    <option value="null"> Select one package </option>
                                    @foreach($packages as $package)
                                        <option value="{{$package->package}}">{{$package->package}}</option>
                                    @endforeach
                                </select>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h4>Module</h4>
                            <p>
                                <select class="form-control select2" id="select_module" disabled>
                                    <option value="null"> <<<<< Select </option>
                                </select>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
                    <button type="button" class="btn btn-primary" id="btnAddPackage" disabled>Add package</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            var selected = 0;
            var selectedArr = [];
            var routeDownload = '{{ route('adtech.core.package.download') }}';
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('adtech.core.package.data', ['id' => $domain_id]) }}',
                columns: [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "searchable":     false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    // { data: 'package_id', name: 'package_id' },
                    { data: 'space', name: 'space' },
                    { data: 'package', name: 'package' },
                    { data: 'module', name: 'module' },
                    { data: 'created_at', name: 'created_at'},
                    { data: 'updated_at', name: 'updated_at'},
                    { data: 'status', name: 'status', className: 'fit-content'},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'fit-content'}
                ]
            });
            table.on('draw', function () {
                $('.livicon').each(function () {
                    $(this).updateLivicon();
                });
            });
            //
            var htmlBtnToolbar = document.getElementById('btnToolbarPackage').innerHTML;
            $('#table tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }

                $('input[type="checkbox"].square').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                    increaseArea: '20%'
                });
            }).on( 'click', 'tr', function () {
                var id = this.id;
                var index = $.inArray(id, selectedArr);
                if ( index === -1 ) {
                    selectedArr.push( id );
                } else {
                    selectedArr.splice( index, 1 );
                }

                $(this).toggleClass('selected');
                selected = table.rows('.selected').data().length;
                var moreHtml = '<a class="btn btn-sm btn-default" href="' + routeDownload + '?packages=' + selectedArr + '">\n' +
                '                            <span class="glyphicon glyphicon-download"></span>\n' +
                '                            ' + selected + ' packages\n' +
                '                        </a>';
                if (selected > 0) {
                    document.getElementById('btnToolbarPackage').innerHTML = moreHtml + htmlBtnToolbar;
                } else {
                    document.getElementById('btnToolbarPackage').innerHTML = htmlBtnToolbar;
                }
            });

            /* Formatting function for row details - modify as you need */
            function format ( d ) {
                // `d` is the original data object for the row
                return d.methods;
            }

            $("#select_domain").select2({
                theme:"bootstrap",
                placeholder:"select a domain"
            }).on("change", function (e) {
                var domain_id = this.options[this.selectedIndex].value;
                var url = window.location.pathname;
                if (url.indexOf('?') > -1){
                    url += '&id=' + domain_id
                }else{
                    url += '?id=' + domain_id
                }
                window.location.href = url;
            });

            $('body').on('hidden.bs.modal', '.modal', function () {
                $(this).removeData('bs.modal');
            });

            $("#select_package").change(function(){
                var package_name = $(this).val();

                $.ajax({
                    url: '{{ route('adtech.core.package.search-package') }}',
                    type: 'post',
                    data: { package_name : package_name },
                    dataType: 'json',
                    success:function(response){

                        var len = response.length;
                        $("#select_module").empty();
                        if (len > 0) {
                            for( var i = 0; i<len; i++){
                                var id = response[i]['id'];
                                var name = response[i]['name'];
                                $("#select_module").append("<option value='" + id + "'>" + name + "</option>");
                            }
                            $("#select_module").prop("disabled", false);
                            $("#btnAddPackage").prop("disabled", false);
                        } else {
                            $("#select_module").append("<option value='null'> <<<<< Select </option>");
                            $("#select_module").prop("disabled", true);
                            $("#btnAddPackage").prop("disabled", true);
                        }
                    }
                });
            });

            $("#btnAddPackage").click(function () {
                var package_id = $("#select_module").val();
                var domain_id = $("#select_domain").val();
                $.ajax({
                    url: '{{ route('adtech.core.package.add-package') }}',
                    type: 'post',
                    data: {package_id: package_id, domain_id: domain_id},
                    dataType: 'json',
                    success: function (response) {
                        if (typeof response.type !== 'undefined') {
                            if (response.type === 'success') {
                                toastr.success(response.msg, response.group);
                                table.ajax.reload();
                            } else {
                                toastr.error(response.msg, response.group);
                            }
                        }
                    }
                });
            });

        });
    </script>
@stop
