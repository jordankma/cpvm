<?php
$adminPrefix = config('site.admin_prefix');
Route::group(array('prefix' => $adminPrefix), function() {
    Route::group(['middleware' => ['adtech.auth', 'adtech.acl']], function () {

        Route::get('cpvm/level/level/log', 'LevelController@log')->name('cpvm.level.level.log');
        Route::get('cpvm/level/level/data', 'LevelController@data')->name('cpvm.level.level.data');
        Route::get('cpvm/level/level/manage', 'LevelController@manage')->name('cpvm.level.level.manage')->where('as','Cấp - Danh sách');
        Route::get('cpvm/level/level/create', 'LevelController@create')->name('cpvm.level.level.create');
        Route::post('cpvm/level/level/add', 'LevelController@add')->name('cpvm.level.level.add');
        Route::get('cpvm/level/level/show', 'LevelController@show')->name('cpvm.level.level.show');
        Route::post('cpvm/level/level/update', 'LevelController@update')->name('cpvm.level.level.update');
        Route::get('cpvm/level/level/delete', 'LevelController@delete')->name('cpvm.level.level.delete');
        Route::get('cpvm/level/level/confirm-delete', 'LevelController@getModalDelete')->name('cpvm.level.level.confirm-delete');
    });
});