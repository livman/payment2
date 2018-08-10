<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CheckoutPostRequest;

class CheckoutController extends Controller
{
    public function showform() {
      return view('checkoutForm');
   }

   public function process(CheckoutPostRequest $request) { 

   		$formData = $request->all();
   		unset($formData['_token']);

   		$request->session()->put('checkout', $formData );

   		//$value = $request->session()->get('checkout', array());

   		return redirect('/');

   }
}
