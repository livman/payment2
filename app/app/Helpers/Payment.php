<?php

if( !function_exists('luhn_check') ) 
{
	function luhn_check($number) {

	  // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
	  $number=preg_replace('/\D/', '', $number);

	  // Set the string length and parity
	  $number_length=strlen($number);
	  $parity=$number_length % 2;

	  // Loop through each digit and do the maths
	  $total=0;
	  for ($i=0; $i<$number_length; $i++) {
	    $digit=$number[$i];
	    // Multiply alternate digits by two
	    if ($i % 2 == $parity) {
	      $digit*=2;
	      // If the sum is two digits, add them together (in effect)
	      if ($digit > 9) {
	        $digit-=9;
	      }
	    }
	    // Total up the digits
	    $total+=$digit;
	  }

	  // If the total mod 10 equals 0, the number is valid
	  return ($total % 10 == 0) ? TRUE : FALSE;

	}
}

if ( !function_exists('inspectCardType') ) 
{
	function inspectCardType($cc_num) 
	{
		$cardTypePettern = array(
			'american' => "/^([34|37]{2})([0-9]{13})$/",         //American Express
			'dinners' => "/^([30|36|38]{2})([0-9]{12})$/",       //Diner's Club
			'discover' => "/^([6011]{4})([0-9]{12})$/",          //Discover Card
			'visa' => "/^([4]{1})([0-9]{12,15})$/",              //Visa
			'master' => "/^([51|52|53|54|55]{2})([0-9]{14})$/",  //Mastercard
			
		);

		$cardType = 'Invalid Card';

		foreach( $cardTypePettern as $type => $pattern )
		{
			if (preg_match($pattern, $cc_num) )
			{
				$cardType = ucfirst($type);
				break;
			}
		}

		return $cardType;

	}
}

if ( !function_exists('getPaymentProvider') )
{
	function getPaymentProvider(array $cardInfo, array $paypalGroupCurrencyType)
	{
		$cardType = inspectCardType($cardInfo['cardNumber']);

		if ( in_array($cardInfo['currencyType'], $paypalGroupCurrencyType) ) 
		{
			if ( $cardInfo['currencyType'] != 'USD' && $cardType == 'American' )
			{
				// AMEX is possible to use only for US
				throw new \Exception('AMEX is possible to use only for US');
			}

			return 'Paypal';
		}
		else
		{
			return 'Braintree';
		}


	}
}