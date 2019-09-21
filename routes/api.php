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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/',function(){
        return response()->json([
        'response'=>[
            'web'=>'upesi-c2b',
            'data'=>[
                'message'=>"Hello Heroku"
            ]
        ]
        ],200);
});

Route::post('/lodgementConfirmation','MpesaController@lodgementConfirmation')->name('lodgement');
Route::post('/lodgementValidation','MpesaController@lodgementValidation')->name('validation');
Route::post('/callback','MpesaController@callback')->name('callback');


Route::fallback(function(Response $response){
    return \response()->json(
        ['response'=>
            ['data'=>[$response]]
        ],400);
});
