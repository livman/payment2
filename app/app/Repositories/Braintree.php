<?php

namespace App\Repositories;

use App\Interfaces\PaymentInterface;

use Braintree_Transaction;
use Braintree_Customer;

use App\Model\Braintree as BraintreeModel;

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
			$result = Braintree_Customer::create(array(
			  'firstName' => $info['vault']['customer']['firstName'],
			  'lastName'  => $info['vault']['customer']['lastName'],
			  'phone'     => $info['vault']['customer']['phone'],
			  'email'     => $info['vault']['customer']['email'],
			  'creditCard' => array(
			    'number'          => $info['vault']['cc']['number'],
			    'cardholderName'  => $info['vault']['cc']['holder'],
			    'expirationMonth' => $info['vault']['cc']['mm'],
			    'expirationYear'  => $info['vault']['cc']['yy'],
			    'cvv'             => $info['vault']['cc']['cvv'],
			
			  )
			));

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

	public function generateParam(array $param)
	{
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

		$info['sale'] = array('amount' => $param['amount']);

		return $info;
	}

	public function processSale(array $param)
	{
		$customerId = $this->createVault($param);


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