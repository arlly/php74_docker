<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('users', 'Api\UserController@store');


Route::get('/personal', function(){
    $user = App\Eloquent\User::find(1);
    $token = $user->createToken('token_for_user1')->accessToken;
    return response()->json(['token' => $token]);
});


Route::post('/login', 'AuthController@login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/me', 'AuthController@me');
    Route::post('/logout', 'AuthController@logout');
    Route::get('/product', 'Api\ProductController@index');
});

