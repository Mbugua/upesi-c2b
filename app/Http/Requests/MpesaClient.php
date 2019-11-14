<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Safaricom\Mpesa\Mpesa;
use Illuminate\Support\Facades\Log;

class MpesaClient
{
    /**
     * simulate C2B Payment Request
     * @param $ShortCode
     * @param $CommandID
     * @param $Amount
     * @param $BillRefNumber
     */
    static function c2b($ShortCode, $Amount, $Msisdn){
        $mpesa = new \Safaricom\Mpesa\Mpesa();
        $CommandID="CustomerPayBillOnline";
        $BillRefNumber="account";
        $c2bRequest=$mpesa->c2b($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber);
        Log::info('<<< c2b >>>'.$c2bRequest);
        return $c2bRequest;

    }


    static function getCallback(){
        $mpesa= new \Safaricom\Mpesa\Mpesa();
        $callbackData=$mpesa->finishTransaction();
        return $callbackData;
    }

    static function getTransactionStatus($TransactionID,$PartyA){
        $mpesa= new \Safaricom\Mpesa\Mpesa();
        $SecurityCredential=self::getSecurityCredentials();
        $Initiator=env("MPESA_C2B_INITIATOR");
        $CommandID="TransactionStatusQuery";

        $IdentifierType= 4;
        $ResultURL=env('MPESA_C2B_RESULT_URL');
        $QueueTimeOutURL=env('MPESA_C2B_QUEUETIMEOUT_URL');
        $Remarks="Status check ". $TransactionID;
        $Occasion="";

        $transactionStatus=$mpesa->transactionStatus($Initiator, $SecurityCredential, $CommandID, $TransactionID, $PartyA, $IdentifierType, $ResultURL, $QueueTimeOutURL, $Remarks, $Occasion);
        return $transactionStatus;
    }

    /**
     * Generate Security Credential token
     * by encrypting API password using the public cert
     * provided.
     *
     * @param envMode :sandox|production
     * return string
     */
    static function getSecurityCredentials(){
        $envMode =\env('MPESA_ENV');
        ($envMode=='sandbox') ? $fopen=fopen(storage_path("certs/cert.cer"),"r")
            : $fopen=fopen(storage_path("certs/Production.cer"),"r");

		$pub_key=fread($fopen,8192);
        fclose($fopen);
        $initiatorPass=\env("MPESA_SECURITY_CREDENTIAL");
        openssl_public_encrypt($initiatorPass,$crypttext,$pub_key);
        $crypted=\base64_encode($crypttext);
        return $crypted;
    }

    /**
     * C2B Register URL(s)
     * @param validationURL - Validation URL for API
     * @param confiramtionURL - Confirmateion URL for API
     * @param responseType - Default response type for timeout
     * @param shortCode - The shortcode i.e paybill
     */

    static  function registerURLS($confirmationURL,$validationURL,$shortCode){
            $mpesa= new \Safaricom\Mpesa\Mpesa();
        try {
            $environment = \env("MPESA_ENV");
        } catch (\Throwable $th) {
            $environment = self::env("MPESA_ENV");
        }

        if( $environment =="live"){
            $url = 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';
            $token=$mpesa->generateLiveToken();
        }elseif ($environment=="sandbox"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
            $token=$mpesa->generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }
        Log:info('check token >>>>'.\json_encode($token));
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
        array('Content-Type:application/json','Authorization:Bearer '.$token));
        //setting custom header


        $curl_post_data = array(
            //Fill in the request parameters with valid values
            'ShortCode' => $shortCode,
            'ResponseType' => 'Completed',
            'ConfirmationURL' =>$confirmationURL,
            'ValidationURL' => $validationURL
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);
        // print_r($curl_response);

        echo $curl_response;
    }
}
