<?php

use Illuminate\Http\Request;
use  Illuminate\Http\Response;
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

Route::post('/lodgement','MpesaController@lodgement');
Route::post('/validation','MpesaController@validation');
Route::post('/callback','MpesaController@callback');
Route::post('/c2b','MpesaController@c2b');
Route::post('/timeout','MpesaController@timout');
Route::post('/result','MpesaController@result');
Route::post('/status','MpesaController@status');
Route::fallback('MpesaController@notfound');
