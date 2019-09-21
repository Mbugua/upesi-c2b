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
        validator::make( $request->all(),['ShortCode'=>'required',
             'Amout'=>'required|numeric','Msisdn'=>'required|max:12'])->validate();

            Log::info('[MpesaController::test]' .\json_encode($request->all()));
            $CommandID="CustomerPayBillOnline";
            $BillRefNumber="account";
            $Amount=$request->input('Amount');
            $Msisdn=$request->input('Msisdn');
            $ShortCode=env('MPESA_B2C_SHORTCODE');
            Log::info('valid data'.\json_encode($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber ));
             $c2b=MpesaClient::requestC2B($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber );


        return $c2b;
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
        $mpesa=new \Safaricom\Mpesa\Mpesa();
        $callback=$mpesa->getDataFromCallback();
        return \response()->json([
            'response'=>[
                'data'=>$callback,
            ]
            ],200);

    }
}
