<?php
$adminPrefix = config('site.admin_prefix');
Route::group(array('prefix' => $adminPrefix), function() {
    Route::group(['middleware' => ['adtech.auth', 'adtech.acl']], function () {

        Route::get('cpvm/subject/demo/log', 'DemoController@log')->name('cpvm.subject.demo.log');
        Route::get('cpvm/subject/demo/data', 'DemoController@data')->name('cpvm.subject.demo.data');
        Route::get('cpvm/subject/demo/manage', 'DemoController@manage')->name('cpvm.subject.demo.manage');
        Route::get('cpvm/subject/demo/create', 'DemoController@create')->name('cpvm.subject.demo.create');
        Route::post('cpvm/subject/demo/add', 'DemoController@add')->name('cpvm.subject.demo.add');
        Route::get('cpvm/subject/demo/show', 'DemoController@show')->name('cpvm.subject.demo.show');
        Route::put('cpvm/subject/demo/update', 'DemoController@update')->name('cpvm.subject.demo.update');
        Route::get('cpvm/subject/demo/delete', 'DemoController@delete')->name('cpvm.subject.demo.delete');
        Route::get('cpvm/subject/demo/confirm-delete', 'DemoController@getModalDelete')->name('cpvm.subject.demo.confirm-delete');
    });
});