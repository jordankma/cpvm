@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('adtech-core::titles.permission.manage') }}@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/iCheck/css/all.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/iCheck/css/line/line.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/tables.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/toastr/css/toastr.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/toastr.css') }}" rel="stylesheet"/>
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>{{ $title }} {{ $titleP }}</h1>
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
                    <h4 class="panel-title pull-left"><i class="livicon" data-name="users" data-size="16"
                                                         data-loop="true" data-c="#fff" data-hc="white"></i>
                        {{ $title }}
                    </h4>
                    <div class="pull-right col-md-3">
                        <div class="col-md-6" style="padding: 0px !important;">
                            <select class="form-control select2" id="select_package">
                                <option value=""> Select one package </option>
                                @foreach($packages as $package)
                                    <option value="{{$package->package}}">{{$package->package}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control select2" id="select_module" disabled>
                                <option value=""> <<<<< Select </option>
                            </select>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                            <tr class="filters">
                                {{--<th>{{ trans('adtech-core::common.route.package') }}</th>--}}
                                {{--<th>{{ trans('adtech-core::common.route.module') }}</th>--}}
                                <th>Chức năng</th>
                                <th class="col-md-9">
                                    <table>
                                        <tr>
                                            <th class="col-md-3">Xem danh sách</th>
                                            <th class="col-md-3">Thêm mới</th>
                                            <th class="col-md-3">Cập nhật</th>
                                            <th class="col-md-3">Xoá</th>
                                        </tr>
                                    </table>
                                </th>
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
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/iCheck/js/icheck.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>

    <script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/iCheck/js/icheck.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/toastr/js/pages/ui-toastr.js') }}"></script>
    <script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/bootstrap-switch/js/bootstrap-switch.js') }}" type="text/javascript"></script>
    <script>
        $(function () {
            var package_id = $("#select_package").val();
            var module_id = $("#select_module").val();

            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                {{--ajax: '{{ route('adtech.core.permission.data') }}?object_type={{ $objectType }}&object_id={{ $objectId }}',--}}
                ajax: {
                    url: '{{ route('adtech.core.permission.data') }}',
                    data: function(d) {
                        d.object_type = '{{ $objectType }}';
                        d.object_id = '{{ $objectId }}';
                        d.package_id = package_id;
                        d.module_id = module_id;
                    }
                },
                columns: [
                    // { data: 'package', name: 'package' },
                    // { data: 'module', name: 'module' },
                    { data: 'controller', name: 'controller' },
                    { data: 'method', name: 'method' }
                ]
            });
            table.on('draw', function () {
                $('.livicon').each(function () {
                    $(this).updateLivicon();
                });

                $('input[type="checkbox"].square').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                    increaseArea: '20%'
                });

                $('input[type="checkbox"].allow_permission').bootstrapSwitch({
                    onSwitchChange:function(event, state) {
                        var routeList = '';
                        if (this.hasAttribute("data-name")) { routeList += $(this).attr("data-name") + ','; }
                        if (this.hasAttribute("data-name1")) { routeList += $(this).attr("data-name1") + ','; }
                        if (this.hasAttribute("data-name2")) { routeList += $(this).attr("data-name2") + ','; }
                        if (this.hasAttribute("data-name3")) { routeList += $(this).attr("data-name3") + ','; }
                        if (this.hasAttribute("data-name4")) { routeList += $(this).attr("data-name4") + ','; }
                        if (this.hasAttribute("data-name5")) { routeList += $(this).attr("data-name5") + ','; }
                        if (this.hasAttribute("data-name6")) { routeList += $(this).attr("data-name6") + ','; }
                        if (this.hasAttribute("data-name7")) { routeList += $(this).attr("data-name7") + ','; }
                        if (this.hasAttribute("data-name8")) { routeList += $(this).attr("data-name8") + ','; }
                        if (this.hasAttribute("data-name9")) { routeList += $(this).attr("data-name9") + ','; }
                        var dataAjax = { object_type : '{{ $objectType }}', object_id : '{{ $objectId }}', allow : (state) ? 1 : 0, route_name : routeList};
                        $.ajax({
                            url: "{{ route('adtech.core.permission.set') }}",
                            method: "POST",
                            data: dataAjax
                        }).done(function( response ) {
                            if (typeof response.type !== 'undefined') {
                                if (response.type === 'success') {
                                    toastr.success(response.msg, response.group);
                                } else {
                                    toastr.error(response.msg, response.group);
                                }
                            }
                        });
                    }
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
                                $("#select_module").append("<option value=''>All Module</option>");
                                for( var i = 0; i<len; i++){
                                    var id = response[i]['id'];
                                    var name = response[i]['name'];
                                    $("#select_module").append("<option value='" + id + "'>" + name + "</option>");
                                }
                                $("#select_module").prop("disabled", false);

                                package_id = $("#select_package").val();
                                module_id = $("#select_module").val();
                                table.ajax.reload();
                            } else {
                                $("#select_module").append("<option value='null'> <<<<< Select </option>");
                                $("#select_module").prop("disabled", true);

                                package_id = null;
                                module_id = null;
                                table.ajax.reload();
                            }
                        }
                    });
                });

                $("#select_module").change(function(){
                    var new_package_id = $("#select_package").val();
                    var new_module_id = $("#select_module").val();
                    if (new_package_id != package_id || new_module_id != module_id) {
                        package_id = new_package_id;
                        module_id = new_module_id;

                        table.ajax.reload();
                    }
                });

                $(".btnSaveControllerName").click(function () {
                    var name = this.dataset.name;
                    var name_value = document.getElementsByName(name)[0].value;
                    $.ajax({
                        url: '{{ route("adtech.core.permission.set-name") }}',
                        type: "GET",
                        dataType: "json",
                        data: { name : name, name_value : name_value },
                        success: function(response){
                            if (typeof response.type !== 'undefined') {
                                if (response.type === 'success') {
                                    toastr.success(response.msg, response.group);
                                } else {
                                    toastr.error(response.msg, response.group);
                                }
                            }
                        },
                        error: function() {
                            toastr.error('Set Name Failed', 'Controller Name');
                        }
                    });
                })
            });
        });

    </script>
@stop
