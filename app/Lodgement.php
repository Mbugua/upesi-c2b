<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lodgement extends Model
{
    	protected $fillable = [
		"TransactionType",
		"TransID",
		"TransTime",
		"TransAmount",
		"BusinessShortCode",
		"BillRefNumber",
		"InvoiceNumber",
		"OrgAccountBalance",
		"ThirdPartyTransID",
		"MSISDN",
		"FirstName",
		"MiddleName",
		"LastName",
		"responseCode",
		"responseMessage",
	];

	function customerName(){
		return "{$this->FirstName} {$this->MiddleName} {$this->LastName}";
	}
}
