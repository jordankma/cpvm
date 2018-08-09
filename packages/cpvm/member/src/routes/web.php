<?php
$adminPrefix = config('site.admin_prefix');
Route::group(array('prefix' => $adminPrefix), function() {
    Route::group(['middleware' => ['adtech.auth', 'adtech.acl']], function () {

        Route::get('cpvm/member/demo/log', 'DemoController@log')->name('cpvm.member.demo.log');
        Route::get('cpvm/member/demo/data', 'DemoController@data')->name('cpvm.member.demo.data');
        Route::get('cpvm/member/demo/manage', 'DemoController@manage')->name('cpvm.member.demo.manage');
        Route::get('cpvm/member/demo/create', 'DemoController@create')->name('cpvm.member.demo.create');
        Route::post('cpvm/member/demo/add', 'DemoController@add')->name('cpvm.member.demo.add');
        Route::get('cpvm/member/demo/show', 'DemoController@show')->name('cpvm.member.demo.show');
        Route::put('cpvm/member/demo/update', 'DemoController@update')->name('cpvm.member.demo.update');
        Route::get('cpvm/member/demo/delete', 'DemoController@delete')->name('cpvm.member.demo.delete');
        Route::get('cpvm/member/demo/confirm-delete', 'DemoController@getModalDelete')->name('cpvm.member.demo.confirm-delete');
    });
});