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
use App\SpfApi\AuthApi;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'example-app', 'as' => 'example-app.'], function () {
    Route::get('/', function () {
        return view('example.index');
    })->name('index');

    Route::post('/login-shop', 'ExampleAppController@loginShop')->name('login-shop');
});


Route::get('/auth', function (Request $request) {
    $data = $request->all();
    $verify = new AuthApi();
    $result = $verify->verifyRequest($data);
    dd($result);
});
