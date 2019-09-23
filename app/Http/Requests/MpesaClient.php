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
        Log::info('[MpesaClient::requestc2B] >> initialize a new Mpesa object');
        $mpesa = new \Safaricom\Mpesa\Mpesa();
        $c2bRequest=$mpesa->c2b($ShortCode, $CommandID, $Amount, $Msisdn, $BillRefNumber);
        // $callbackData=$mpesa->getDataFromCallback();

        return $c2bRequest;

    }


    static function callback(){
        $mpesa= new \Safaricom\Mpesa\Mpesa;

        $callbackData=$mpesa->getDataFromCallback();
        return $callbackData;
    }

}
