<?php
/**
 * Backend Routes
 */
$adminPrefix = config('site.admin_prefix');
Route::group(array('prefix' => $adminPrefix), function () {
    /*
     * auth - login
     */

    Route::get('adtech/core/menu/tab', 'MenuController@tab')->name('adtech.core.menu.tab');

    Route::match(['get', 'post'], 'login', 'Auth\LoginController@login')->name('adtech.core.auth.login');

    Route::match(['get', 'post'], 'register', 'Auth\RegisterController@create')->name('adtech.core.auth.register');

    Route::match(['get', 'post'], 'forgot-password', 'Auth\ForgotPasswordController@forgot')->name('adtech.core.auth.forgot');

    Route::match(['get', 'post'], 'reset-password/{reset_token}', 'Auth\ResetPasswordController@reset')->name('adtech.core.auth.reset');

    Route::group(['middleware' => ['adtech.auth']], function () {
        /*
         * Activate
         */
        Route::get('activate/{token}', 'ActivateController@activate')->name('adtech.core.activate.activate');
        /*
         * Activate - Resend
         */
        Route::get('activate/resend', 'ActivateController@resend')->name('adtech.core.activate.resend');
        /*
         * Auth - Logout
         */
        Route::get('logout', 'Auth\LoginController@logout')->name('adtech.core.auth.logout');
    });

    //
    Route::group(['middleware' => ['adtech.auth', 'adtech.acl', 'adtech.locale']], function () {

        Route::get('', 'DashboardController@index')
            ->where('as', 'Trang quản trị')
            ->name('backend.homepage');

        Route::get('adtech/core/setting/manage', 'SettingController@manage')
            ->where('as', 'Cài đặt chung')
            ->name('adtech.core.setting.manage');

        Route::get('adtech/core/setting/set-language', 'SettingController@setLanguage')->name('adtech.core.setting.set-language');
        Route::put('adtech/core/setting/update', 'SettingController@update')->name('adtech.core.setting.update');
        Route::put('adtech/core/setting/translate', 'SettingController@translate')->name('adtech.core.setting.translate');
        Route::match(['get', 'post'], '/adtech/core/file/upload-test', 'DashboardController@fileuploadtest')->name('adtech.core.file.upload-test');

        Route::get('/adtech/core/file/manage', 'DashboardController@filemanage')
            ->where('as', 'Quản lý file')
            ->name('adtech.core.file.manage');
        Route::get('/laravel-filemanager', '\UniSharp\LaravelFilemanager\Controllers\LfmController@show')->name('adtech.core.file.manager');
        Route::post('/laravel-filemanager/upload', '\UniSharp\LaravelFilemanager\Controllers\UploadController@upload')->name('adtech.core.file.upload');

        Route::get('adtech/core/role/manage', 'RoleController@manage')
            ->where('as', 'Quản lý role')
            ->name('adtech.core.role.manage');
        Route::get('adtech/core/role/log', 'RoleController@log')->name('adtech.core.role.log');
        Route::get('adtech/core/role/data', 'RoleController@data')->name('adtech.core.role.data');
        Route::get('adtech/core/role/create', 'RoleController@create')->name('adtech.core.role.create');
        Route::post('adtech/core/role/add', 'RoleController@add')->name('adtech.core.role.add');
        Route::get('adtech/core/role/show', 'RoleController@show')->name('adtech.core.role.show');
        Route::put('adtech/core/role/update', 'RoleController@update')->name('adtech.core.role.update');
        Route::get('adtech/core/role/delete', 'RoleController@delete')->name('adtech.core.role.delete');
        Route::get('adtech/core/role/confirm-delete', 'RoleController@getModalDelete')->name('adtech.core.role.confirm-delete');

        Route::get('adtech/core/api/manage', 'ApiController@manage')->name('adtech.core.api.manage');
        Route::get('adtech/core/api/log', 'ApiController@log')->name('adtech.core.api.log');
        Route::get('adtech/core/api/data', 'ApiController@data')->name('adtech.core.api.data');
        Route::get('adtech/core/api/create', 'ApiController@create')->name('adtech.core.api.create');
        Route::post('adtech/core/api/add', 'ApiController@add')->name('adtech.core.api.add');
        Route::get('adtech/core/api/show', 'ApiController@show')->name('adtech.core.api.show');
        Route::put('adtech/core/api/update', 'ApiController@update')->name('adtech.core.api.update');
        Route::get('adtech/core/api/delete', 'ApiController@delete')->name('adtech.core.api.delete');
        Route::get('adtech/core/api/datademo', 'ApiController@getModalDatademo')->name('adtech.core.api.datademo');
        Route::get('adtech/core/api/confirm-delete', 'ApiController@getModalDelete')->name('adtech.core.api.confirm-delete');
        Route::get('api/{module}/{link}', 'ApiController@showdata')->name('adtech.core.api.showdata');

        Route::get('adtech/core/menu/manage', 'MenuController@manage')
            ->where('as', 'Quản lý menu')
            ->name('adtech.core.menu.manage');
        Route::get('adtech/core/menu/log', 'MenuController@log')->name('adtech.core.menu.log');
        Route::get('adtech/core/menu/data', 'MenuController@data')->name('adtech.core.menu.data');
        Route::get('adtech/core/menu/create', 'MenuController@create')->name('adtech.core.menu.create');
        Route::post('adtech/core/menu/add', 'MenuController@add')->name('adtech.core.menu.add');
        Route::get('adtech/core/menu/show', 'MenuController@show')->name('adtech.core.menu.show');
        Route::put('adtech/core/menu/update', 'MenuController@update')->name('adtech.core.menu.update');
        Route::get('adtech/core/menu/delete', 'MenuController@delete')->name('adtech.core.menu.delete');
        Route::get('adtech/core/menu/confirm-delete', 'MenuController@getModalDelete')->name('adtech.core.menu.confirm-delete');

        Route::get('adtech/core/domain/manage', 'DomainController@manage')
            ->where('as', 'Quản lý domain')
            ->name('adtech.core.domain.manage');
        Route::get('adtech/core/domain/log', 'DomainController@log')->name('adtech.core.domain.log');
        Route::get('adtech/core/domain/data', 'DomainController@data')->name('adtech.core.domain.data');
        Route::get('adtech/core/domain/create', 'DomainController@create')->name('adtech.core.domain.create');
        Route::post('adtech/core/domain/add', 'DomainController@add')->name('adtech.core.domain.add');
        Route::get('adtech/core/domain/show', 'DomainController@show')->name('adtech.core.domain.show');
        Route::put('adtech/core/domain/update', 'DomainController@update')->name('adtech.core.domain.update');
        Route::get('adtech/core/domain/delete', 'DomainController@delete')->name('adtech.core.domain.delete');
        Route::get('adtech/core/domain/confirm-delete', 'DomainController@getModalDelete')->name('adtech.core.domain.confirm-delete');

        Route::get('adtech/core/locale/manage', 'LocaleController@manage')
            ->where('as', 'Quản lý ngôn ngữ')
            ->name('adtech.core.locale.manage');
        Route::get('adtech/core/locale/log', 'LocaleController@log')->name('adtech.core.locale.log');
        Route::get('adtech/core/locale/data', 'LocaleController@data')->name('adtech.core.locale.data');
        Route::get('adtech/core/locale/create', 'LocaleController@create')->name('adtech.core.locale.create');
        Route::post('adtech/core/locale/add', 'LocaleController@add')->name('adtech.core.locale.add');
        Route::get('adtech/core/locale/show', 'LocaleController@show')->name('adtech.core.locale.show');
        Route::put('adtech/core/locale/update', 'LocaleController@update')->name('adtech.core.locale.update');
        Route::get('adtech/core/locale/delete', 'LocaleController@delete')->name('adtech.core.locale.delete');
        Route::get('adtech/core/locale/confirm-delete', 'LocaleController@getModalDelete')->name('adtech.core.locale.confirm-delete');

        Route::get('adtech/core/package/manage', 'PackageController@manage')
            ->where('as', 'Quản lý package')
            ->name('adtech.core.package.manage');
        Route::get('adtech/core/package/log', 'PackageController@log')->name('adtech.core.package.log');
        Route::get('adtech/core/package/demo', 'PackageController@demo')->name('adtech.core.package.demo');
        Route::get('adtech/core/package/data', 'PackageController@data')->name('adtech.core.package.data');
        Route::get('adtech/core/package/create', 'PackageController@create')->name('adtech.core.package.create');
        Route::post('adtech/core/package/add', 'PackageController@add')->name('adtech.core.package.add');
        Route::get('adtech/core/package/show', 'PackageController@show')->name('adtech.core.package.show');
        Route::put('adtech/core/package/update', 'PackageController@update')->name('adtech.core.package.update');
        Route::get('adtech/core/package/delete', 'PackageController@delete')->name('adtech.core.package.delete');
        Route::get('adtech/core/package/status', 'PackageController@status')->name('adtech.core.package.status');
        Route::get('adtech/core/package/download', 'PackageController@download')->name('adtech.core.package.download');
        Route::get('adtech/core/package/search', 'PackageController@getModalSearch')->name('adtech.core.package.search');
        Route::post('adtech/core/package/add-package', 'PackageController@addPackage')->name('adtech.core.package.add-package');
        Route::post('adtech/core/package/search-package', 'PackageController@searchPackage')->name('adtech.core.package.search-package');
        Route::get('adtech/core/package/confirm-delete', 'PackageController@getModalDelete')->name('adtech.core.package.confirm-delete');
        Route::get('adtech/core/package/confirm-status', 'PackageController@getModalStatus')->name('adtech.core.package.confirm-status');
        Route::get('adtech/core/package/confirm-public', 'PackageController@getModalPublic')->name('adtech.core.package.confirm-public');

        Route::get('adtech/core/user/manage', 'UserController@manage')
            ->where('as', 'Quản lý user')
            ->name('adtech.core.user.manage');
        Route::get('adtech/core/user/log', 'UserController@log')->name('adtech.core.user.log');
        Route::get('adtech/core/user/data', 'UserController@data')->name('adtech.core.user.data');
        Route::get('adtech/core/user/create', 'UserController@create')->name('adtech.core.user.create');
        Route::post('adtech/core/user/add', 'UserController@add')->name('adtech.core.user.add');
        Route::get('adtech/core/user/show', 'UserController@show')->name('adtech.core.user.show');
        Route::put('adtech/core/user/update', 'UserController@update')->name('adtech.core.user.update');
        Route::get('adtech/core/user/delete', 'UserController@delete')->name('adtech.core.user.delete');
        Route::get('adtech/core/user/checkExits', 'UserController@checkExits')->name('adtech.core.user.checkExits');
        Route::get('adtech/core/user/confirm-delete', 'UserController@getModalDelete')->name('adtech.core.user.confirm-delete');

        Route::get('adtech/core/route/list', 'RouteController@manage')->name('adtech.core.route.list');
        Route::get('adtech/core/route/data', 'RouteController@data')->name('adtech.core.route.data');

        /**
         * Permission manage
         */
        Route::get('adtech/core/permission/data-more', 'PermissionController@dataMore')->name('adtech.core.permission.data-more');
        Route::get('adtech/core/permission/data', 'PermissionController@data')->name('adtech.core.permission.data');
        Route::get('adtech/core/permission/set-name', 'PermissionController@setName')->name('adtech.core.permission.set-name');
        Route::get('adtech/core/permission/{object_type}/{object_id}', 'PermissionController@manage')
            ->where('object_type', '[role|user|group]+')
            ->where('object_id', '[0-9]+')
            ->name('adtech.core.permission.manage');
        Route::get('adtech/core/permission/more/{object_type}/{object_id}', 'PermissionController@manageMore')
            ->where('object_type', '[role|user|group]+')
            ->where('object_id', '[0-9]+')
            ->name('adtech.core.permission.manage-more');

        Route::post('adtech/core/permission/set', 'PermissionController@set')->name('adtech.core.permission.set');
    });
});
