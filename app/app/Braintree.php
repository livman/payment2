<?php

namespace App;

use Braintree_Transaction;
use Braintree_Customer;


class Braintree
{
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
