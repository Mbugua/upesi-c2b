<?php

namespace App\Http\Controllers;
use App\Jobs\ProcessLodgements;
use Illuminate\Http\Request;
use App\Http\Requests\MpesaClient;
use Illuminate\Support\Facades\Log;
use Validator;
class MpesaController extends Controller
{
    /**
     * simulate c2b request
     */
    function test(Request $request){
            $CommandID="CustomerPayBillOnline";
            $BillRefNumber="account";
            $Amount=$request->input('Amount');
            $Msisdn=$request->input('Msisdn');//"2547979561830";
            $ShortCode=env('MPESA_B2C_SHORTCODE',($request->input('ShortCode')));

            $c2b=MpesaClient::requestC2B($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber );
             return \response()->json(['response'=>['data'=> json_decode($c2b)]],200);

    }

    /**
     * C2B confirmation URL
     */
    function lodgement(Request $request){
        Log::info('lodgementConfirmation');
        $data = $request->all();
		$data['ip'] = $request->ip();
		ProcessLodgements::dispatch($data)->onQueue('lodgements')->delay(3);
        return \response()->json([
                'ResultCode'=>0,
                'ResultDesc'=>'success'
            ],200);

    }
    function validation(Request $request){
        Log::info('validation payload >> ');
		return response()->json([
			'ResultCode' => 0,
			'ResultDesc' => 'Success'
        ],200);
	}

    function callback(Request $request){
        $callback=MpesaClient::getCallback();
        Log::info('callback result >>'.\json_encode($callback));
        return \response()->json([
            'ResultCode' => 0,
			'ResultDesc' => 'Success'
        ],200);

    }

    function notFound(Request $request){
        return \response()->json(['response'=>['data'=>$request->all(),'message'=>'API Route Not Found']],404);
    }
}
