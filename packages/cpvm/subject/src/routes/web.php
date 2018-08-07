<?php
$adminPrefix = config('site.admin_prefix');
Route::group(array('prefix' => $adminPrefix), function() {
    Route::group(['middleware' => ['adtech.auth', 'adtech.acl']], function () {

        Route::get('cpvm/subject/subject/log', 'SubjectController@log')->name('cpvm.subject.subject.log');
        Route::get('cpvm/subject/subject/data', 'SubjectController@data')->name('cpvm.subject.subject.data');
        Route::get('cpvm/subject/subject/manage', 'SubjectController@manage')->name('cpvm.subject.subject.manage')->where('as','Môn - Danh sách');
        Route::get('cpvm/subject/subject/create', 'SubjectController@create')->name('cpvm.subject.subject.create');
        Route::post('cpvm/subject/subject/add', 'SubjectController@add')->name('cpvm.subject.subject.add');
        Route::get('cpvm/subject/subject/show', 'SubjectController@show')->name('cpvm.subject.subject.show');
        Route::post('cpvm/subject/subject/update', 'SubjectController@update')->name('cpvm.subject.subject.update');
        Route::get('cpvm/subject/subject/delete', 'SubjectController@delete')->name('cpvm.subject.subject.delete');
        Route::get('cpvm/subject/subject/confirm-delete', 'SubjectController@getModalDelete')->name('cpvm.subject.subject.confirm-delete');

        Route::get('cpvm/subject/subject/list', 'SubjectController@getSubject')->name('cpvm.subject.subject.list');
    });
});