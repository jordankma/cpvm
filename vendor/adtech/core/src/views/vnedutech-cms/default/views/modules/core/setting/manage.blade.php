@extends('layouts.default')

{{-- Page title --}}
@section('title')
    {{ $title_page = trans('adtech-core::titles.setting.manage') }}
    @parent
@stop

{{-- page styles --}}
@section('header_styles')
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}"  rel="stylesheet" type="text/css" />
    <link href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/pages/blog.css') }}" rel="stylesheet" type="text/css">
    <!--end of page css-->
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        {{--<h1>{{ $title_page }}</h1>--}}
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('backend.homepage') }}"> <i class="livicon" data-name="home" data-size="16"
                                                                         data-color="#000"></i>
                    {{ trans('adtech-core::labels.home') }}
                </a>
            </li>
            <li class="active"><a href="#">{{ $title_page }}</a></li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content paddingleft_right15">
        <!--main content-->
        <div class="row">

            <div class="the-box no-border">

                <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                    <li class="{{ ($tab == 0) ? 'active' : '' }}">
                        <a href="#settings" data-toggle="tab">Cài đặt chung</a>
                    </li>
                    <li class="{{ ($tab == 1) ? 'active' : '' }}">
                        <a href="#translate" data-toggle="tab">Dịch ngôn ngữ</a>
                    </li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade {{ ($tab == 0) ? 'active in' : '' }}" id="settings">
                        {!! Form::open(array('url' => route('adtech.core.setting.update'), 'method' => 'put', 'class' => 'bf', 'files'=> true)) !!}
                        <div class="row">
                            <div class="col-sm-8">

                                <label>{{ trans('adtech-core::labels.setting.title_page') }}</label>
                                <div class="form-group">
                                    {!! Form::text('title', $title, array('class' => 'form-control', 'autofocus'=>'autofocus', 'placeholder'=> trans('adtech-core::common.setting.title_here'))) !!}
                                </div>

                                <label>Logo</label>
                                <div class="input-group">
                                   <span class="input-group-btn">
                                     <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                       <i class="fa fa-picture-o"></i> Choose
                                     </a>
                                   </span>
                                    <input id="thumbnail" class="form-control" type="text" name="logo" value="{{ $logo }}">
                                </div>
                                <img id="holder" src="{{ $logo }}" style="margin-top:15px;max-height:100px;">
                                <br><br>

                                <label>Logo mini</label>
                                <div class="input-group">
                                   <span class="input-group-btn">
                                     <a id="lfm2" data-input="thumbnail2" data-preview="holder2" class="btn btn-primary">
                                       <i class="fa fa-picture-o"></i> Choose
                                     </a>
                                   </span>
                                    <input id="thumbnail2" class="form-control" type="text" name="logo_mini" value="{{ $logo_mini }}">
                                </div>
                                <img id="holder2" src="{{ $logo_mini }}" style="margin-top:15px;max-height:100px;">
                                <br><br>

                                <label>Favicon</label>
                                <div class="input-group">
                                   <span class="input-group-btn">
                                     <a id="lfm1" data-input="thumbnail1" data-preview="holder1" class="btn btn-primary">
                                       <i class="fa fa-picture-o"></i> Choose
                                     </a>
                                   </span>
                                    <input id="thumbnail1" class="form-control" type="text" name="favicon" value="{{ $favicon }}">
                                </div>
                                <img id="holder1" src="{{ $favicon }}" style="margin-top:15px;max-height:100px;">
                                <br><br>

                                <label>Ngôn ngữ mặc định</label>
                                <div class="form-group">
                                    <select class="form-control select2" title="Select language..." name="language">
                                        @foreach($languages as $language)
                                            <option value="{{ $language->alias }}" {{ $language->alias == $languageCurrent ? ' selected="selected"' : '' }}>{{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <label>Logo link</label>
                                <div class="form-group">
                                    {!! Form::text('logo_link', $logo_link, array('class' => 'form-control', 'placeholder'=> trans('adtech-core::common.setting.logo_link_here'))) !!}
                                </div>

                                <label>Company Name</label>
                                <div class="form-group">
                                    {!! Form::text('company_name', $company_name, array('class' => 'form-control', 'placeholder'=> trans('adtech-core::common.setting.company_name_here'))) !!}
                                </div>

                                <label>Address</label>
                                <div class="form-group">
                                    {!! Form::text('address', $address, array('class' => 'form-control', 'placeholder'=> trans('adtech-core::common.setting.address_here'))) !!}
                                </div>

                                <label>Email</label>
                                <div class="form-group">
                                    {!! Form::text('email', $email, array('class' => 'form-control', 'placeholder'=> trans('adtech-core::common.setting.email_here'))) !!}
                                </div>

                                <label>Phone number</label>
                                <div class="form-group">
                                    {!! Form::text('phone', $phone, array('class' => 'form-control', 'placeholder'=> trans('adtech-core::common.setting.phone_here'))) !!}
                                </div>

                                <label>Hotline</label>
                                <div class="form-group">
                                    {!! Form::text('hotline', $hotline, array('class' => 'form-control', 'placeholder'=> trans('adtech-core::common.setting.hotline_here'))) !!}
                                </div>

                                <label>GA Code</label>
                                <div class="form-group">
                                    {!! Form::textarea('ga_code', $ga_code, array('class' => 'form-control', 'placeholder'=> trans('adtech-core::common.setting.ga_code_here'))) !!}
                                </div>

                                <label>Chat Code</label>
                                <div class="form-group">
                                    {!! Form::textarea('chat_code', $chat_code, array('class' => 'form-control', 'placeholder'=> trans('adtech-core::common.setting.chat_code_here'))) !!}
                                </div>


                            </div>
                            <!-- /.col-sm-8 -->
                            <div class="col-sm-4">
                                <label for="blog_category" class="">Actions</label>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">{{ trans('adtech-core::buttons.save') }}</button>
                                    <a href="{!! route('adtech.core.setting.manage') !!}"
                                       class="btn btn-danger">{{ trans('adtech-core::buttons.discard') }}</a>
                                </div>
                            </div>
                            <!-- /.col-sm-4 --> </div>
                        <!-- /.row -->
                        {!! Form::close() !!}
                    </div>
                    <div class="tab-pane fade {{ ($tab == 1) ? 'active in' : '' }}" id="translate">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            @foreach($arrTranslate as $module => $translate)
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading-{{ $module }}">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ $module }}" aria-controls="collapse-{{ $module }}">
                                            <h4 class="panel-title" style="color: white">{{ $module }}</h4>
                                        </a>
                                    </div>
                                    <div id="collapse-{{ $module }}" class="panel-collapse collapse {{ ($tab_tran == $module) ? 'in' : '' }}" role="tabpanel" aria-labelledby="heading-{{ $module }}">
                                        <div class="panel-body">

                                            <div class="row">
                                            @foreach($translate as $file => $content)

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 10px">
                                                    <div class="panel panel-primary" style="overflow: hidden">
                                                        <h4 class="panel-title pull-left" style="font-weight: bold; position: absolute; top:10px; left: 30px">
                                                            {{ $file }}
                                                        </h4>
                                                        <div class="panel-body" style="margin-top: 50px; height: 250px; overflow: auto">
                                                            {!! Form::open(array('url' => route('adtech.core.setting.translate'), 'method' => 'put', 'class' => 'bf')) !!}
                                                            <div class="form-position" style="position: absolute; right: 20px; top:10px;">
                                                                <div class="col-md-12 text-right">
                                                                    <button type="submit" class="btn btn-responsive btn-primary btn-sm">Cập nhật</button>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                {!! Form::hidden('package_module', $module) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! Form::hidden('file', $file) !!}
                                                            </div>

                                                            <div class="row">
                                                                @foreach($content as $key => $value)

                                                                    @if (is_array($value))
                                                                        @foreach($value as $key1 => $value1)

                                                                            @if (is_array($value1))
                                                                                @foreach($value1 as $key2 => $value2)

                                                                                    @if (is_array($value2))
                                                                                        @foreach($value2 as $key3 => $value3)
                                                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 10px">
                                                                                                <div class="form-group">
                                                                                                    <label>{{ $key1 . '-' . $key1 . '-' . $key2 . '-' . $key3 }}</label>
                                                                                                    <input type="text" name="tran[{{ $key1 }}][{{ $key1 }}][{{ $key2 }}][{{ $key3 }}]" class="form-control" value="{{ $value3 }}">
                                                                                                </div>
                                                                                            </div>
                                                                                        @endforeach
                                                                                    @endif
                                                                                    @if (!is_array($value2))
                                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 10px">
                                                                                            <div class="form-group">
                                                                                                <label>{{ $key1 . '-' . $key1 . '-' . $key2 }}</label>
                                                                                                <input type="text" name="tran[{{ $key1 }}][{{ $key1 }}][{{ $key2 }}]" class="form-control" value="{{ $value2 }}">
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif

                                                                                @endforeach
                                                                            @endif
                                                                            @if (!is_array($value1))
                                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 10px">
                                                                                    <div class="form-group">
                                                                                        <label>{{ $key . '-' . $key1 }}</label>
                                                                                        <input type="text" name="tran[{{ $key }}][{{ $key1 }}]" class="form-control" value="{{ $value1 }}">
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                        @endforeach
                                                                    @endif
                                                                    @if (!is_array($value))
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 10px">

                                                                            <div class="form-group">
                                                                                <label>{{ $key }}</label>
                                                                                <input type="text" name="tran[{{ $key }}]" class="form-control" value="{{ $value }}">
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    </div>
                                                </div>

                                            @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

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
    <script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" ></script>
    <script src="{{ asset('/vendor/laravel-filemanager/js/lfm.js') }}" ></script>
    <script>
        $(function () {
            $('#lfm').filemanager('image');
            $('#lfm1').filemanager('image');
            $('#lfm2').filemanager('image');
        })
    </script>
@stop
