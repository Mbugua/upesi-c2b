<?php
use  Illuminate\Http\Response;
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

Route::get('/', function (Response $response) {
    return response()->json([
        'response'=>[
            'web'=>'upesi-c2b',
            'data'=>[
                $response
            ]
        ]
        ],200);
});

Route::fallback(function (Response $response) {
    return response()->json([
        'response'=>[
            'data'=>[
                $response
            ]
        ]
        ],400);
});