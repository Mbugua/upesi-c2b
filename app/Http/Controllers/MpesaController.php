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
            Log:info('simulate c2b >>>'.\json_encode($request->all()));

            // $c2b=MpesaClient::c2b($ShortCode, $Amount, $Msisdn);
            return \response()->json(
                ['response'=>['data'=>'success' //json_decode($c2b)
                ]
            ],200);

    }

    /**
     * C2B confirmation URL
     * Receives lodgements confirmations from MNO
     * @param request
     */
    function lodgements(Request $request){
        Log::info('{ processing lodgement { '.\json_encode($request->all()).'} }');
        $customerName=$request->FirstName.' '.$request->MiddleName.' '.$request->LastName;
        // $upesiRef="cb2_XXXXXX";
        $lodgement=[
            'customer_name'=>(null != $customerName)?$customerName:"Anonymous",
            'msisdn'=>(null != $request->MSISDN) ? ($request->MSISDN):'',
            'mpesa_ref'=>( null != $request->TransID)?$request->TransID:"",
            'amount'=>(null != $request->TransAmount)? $request->TransAmount:"",
            'trans_time'=>(null != $request->TransTime) ? $request->TransTime:now(),
            'transaction_type'=>(null != $request->TransactionType)?$request->TransactionType:'Pay Bill',
            'business_shortcode'=>(null !=$request->BusinessShortCode)?$request->BusinessShortCode:\env('MPESA_C2B_SHORTCODE'),
            'bill_refnumber'=>(null != $request->BillRefNumber)? $request->BillRefNumber :$request->MSISDN,
            // 'invoice_number'=>(null != $request->InvoiceNumber) ?$request->InvoiceNumber : $upesiRef ,
        ];
        // Log::debug('creating lodgement object >>'.json_encode($lodgement));
        ProcessLodgement::dispatch($lodgement)->onQueue('lodgements')->delay(3);

        //return success to safaricom
        return \response()->json([
                'ResultCode'=>0,
                'ResultDesc'=>'success'
            ],200);

    }

    /**
     * Validate incoming lodgements
     * Receive validation requests
     * to verify lodgements
     */
    function validation(Request $request){
        //log all validation requests
        Log::info('{ validating lodgement { '.\json_encode($request->all()).'} }');
        //return success to safaricom
		return response()->json([
			'ResultCode' => 0,
			'ResultDesc' => 'Success'
        ],200);
	}

    function callback(Request $request){
        $callback=MpesaClient::getCallback();
        Log::info('{ check callback {'.\json_encode($callback).'} }');
    }

    function result(Request $request){
        $input = $request->all();
        Log::info('{ check  result {'.\json_encode($request->all()).'} }');
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
        Log::error('{ logging timeouts  {' . json_encode($request->all()).'} }');
        //To Do
        //check how to sort this later
    }

    /**
     * Generic fallback route
     * handle exceptions and errors
     * gracefully.
     *
     */
    function notFound(){
        return \response()->json([
            'response'=>[
                'status'=>'failed',
                'data'=>[
                    'code'=>400,
                    'message'=>"Bad Request"
                ]
            ]
        ],400);
    }

    /**
     * Register C2B callbacks
     * confirmation and validation
     * URLs
     *
     * Activate this only once!
     */

    function register(Request $request){
        //if no env configs use form request values
        $shortcode=(null != $request->shortcode)?$request->shortcode:\env('MPESA_C2B_SHORTCODE');
        $validationURL=(null != $request->validation_url )? $request->validation_url:\env('MPESA_C2B_VALIDATION_URL');
        $confirmationURL=(null != $request->confirmation_url )?$request->confirmation_url:\env('MPESA_C2B_CONFIRMATION_URL');
        Log:info('MpesaController::registerC2BURLs >> {'.\json_encode(['shortcode'=>$shortcode,'validation_url'=>$validationURL,'confirmation_url'=>$confirmationURL]).'}');
        MpesaClient::registerURLS($confirmationURL,$validationURL,$shortcode);
    }
}
