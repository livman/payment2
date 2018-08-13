<?php

namespace App\Http\Controllers;

use Braintree_Transaction;
use Braintree_Customer;

use Illuminate\Http\Request;

use App\Paypal as Paypal;
use App\Braintree as Braintree;


class PaymentsController extends Controller
{
	protected function paypal($param = array())
	{
		//$checkoutSession = $request->session()->get('checkout', array());

		$paramPaypal = array(
			'auth' => array(
				'AZlu1oYTlPMRpATYdqUlTZzPEMVn-PgHQsCC-uauXR-tZ3GDYL8gZCzAZaGAMmHCADHlWKGD5XCmZ7zQ', 
				'EFgGUwexWJDqmDUUzJFlUGRiyHq48FcT9IgQI2iD4Jb9GovBfNXpFPCfe2dDWTLHgCNAd2j5dJUqgD54'
			),
			'checkoutInfo' => array(
				'amount' => $param['amount'],
				'currency' => $param['currency'],
			)
		);



		$paypalModel = new Paypal();
		$paypalModel->processSale($paramPaypal);

		$paypalModel->getPaymentInfo();

		$approval_url = $paypalModel->getApprovalUrl();

		if ( $approval_url == '' )
		{
			die('Error');
		}

		return $approval_url;
	}

	protected function braintree($param = array())
	{

		$braintreeModel = new Braintree();

		list($param['mm'], $param['yy']) = explode("/", $param['exp_date']);

		$info['vault'] = array(
			'customer' => array(
				'firstName' => $param['c_firstname'],
				'lastName' => $param['c_lastname'],
				'email' => $param['c_email'],
				'phone' => $param['c_phonenumber'],
			),
			'cc' => array(
				'number' => $param['card_number'],
				'holder' => $param['c_firstname'] .' '. $param['c_lastname'],
				'mm' => $param['mm'],
				'yy' => $param['yy'],
				'cvv' => $param['cvv'],
			)
		);

		$braintreeCustomerId = $braintreeModel->createVault($info);

		if( $braintreeCustomerId <= 0 )
		{
			// Error Something wrong
		}

		$info['sale'] = array(
			'customerId' => $braintreeCustomerId,
			'amount' => $param['amount']
		);

		return $braintreeModel->createSale($info);

	}

	public function payment_process(Request $request)
	{
		$param = $request->input();

		$currency = strtoupper($param['currency']);
		$card_number = $param['card_number'];

		// Validate card
		if ( !luhn_check($card_number) ) {
			die('Invalid Card');
		}

		$cardType = inspectCardType($card_number);

		$paypalGroupCurrencyType = array('USD', 'EUR', 'AUD');

		if ( in_array($currency, $paypalGroupCurrencyType) ) 
		{
			if ( $currency != 'USD' && $cardType == 'American' )
			{
				// AMEX is possible to use only for US
				die('AMEX is possible to use only for US');
			}
			
			// Use Paypal
			$approval_url = $this->paypal($param);

			return redirect($approval_url);

		}
		else
		{
			// Use Braintree
			$res = $this->braintree($param);

			$data['success'] = $res;
		}

		return response()->view('success', $data, 200);

	}

    public function process(Request $request)
	{
	    $payload = $request->input('payload', false);
	    print_r($payload); die;
	    $nonce = $payload['nonce'];

	    $status = Braintree_Transaction::sale([
			'amount' => '10.00',
			'paymentMethodNonce' => $nonce,
			'options' => [
			    'submitForSettlement' => True
			]
	    ]);

	    return response()->json($status);
	}
}
