<?php
$adminPrefix = config('site.admin_prefix');
Route::group(array('prefix' => $adminPrefix), function() {
    Route::group(['middleware' => ['adtech.auth', 'adtech.acl']], function () {

        Route::get('cpvm/block/block/log', 'BlockController@log')->name('cpvm.block.block.log');
        Route::get('cpvm/block/block/data', 'BlockController@data')->name('cpvm.block.block.data');
        Route::get('cpvm/block/block/manage', 'BlockController@manage')->name('cpvm.block.block.manage')->where('as','Khối - Danh sách');
        Route::get('cpvm/block/block/create', 'BlockController@create')->name('cpvm.block.block.create');
        Route::post('cpvm/block/block/add', 'BlockController@add')->name('cpvm.block.block.add');
        Route::get('cpvm/block/block/show', 'BlockController@show')->name('cpvm.block.block.show');
        Route::put('cpvm/block/block/update', 'BlockController@update')->name('cpvm.block.block.update');
        Route::get('cpvm/block/block/delete', 'BlockController@delete')->name('cpvm.block.block.delete');
        Route::get('cpvm/block/block/confirm-delete', 'BlockController@getModalDelete')->name('cpvm.block.block.confirm-delete');
    });
});