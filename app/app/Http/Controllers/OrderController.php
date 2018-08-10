<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderPostRequest;

class OrderController extends Controller
{
    public function showform() {
      return view('orderForm');
   }

   public function process(OrderPostRequest $request) { 

   		$formData = $request->all();
   		unset($formData['_token']);

   		$request->session()->put('order', $formData );

   		$value = $request->session()->get('order', array());

   		return redirect('/');

   }


}
