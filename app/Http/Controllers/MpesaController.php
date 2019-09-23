<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MpesaClient;
use Illuminate\Support\Facades\Log;
use Validator;
class MpesaController extends Controller
{
    function test(Request $request){
        Log::info('[MpesaController::test] request' .\json_encode($request->all()));

        Log::info('[MpesaController::test]');
        // $validate=validator::make( $request->all(),['ShortCode'=>'safe',
        //      'Amount'=>'required|numeric','Msisdn'=>'required|max:12'])->validate();

        //     Log::info('[MpesaController::test]' .\json_encode($request->all()));
        //     if($validate->validated()){


            $CommandID="CustomerPayBillOnline";
            $BillRefNumber="account";
            $Amount=$request->input('Amount');
            $Msisdn==$request->input('Msisdn');//"2547979561830";
            $ShortCode=env('MPESA_B2C_SHORTCODE',($request->input('ShortCode')));
            Log::info('valid data'.\json_encode($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber ));
             $c2b=MpesaClient::requestC2B($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber );

            var_dump($c2b);
             return  \response()->json([
            'response'=>['data'=>[
                $c2b
            ]]
            ],200);
        // }
    }

    /**
     * C2B confirmation URL
     */
    function lodgementConfirmation(Request $request){
        Log::info('lodgementConfirmation >>'.\json_encode($request->all()));
        return \response()->json([
            'response'=>[
                'data'=>$request->all(),
                'ResultCode'=>0,
                'ResultDesc'=>'success'
            ]],200);

    }
    function lodgementValidation(Request $request){
		return response()->json([
			'ResultCode' => 0,
			'ResultDesc' => 'Success'
        ],200);
	}

    function callback(Request $request){
        Log::info('check url registered >>');
        $mpesa=MpesaClient::callback();

        return \response()->json([
            'response'=>[
                'data'=>$callback,
            ]
            ],200);

    }
}
