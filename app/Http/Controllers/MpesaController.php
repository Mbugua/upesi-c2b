<?php

namespace App\Http\Controllers;
use App\Jobs\ProcessLodgement;
use Illuminate\Http\Request;
use App\Http\Requests\MpesaClient;
use Illuminate\Support\Facades\Log;
use Validator;
class MpesaController extends Controller
{
    /**
     * simulate c2b request
     */
    function c2b(Request $request){
            Log:info('c2b >>>'.\json_encode($request->all()));
            $Amount=$request->input('Amount');
            $Msisdn=$request->input('Msisdn');
            $ShortCode=env('MPESA_B2C_SHORTCODE', $request->input('ShortCode'));

            $c2b=MpesaClient::requestC2B($ShortCode, $Amount, $Msisdn);
            Log::info('c2b response >>'.($c2b));
            return \response()->json(['response'=>['data'=> json_decode($c2b)]],200);

    }

    /**
     * C2B confirmation URL
     */
    function lodgement(Request $request){
        Log::info('<< lodgement confirmation >>'.\json_encode($request->all()));
        $data = $request->all();
		$data['ip'] = $request->ip();
		ProcessLodgement::dispatch($data)->onQueue('lodgements')->delay(3);
        return \response()->json([
                'ResultCode'=>0,
                'ResultDesc'=>'success'
            ],200);

    }
    function validation(Request $request){
        Log::info('validation payload >> '.\json_encode($request->all()));
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

    function result(Request $request){
        $input = $request->all();
        Log::info('result >>>'.\json_encode($request->all()));
    }

    function status(Request $request){
        Log::info('status >>>'.\json_encode($request->all()));
        $TransactionID=$request->input('transactionID');
        $PartyA=env('MPESA_C2B_SHORTCODE',$request->input('paybill'));
        $status=MpesaClient::getTransactionStatus($TransactionID,$PartyA);
        return \response()->json(
            ['response'=>['data'=>json_decode($status)]]
        );

    }

    function timeout(Request $request){
        Log::error("Timeout >>>" . json_encode($request->all()));
    }

    function notFound(Request $request){
        return \response()->json(['response'=>['data'=>[
            'code'=>400,
            'message'=>'Bad Request'
        ]]],404);
    }
}
