<?php
$adminPrefix = config('site.admin_prefix');
Route::group(array('prefix' => $adminPrefix), function() {
    Route::group(['middleware' => ['adtech.auth', 'adtech.acl']], function () {

        Route::get('cpvm/classes/classes/log', 'ClassesController@log')->name('cpvm.classes.classes.log');
        Route::get('cpvm/classes/classes/data', 'ClassesController@data')->name('cpvm.classes.classes.data');
        Route::get('cpvm/classes/classes/manage', 'ClassesController@manage')->name('cpvm.classes.classes.manage')->where('as','Lớp - Danh sách');
        Route::get('cpvm/classes/classes/create', 'ClassesController@create')->name('cpvm.classes.classes.create');
        Route::post('cpvm/classes/classes/add', 'ClassesController@add')->name('cpvm.classes.classes.add');
        Route::get('cpvm/classes/classes/show', 'ClassesController@show')->name('cpvm.classes.classes.show');
        Route::post('cpvm/classes/classes/update', 'ClassesController@update')->name('cpvm.classes.classes.update');
        Route::get('cpvm/classes/classes/delete', 'ClassesController@delete')->name('cpvm.classes.classes.delete');
        Route::get('cpvm/classes/classes/confirm-delete', 'ClassesController@getModalDelete')->name('cpvm.classes.classes.confirm-delete');
    });
});