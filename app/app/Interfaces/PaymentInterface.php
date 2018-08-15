<?php

namespace App\Interfaces;

use App\Payment;

interface PaymentInterface {

	public function processSale(Payment $payment, array $param);



}