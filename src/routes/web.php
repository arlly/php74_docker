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

Route::get('/', function () {
    return view('home');
});

Route::get('/sample', 'SampleController@index')->name('sample');
Route::get('/test', 'SampleController@test')->name('test');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
], function () {

    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout');

    // // Registration Routes...
    // Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    // Route::post('register', 'Auth\RegisterController@register');

    // // Password Reset Routes...
    //Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    //Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    //Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('admin.password.reset');
    //Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::get('/', 'AdminController@index')->name('admin.home');
    Route::get('/home', 'AdminController@index')->name('admin.home');

    /**
     * information
     */
    Route::group([
        'prefix' => 'information'
    ], function () {
        Route::get('/', 'InformationController@index')->name('admin.information.index');
        Route::post('/', 'InformationController@indexPost');
        Route::get('create', 'InformationController@create')->name('admin.information.create');
        Route::post('create', 'InformationController@store');
        Route::get('edit/{id}', 'InformationController@edit')->name('admin.information.edit');
        Route::post('edit/{id}', 'InformationController@update');
        Route::get('destroy/{id}', 'InformationController@destroy')->name('admin.information.destroy');
        Route::get('publish/{id}', 'InformationController@publish')->name('admin.information.publish');

        Route::get('copy/{id}', 'InformationController@copy')->name('admin.information.copy');
        Route::post('copy/{id}', 'InformationController@copyPost');
    });
});
