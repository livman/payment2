<?php

namespace App\Repositories;

use App\Interfaces\PaymentInterface;


use Braintree_Transaction;
use Braintree_Customer;

use App\Model\Braintree as BraintreeModel;
use App\Repositories\BraintreeDataInput;

Class Braintree implements PaymentInterface
{


	public function logRecord(array $param)
	{
		// Log to db

		return true;
	}

	public function createVault($info = array())
	{
		try {

			// Create customer in braintree Vault
			$result = Braintree_Customer::create($info);

			if ($result->success) 
			{
				// Save this Braintree_cust_id in DB and use for future transactions too
				foreach($result->customer->creditCards[0]->createdAt as $key => $item) 
				{
					if($key == 'date')
					{
						$created_at = date('Y-m-d H:i:s', strtotime($item));
						break;
					}
				}
				foreach($result->customer->creditCards[0]->updatedAt as $key => $item) 
				{
					if($key == 'date')
					{
						$updated_at = date('Y-m-d H:i:s', strtotime($item));
						break;
					}
				}

				return $result->customer->id;
			} 
			else 
			{
				return 0;
			}

		} catch (Exception $e) {
			echo 'Message: ' .$e->getMessage(); die;
		}
	
	}

	public function processSale(BraintreeDataInput $dataInputInstance)
	{
		$customerId = $this->createVault($dataInputInstance->getDataInput());


		if( $customerId <= 0 )
		{
			die('Invalid customer');
		}

		$sale = array(
		      'customerId' => $customerId,
		      'amount'   => $param['sale']['amount'],
		      'options' => array('submitForSettlement' => true)
		    );

		try {

			$result = Braintree_Transaction::sale($sale);

			if ($result->success)
			{
			  // Execute on payment success event at here
				return true;
			}
			else
			{
				return false;
			}

		} catch (Exception $e) {

			
			echo 'Message: ' .$e->getMessage(); die;
		}
	}

}