<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MpesaClient;
use Illuminate\Support\Facades\Log;
use Validator;
class MpesaController extends Controller
{
    function test(Request $request){
            $CommandID="CustomerPayBillOnline";
            $BillRefNumber="account";
            $Amount=$request->input('Amount');
            $Msisdn=$request->input('Msisdn');//"2547979561830";
            $ShortCode=env('MPESA_B2C_SHORTCODE',($request->input('ShortCode')));

            $c2b=MpesaClient::requestC2B($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber );

            var_dump($c2b);
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
        $callback=MpesaClient::getCallback();
        return \response()->json([
            'response'=>[ 'data'=>$callback]
        ],200);

    }
}
