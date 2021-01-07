<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Admin panel routes
Route::group(['prefix' => 'admin/kpi', 'as' => 'admin.'], function () {
    Route::delete('resources/{resource}/delete', 'Admin\ResourceController@delete')->name('resources.delete');
    Route::put('resources/{resource}/restore', 'Admin\ResourceController@restore')->name('resources.restore');
    Route::get('resources/trash', 'Admin\ResourceController@trash')->name('resources.trash');
    Route::resource('resources', 'Admin\ResourceController');
});

//Member panel routes
Route::group(['prefix' => 'member/kpi', 'as' => 'member.'], function () {
    Route::delete('resources/{resource}/delete', 'Member\ResourceController@delete')->name('resources.delete');
    Route::put('resources/{resource}/restore', 'Member\ResourceController@restore')->name('resources.restore');
    Route::get('resources/trash', 'Member\ResourceController@trash')->name('resources.trash');
    Route::resource('resources', 'Member\ResourceController');
});
