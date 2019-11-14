<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lodgement extends Model
{
    	protected $fillable = [
		"transaction_type",
		"trans_id",
		"trans_time",
		"trans_amount",
		"business_shortcode",
		"bill_refnumber",
		"invoice_number",
		"orgaccount_balance",
		"thirdpartytrans_id",
		"msisdn",
		"first_name",
		"middle_name",
		"last_name",
	];

	function customerName(){
		return "{$this->FirstName} {$this->MiddleName} {$this->LastName}";
	}
}
