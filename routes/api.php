<?php

use Illuminate\Http\Request;
\/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('api')->group(function (){
    Route::group(['prefix' => 'store-front', 'as' => 'store-front.'], function (){
        Route::get('/message/{product_id}', 'StoreFrontController@getMessages');
    });

});
