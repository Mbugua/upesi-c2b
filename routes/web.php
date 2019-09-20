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

Route::get('/', function() {
    return ['response'=>[
            'web'=>'upesi-c2b',
        ]
    ];
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