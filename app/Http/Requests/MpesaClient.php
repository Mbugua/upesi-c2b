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
    static function requestC2B($ShortCode, $Amount, $Msisdn){
        $mpesa = new \Safaricom\Mpesa\Mpesa();
        $CommandID="CustomerPayBillOnline";
        $BillRefNumber="account";
        $c2bRequest=$mpesa->c2b($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber);
        return $c2bRequest;

    }


    static function getCallback(){
        $mpesa= new \Safaricom\Mpesa\Mpesa();
        $callbackData=$mpesa->finishTransaction();
        return $callbackData;
    }

    static function getTransactionStatus($TransactionID,$PartyA){
        $mpesa= new \Safaricom\Mpesa\Mpesa();
        $SecurityCredential=self::getSecurityCredential(false);
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

    static function getSecurityCredential($devMode=true){
		($devMode) ? $fopen=fopen(storage_path("certs/sandboxcert.cer"),"r")
            : $fopen=fopen(storage_path("certs/production.cer"),"r");

		$pub_key=fread($fopen,8192);
        fclose($fopen);

        openssl_public_encrypt(env("MPESA_SECURTIY_CREDENTIAL"),$crypttext, $pub_key );

		return(base64_encode($crypttext));

    }

}
