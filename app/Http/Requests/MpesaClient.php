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
        Log::info('security credential >> '.$SecurityCredential);
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
}
