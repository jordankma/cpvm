@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('adtech-core::titles.menu.update') }}@stop

{{-- page styles --}}
@section('header_styles')
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/icon.css') }}" rel="stylesheet" type="text/css">
    <style type="text/css">
        .select2 {
            width: 100% !important;
        }
    </style>
    <!--end of page css-->
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
    <!--section ends-->
    <section class="content paddingleft_right15">
        <!--main content-->
        <div class="row">
            <div class="the-box no-border">
                {!! Form::model($menu, ['url' => route('adtech.core.menu.update'), 'method' => 'put', 'class' => 'bf', 'files'=> true]) !!}
                <div class="row">
                    <div class="col-sm-8">

                        <label>Menu cha</label>
                        <div class="form-group {{ $errors->first('parent', 'has-error') }}">
                            <select class="form-control select2" title="Select parent..." name="parent"
                                    id="parent">
                                <option value="0">Root menu</option>
                                @foreach($menus as $menuItem)
                                    <option value="{{ $menuItem->menu_id }}" {{ ($menuItem->menu_id == $menu->parent) ? ' selected="selected"' : '' }}>{{ str_repeat('---', $menuItem->level) . $menuItem->name }}</option>
                                @endforeach
                            </select>
                            <span class="help-block">{{ $errors->first('parent', ':message') }}</span>
                        </div>

                        <label>Chức năng</label>
                        <div class="form-group {{ $errors->first('route_name', 'has-error') }}">
                            <select class="form-control select2" title="Select route name..." name="route_name"
                                    id="route_name" onchange="getTypeView(this);">
                                <option value="#" {{ $menu->route_name == '#' ? ' selected="selected"' : '' }}>No Link</option>
                                @foreach($listRouteName as $route_name => $routeName)
                                    <option value="{{ $route_name }}" {{ ($route_name == $menu->route_name) ? ' selected="selected"' : '' }}>{{ $routeName }}</option>
                                @endforeach
                            </select>
                            <span class="help-block">{{ $errors->first('route_name', ':message') }}</span>
                        </div>

                        <div id="boxParams" style="{{ $checkDisplay }}">
                            <label id="lbl_category">Chọn danh mục</label>
                            <div class="form-group">
                                <select class="form-control select2" title="Select category name..." name="route_params" id="category_name">
                                    @foreach($listCate as $category)
                                        @if (isset($category['news_cat_id']))
                                        <option value="{{ $category->news_cat_id }}" {{ ($category->news_cat_id == $menu->route_params) ? ' selected="selected"' : '' }}>{{ $category->name }}</option>
                                        @endif
                                        @if (isset($category['document_cate_id']))
                                            <option value="{{ $category['alias'] }}" {{ ($category['alias'] == $menu->route_params) ? ' selected="selected"' : '' }}>{{ $category['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="boxParams_detail" style="{{ $checkDisplayDetail }}">
                            <label>Chọn chi tiết</label>
                            <div class="form-group input-group">
                                {!! Form::text('route_params_detail', $route_params, array('class' => 'form-control', 'placeholder'=> trans('adtech-core::common.menu.id_detail_here'))) !!}
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="changeGroupType()">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::hidden('typeData', null, ['id' => 'btn_typeData']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::hidden('typeView', null, ['id' => 'btn_typeView']) !!}
                        </div>

                        @if ($type == 0)
                        <label>Nhóm</label>
                        <div class="form-group input-group {{ $errors->first('group', 'has-error') }}">
                            {!! Form::text('group', null, array('class' => 'form-control', 'id' => 'group_name_txt', 'disabled' => true, 'placeholder'=> trans('adtech-core::common.menu.group_name_here'))) !!}
                            <select class="form-control" title="Select group name..." name="group" id="group_name_select" disabled="true" style="display: none">
                                @if (count($menusGroups) == 0)
                                    <option value="Hệ thống">Hệ thống</option>
                                @endif

                                @foreach($menusGroups as $groupName)
                                    <option value="{{ $groupName->group }}" {{ ($groupName->group == $menu->group) ? 'selected' : '' }}>{{ $groupName->group }}</option>
                                @endforeach
                            </select>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="changeGroupType()">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </span>
                        </div>
                        @endif

                        <label>Tên menu</label>
                        <div class="form-group {{ $errors->first('name', 'has-error') }}">
                            {!! Form::text('name', null, array('class' => 'form-control', 'autofocus'=>'autofocus', 'placeholder'=>trans('adtech-core::common.menu.name_here'))) !!}
                            <span class="help-block">{{ $errors->first('name', ':message') }}</span>
                        </div>

                        <label>Alias</label>
                        <div class="form-group {{ $errors->first('alias', 'has-error') }}">
                            {!! Form::text('alias', null, array('class' => 'form-control', 'placeholder'=>trans('adtech-core::common.menu.alias_here'))) !!}
                            <span class="help-block">{{ $errors->first('alias', ':message') }}</span>
                        </div>

                        <label>Sắp xếp</label>
                        <div class="form-group {{ $errors->first('sort', 'has-error') }}">
                            {!! Form::number('sort', null, array('min' => 0, 'max' => 99,'class' => 'form-control', 'placeholder'=> trans('adtech-core::common.menu.sort_here'))) !!}
                            <span class="help-block">{{ $errors->first('sort', ':message') }}</span>
                        </div>

                        <label>Icon</label>
                        <div class="input-group {{ $errors->first('icon', 'has-error') }}">
                            <span class="input-group-btn">
                             <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                               <i class="fa fa-picture-o"></i> Choose
                             </a>
                           </span>
                            {!! Form::text('icon', null, array('class' => 'form-control', 'id' => 'thumbnail', 'placeholder'=>trans('adtech-core::common.menu.icon_here'))) !!}
                            <span class="help-block">{{ $errors->first('icon', ':message') }}</span>
                        </div>

                        <div class="row" style="overflow: auto; height: 200px">
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="adjust" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="alarm" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="apple" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="balance" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="ban" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="globe" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="barchart" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="beer" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="bell" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="biohazard" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="bolt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="bookmark" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="briefcase" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="brush" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="bug" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="calendar" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="camcoder" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="camera" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="camera-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="car" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="cellphone" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="certificate" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="check" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="check-circle" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="check-circle-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="checked-off" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="checked-on" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="circle" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="circle-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="clapboard" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="clip" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="clock" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="cloud" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="cloud-bolts" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="cloud-rain" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="cloud-snow" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="cloud-sun" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="cloud-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="cloud-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="code" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="comment" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="comments" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="compass" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="credit-card" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="css3" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="dashboard" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="desktop" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="doc-landscape" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="doc-portrait" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="download" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="download-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="drop" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="edit" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="eye-close" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="eye-open" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="film" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="filter" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="fire" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="flag" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="gear" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="gears" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="ghost" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="gift" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="glass" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="globe" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="hammer" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="heart" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="heart-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="help" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="home" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="html5" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="image" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="inbox" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="info" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="key" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="calender" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="lab" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="laptop" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="leaf" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="legal" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="linechart" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="link" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="location" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="lock" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="magic" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="magic-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="magnet" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="mail" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="mail-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="map" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="minus" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="minus-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="money" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="more" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="move" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="music" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>

                            <!--jareena -->

                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="notebook" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="pacman" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="pen" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="pencil" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="phone" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="piechart" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="piggybank" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="plane-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="plane-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="plus" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="plus-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="presentation" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="printer" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="qrcode" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="question" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="quote-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="quote-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="remove" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="remove-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="remove-circle" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="responsive" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="responsive-menu" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="retweet" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="rocket" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="sandglass" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="scissors" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="screenshot" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="search" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="settings" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="share" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="shield" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="shopping-cart" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="shuffle" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="sign-in" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="sign-out" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="signal" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="sitemap" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="sky-dish" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="sort" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="sort-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="sort-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="star-empty" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="star-full" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="star-half" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="stopwatch" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="sun" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="tablet" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="tag" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="tags" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="tasks" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="thermo-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="thermo-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="thumbs-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="thumbs-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="trash" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="tree" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="trophy" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="truck" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="umbrella" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="unlock" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="upload" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="upload-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="user" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="users" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="warning" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="warning-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="wrench" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="zoom-in" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="zoom-out" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>

                            <!--Arrows and Directional Icons -->
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="angle-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="angle-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="angle-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="angle-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="angle-double-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="angle-double-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="angle-double-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="angle-double-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="angle-wide-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="arrow-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="arrow-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="arrow-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="arrow-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="arrow-circle-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="arrow-circle-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="arrow-circle-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="arrow-circle-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="caret-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="caret-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="caret-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="caret-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="chevron-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="chevron-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="chevron-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="chevron-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="exchange" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="external-link" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="hand-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="hand-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="hand-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="hand-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="recycled" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="redo" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="refresh" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="resize-big" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="resize-big-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="resize-horizontal" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="resize-horizontal-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="resize-small" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="resize-small-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="resize-vertical" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="resize-vertical-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="rotate-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="rotate-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="undo" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>

                            <!-- Text Control Icons -->

                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="align-center" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="align-justify" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="align-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="align-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="bold" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="columns" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="font" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="italic" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="list" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="list-ol" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="list-ul" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="table" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="underline" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>

                            <!-- Video Player Icons -->

                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="video-play" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="video-play-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="video-stop" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="video-pause" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="video-eject" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="video-backward" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="video-step-backward" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="video-fast-backward" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="video-forward" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="video-step-forward" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="video-fast-forward" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="screen-full" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="screen-full-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="screen-small" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="screen-small-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="speaker" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <!-- Socials, OSs, Browsers, JS libs and others Icons (not animated) - Just Static    -->
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="dropbox" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="facebook" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="facebook-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="flickr" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="flickr-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="google-plus" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="google-plus-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="linkedin" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="linkedin-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="myspace" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="pinterest" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="pinterest-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="rss" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="skype" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="stumbleupon" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="stumbleupon-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="twitter" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="twitter-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="wordpress" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="wordpress-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="youtube" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="android" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="ios" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="windows" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="windows8" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="chrome" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="firefox" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="ie" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="safari" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="bootstrap" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="jquery" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="raphael" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="paypal" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="livicon" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <!--Spinner Icons -->
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="spinner-one" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="spinner-two" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="spinner-three" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="spinner-four" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="spinner-five" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="spinner-six" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="spinner-seven" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <!-- Morphs Icons-->

                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="morph-c-s" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="morph-c-o" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="morph-s-c" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="morph-s-o" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="morph-o-c" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="morph-o-s" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="morph-c-t-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="morph-s-t-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="morph-o-t-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="morph-t-up-c" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="morph-t-up-s" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="morph-t-up-o" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="morph-c-t-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="morph-s-t-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="morph-o-t-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="morph-t-right-c" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="morph-t-right-s" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="morph-t-right-o" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="morph-c-t-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="morph-s-t-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="morph-o-t-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="morph-t-down-c" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="morph-t-down-s" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="morph-t-down-o" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="morph-c-t-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="morph-s-t-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="morph-o-t-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="morph-t-left-c" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="morph-t-left-s" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="morph-t-left-o" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <!-- NEW in LivIcons (v1.3 of jQuery plugin and v1.1 of WP plugin)-->
                            <!-- New animated icons-->
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="connect" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="disconnect" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="collapse-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="collapse-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="expand-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="expand-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="battery" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="medal" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="servers" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <!-- New static (Brand) icons-->
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="apple-logo" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="bing" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="bitbucket" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="blogger" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="concrete5" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="deviantart" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="dribbble" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="github" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="github-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="instagram" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="opera" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="reddit" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="soundcloud" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="tumblr" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="vimeo" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="vk" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="xing" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="yahoo" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <!-- New in LivIcons (v1.2 of jQuery plugin and v1.0 of WP plugin)-->
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="address-book" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="albums" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="anchor" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="archive-add" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="archive-extract" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="asterisk" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="bluetooth" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="brightness-down" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="brightness-up" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="crop" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="eyedropper" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="file-export" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="file-import" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="folder-add" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="folder-flag" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="folder-lock" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="folder-new" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="folder-open" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="folder-remove" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="inbox-empty" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="inbox-in" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="inbox-out" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="indent-left" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="indent-right" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="message-add" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="message-flag" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="message-lock" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="message-new" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="message-in" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="message-remove" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="message-out" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="microphone" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="moon" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="new-window" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="pin-off" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="pin-on" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="playlist" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="save" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="shopping-cart-in" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="shopping-cart-out" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="striked" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="text-decrease" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="text-height" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="text-increase" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="text-size" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="text-width" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="thumbnails-big" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="thumbnails-small" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="timer" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="unlink" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="user-add" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="user-ban" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="user-flag" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="user-remove" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="users-add" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="users-ban" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="users-remove" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="vector-circle" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="vector-curve" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l1">
                                <i class="livicon" data-name="vector-line" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l2">
                                <i class="livicon" data-name="vector-polygon" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l3">
                                <i class="livicon" data-name="vector-square" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l4">
                                <i class="livicon" data-name="webcam" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l5">
                                <i class="livicon" data-name="wifi" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-4 text-center l6">
                                <i class="livicon" data-name="wifi-alt" data-size="40" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::hidden('menu_id') !!}
                        </div>
                        <div class="form-group">
                            {!! Form::hidden('domain_id') !!}
                        </div>
                        <div class="form-group">
                            {!! Form::hidden('type') !!}
                        </div>
                    </div>
                    <!-- /.col-sm-8 -->
                    <div class="col-sm-4">
                        <label for="blog_category" class="">Actions</label>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">{{ trans('adtech-core::buttons.save') }}</button>
                            <a href="{!! route('adtech.core.menu.create') !!}"
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
    <script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/select2/js/select2.js') }}"></script>
    <script src="{{ asset('/vendor/laravel-filemanager/js/lfm.js') }}" ></script>
    <script>
        $(function () {
            $('#lfm').filemanager('image');
            $(".select2").select2({
                theme:"bootstrap"
            });
            $(".livicon").click(function () {
                var icon_name = this.getAttribute('data-name');
                $("#thumbnail").replaceWith( $("#thumbnail").val(icon_name).clone(true) );
            });
        });

        var checkGroup = 0;
        function changeGroupType() {
            if (checkGroup % 2 == 0) {
                $("#group_name_txt").css('display', 'block');
                $('#group_name_txt').prop('disabled', false);
                $('#group_name_select').prop('disabled', true);
                $("#group_name_select").css('display', 'none');
            } else {
                $("#group_name_txt").css('display', 'none');
                $('#group_name_txt').prop('disabled', true);
                $('#group_name_select').prop('disabled', false);
                $("#group_name_select").css('display', 'block');
            }
            checkGroup++;
        }

        var txtUrl = '', typeData = '', typeView = '', txtModal = '',
            listRouteType = $.parseJSON('{!! $listRouteType !!}'),
            listRouteView = $.parseJSON('{!! $listRouteView !!}');

        function getTypeView(e) {
            txtUrl = '', txtModal = '';
            var route_name = e.options[e.selectedIndex].value;
            removeOptions(document.getElementById("category_name"));
            $("#boxParams_detail").css('display', 'none');
            $("#boxParams_detail").attr('value', '');
            $("#btn_typeData").attr('value', 'thuong');
            $("#btn_typeView").attr('value', 'thuong');
            $("#boxParams").css('display', 'none');

            if (route_name in listRouteType && route_name in listRouteView) {
                typeData = listRouteType[route_name];
                typeView = listRouteView[route_name];

                switch(typeData + '-' + typeView) {
                    case 'tintuc-list':
                        txtUrl = '{{ Illuminate\Support\Facades\Route::has('dhcd.api.news.category') ? route('dhcd.api.news.category') : '' }}';
                        break;
                    case 'tintuc-detail':
                        txtUrl = '';
                        txtModal = 'tintuc-detail';
                        {{--txtUrl = '{{ Illuminate\Support\Facades\Route::has('dhcd.api.news.detail') ? route('dhcd.api.news.detail') : '' }}';--}}
                            break;
                    case 'tailieu-list':
                        txtUrl = '{{ Illuminate\Support\Facades\Route::has('dhcd.api.tailieu.category') ? route('dhcd.api.tailieu.category') : '' }}';
                            break;
                    case 'tailieu-detail':
                        {{--                        txtUrl = '{{ Illuminate\Support\Facades\Route::has('dhcd.api.tailieu.detail') ? route('dhcd.api.tailieu.detail') : '' }}';--}}
                            break;
                    default:
                        txtUrl = '';
                }

                if (txtUrl != '') {
                    $.ajax({
                        url: txtUrl,
                        type: 'get',
                        dataType: 'json',
                        success: function (response) {
                            $("#boxParams").css('display', '');
                            $("#lbl_category").html('Chọn danh mục');
                            if (response.length > 0) {
                                response.forEach(function(element) {
                                    var x = document.getElementById("category_name");
                                    var option = document.createElement("option");
                                    if (typeof element.news_cat_id != 'undefined') {
                                        option.value = element.news_cat_id;
                                    }
                                    if (typeof element.document_cate_id != 'undefined' && typeof element.alias != 'undefined') {
                                        option.value = element.alias;
                                    }
                                    option.text = element.name;
                                    x.add(option, x[0]);
                                });

                                $("#btn_typeData").attr('value', typeData);
                                $("#btn_typeView").attr('value', typeView);
                            }
                        }
                    });
                }

                if (txtModal != '') {
                    $("#boxParams_detail").css('display', '');
                    $("#btn_typeData").attr('value', typeData);
                    $("#btn_typeView").attr('value', typeView);
                }

            }
        }

        function removeOptions(selectbox)
        {
            var i;
            for(i = selectbox.options.length - 1 ; i >= 0 ; i--)
            {
                selectbox.remove(i);
            }
        }
    </script>
@stop
