<?php
use  Illuminate\Http\Request;
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
Route::fallback(function(Response $response){
    return \response()->json(
        ['response'=>
            ['data'=>[
            'message'=>'Not allowed']
            ]
        ],400);
});