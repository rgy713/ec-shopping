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


Route::group(['namespace'=>'Api\\Admin','prefix' => 'admin', 'middleware' => 'auth:admin-api'], function() {
    Route::get('/settings','AdminSettingController@getOptions');
    Route::patch('/settings/left_menu','AdminSettingController@saveOptionLeftMenu');
    Route::patch('/settings/right_menu','AdminSettingController@saveOptionRightMenu');
});

Route::group(['namespace'=>'Api'], function() {
    Route::get('/lists/prefecture','ListsController@prefecture');
    Route::get('/lists/paymentList/{id}','ListsController@paymentList');
    Route::get('/lists/deliveryTimeList/{id}','ListsController@deliveryTimeList');
    Route::get('/lists/deliveryRequestDateListWithLeadTime/{id1}/{id2}','ListsController@deliveryRequestDateListWithLeadTime');
    Route::get('/value/deliveryFee/{id1}/{id2}','ValueController@deliveryFee');
});



