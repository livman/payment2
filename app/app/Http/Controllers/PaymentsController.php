<?php

namespace App\Http\Controllers;

use Braintree_Transaction;

use Illuminate\Http\Request;

use App\Paypal as Paypal;


class PaymentsController extends Controller
{
	public function paypal(Request $request)
	{
		$checkoutSession = $request->session()->get('checkout', array());

		$param = array(
			'auth' => array(
				'AZlu1oYTlPMRpATYdqUlTZzPEMVn-PgHQsCC-uauXR-tZ3GDYL8gZCzAZaGAMmHCADHlWKGD5XCmZ7zQ', 
				'EFgGUwexWJDqmDUUzJFlUGRiyHq48FcT9IgQI2iD4Jb9GovBfNXpFPCfe2dDWTLHgCNAd2j5dJUqgD54'
			),
			'checkoutInfo' => $checkoutSession
		);

		$paypalModel = new Paypal();
		$paypalModel->processSale($param);

		$approval_url = $paypalModel->getApprovalUrl();

		if ( $approval_url == '' )
		{
			die('Error');
		}

		return redirect($approval_url);
	}

    public function process(Request $request)
	{
	    $payload = $request->input('payload', false);
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
