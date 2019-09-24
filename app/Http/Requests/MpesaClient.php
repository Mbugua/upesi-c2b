<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Safaricom\Mpesa\Mpesa;
use Illuminate\Support\Facades\Log;

class MpesaClient
{
    /**
     * C2B Payment Request
     * @param $ShortCode
     * @param $CommandID
     * @param $Amount
     * @param $BillRefNumber
     */
    static function requestC2B($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber){
        Log::info('[MpesaClient::requestc2B] >> simulate c2b payment');
        $mpesa = new \Safaricom\Mpesa\Mpesa();
        $c2bRequest=$mpesa->c2b($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber);
        return $c2bRequest;

    }


    static function getCallback(){
        $mpesa= new \Safaricom\Mpesa\Mpesa();
        $callbackData=$mpesa->finishTransaction();
        return $callbackData;
    }

    static function getTransactionStatus(){
        $mpesa= new \Safaricom\Mpesa\Mpesa();

        $transactionStatus=$mpesa->transactionStatus($Initiator, $SecurityCredential, $CommandID, $TransactionID, $PartyA, $IdentifierType, $ResultURL, $QueueTimeOutURL, $Remarks, $Occasion);


        return $transactionStatus;
    }



}
