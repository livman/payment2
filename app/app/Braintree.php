<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Braintree_Transaction;
use Braintree_Customer;


class Braintree extends Model
{

	protected $table = 'braintree';

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
    			//print_r($created_at); die;
    			$data = array(
    				'merchantId' => $result->customer->merchantId,
    				'firstName' => $result->customer->firstName,
    				'lastName' => $result->customer->lastName,
    				'email' => $result->customer->email,
    				'cardType' => $result->customer->creditCards[0]->cardType,
    				'expirationDate' => $result->customer->creditCards[0]->expirationDate,
    				'created_at' => $created_at,
    				'updated_at' => $updated_at,
    			);
    			$this->saveBraintreeRecord($data);
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

    public function saveBraintreeRecord($data = array())
    {
    	try {
    		
    		$this->merchantId = $data['merchantId'];
    		$this->firstName = $data['firstName'];
    		$this->lastName = $data['lastName'];
    		$this->email = $data['email'];
    		$this->cardType = $data['cardType'];
    		$this->expirationDate = $data['expirationDate'];
    		$this->created_at = $data['created_at'];
    		$this->updated_at = $data['updated_at'];
    		$this->save();

    	} catch (Exception $e) {
    		echo 'Message: ' .$e->getMessage(); die;
    	}
        
        return true;
    }

    public function createSale($param = array())
    {

    	$sale = array(
    	      'customerId' => $param['sale']['customerId'],
    	      'amount'   => $param['sale']['amount'],
    	      'options' => array('submitForSettlement' => true)
    	    );

    	try {

    		$result = Braintree_Transaction::sale($sale);

    		if ($result->success)
    		{
    		  // Execute on payment success event at here
    			print_r($result); die;
    		}
    		else
    		{
    		  echo "Error : ".$result->_attributes['message'];
    		}

    	} catch (Exception $e) {

    		
    		echo 'Message: ' .$e->getMessage(); die;
    	}
    	          
    	

    	
    }

}
