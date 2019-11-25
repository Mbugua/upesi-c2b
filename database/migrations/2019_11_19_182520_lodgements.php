<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Lodgements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lodgements',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('customer_name');
            $table->string('msisdn');
            $table->string('mpesa_ref');
            $table->float('amount');
            $table->dateTimeT('trans_time');
            $table->string('transaction_type');
            $table->string('business_shortcode');
            $table->string('bill_refnumber');
            // $table->string('invoice_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lodgements');
    }
}
