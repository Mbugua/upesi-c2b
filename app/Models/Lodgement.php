<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lodgement extends Model
{
		protected $table='lodgements';
    	protected $fillable = [
			'customer_name',
			'msisdn',
			'mpesa_ref',
			'amount',
			'trans_time',
			'transaction_type',
			'business_shortcode',
			'bill_refnumber'

	];

}
