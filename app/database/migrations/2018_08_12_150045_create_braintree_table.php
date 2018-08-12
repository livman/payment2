<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBraintreeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('braintree', function (Blueprint $table) {
            $table->increments('id');
            $table->string('merchantId');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('email');
            $table->string('cardType');
            $table->string('expirationDate');
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
        Schema::dropIfExists('braintree');
    }
}
