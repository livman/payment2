<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Services\PaymentService;


class PaymentsController extends Controller
{
	private $_paymentService;

	public function __construct()
	{
		$this->_paymentService = new PaymentService();
	}

	public function payment_process(Request $request)
	{
		$param = $request->input();

		$res = $this->_paymentService->create($param);

		if( $res === true )
		{
			$data['success'] = $res;
			return response()->view('success', $data, 200);
		}

		return redirect($paymentProvider->getApprovalUrl());

	}

}
