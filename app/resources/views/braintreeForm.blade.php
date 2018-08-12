<!--
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Braintree-Demo</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script>

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <div class="container">
     <div class="row">
       <div class="col-md-8 col-md-offset-2">
         <div id="dropin-container"></div>
         <button id="submit-button">Request payment method</button>
       </div>
     </div>
  </div>
  <script>
    $(document).ready(function(){
        $('#braintree-hosted-field-number').load(function(){
            console.log($('#braintree-hosted-field-number').contents().find("#credit-card-number").val());
        });
      });

    var button = document.querySelector('#submit-button');

    braintree.dropin.create({
      authorization: "{{ Braintree_ClientToken::generate() }}",
      container: '#dropin-container'
    }, function (createErr, instance) {
      button.addEventListener('click', function () {
        instance.requestPaymentMethod(function (err, payload) {
          $.get('{{ route('payment.process') }}', {payload}, function (response) {
            if (response.success) {
              alert('Payment successfull!');
            } else {
              alert('Payment failed');
            }
          }, 'json');
        });
      });
    });
  </script>
</body>
</html>
-->

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout</title>
</head>
<body>
<link href="{{ URL::asset('css/style.css') }}" type="text/css" rel="stylesheet" />
<h1 class="bt_title">Braintree Custom Integration</h1>
<div class="dropin-page">
  <form id="checkout" method="post" action="{{ route('payment.payment_process') }}">
    <h4 class="bt_title">Customer Information</h4>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="invoiceid" value="123456">
     <fieldset class="one_off_firstname">
      <label class="input-label" for="firstname">
      <span class="field-name">First Name</span>
      <input id="c_firstname" name="c_firstname" class="input-field card-field" type="text" placeholder="First Name" autocomplete="off">
      <div class="invalid-bottom-bar"></div>
      </label>
    </fieldset>
    <fieldset class="one_off_lastname">
      <label class="input-label" for="lastname">
      <span class="field-name">Last Name</span>
      <input id="c_lastname" name="c_lastname" class="input-field card-field" type="text" placeholder="Last Name" autocomplete="off">
      <div class="invalid-bottom-bar"></div>
      </label>
    </fieldset>
    <fieldset class="one_off_lastname">
      <label class="input-label" for="email">
      <span class="field-name">Email</span>
      <input id="c_email" name="c_email" class="input-field card-field" type="text" placeholder="Email" autocomplete="off">
      <div class="invalid-bottom-bar"></div>
      </label>
    </fieldset>
    <fieldset class="one_off_phonenumber">
      <label class="input-label" for="phonenumber">
      <span class="field-name">Phone Number</span>
      <input id="c_phonenumber" name="c_phonenumber" class="input-field card-field" type="text"placeholder="Phone Number" autocomplete="off">
      <div class="invalid-bottom-bar"></div>
      </label>
    </fieldset>
    <h4 class="bt_title">Credit Card Details</h4>
    <fieldset class="one_off_country">
      <label class="input-label" for="country">
      <span class="field-name">Card number</span>
      <input id="card_number" name="card_number" class="input-field card-field" type="text" placeholder="Card number" autocomplete="off">
      <div class="invalid-bottom-bar"></div>
      </label>
    </fieldset>
    <fieldset class="one_off_country">
      <label class="input-label" for="country">
      <span class="field-name">CVV</span>
      <input id="CVV" name="cvv" class="input-field card-field" type="text" placeholder="CVV" autocomplete="off">
      <div class="invalid-bottom-bar"></div>
      </label>
    </fieldset>
    <fieldset class="one_off_country">
      <label class="input-label" for="country">
      <span class="field-name">Expiration date (MM/YY)</span>
      <input id="exp_date" name="exp_date" class="input-field card-field" type="text" placeholder="Expiration date (MM/YY)" autocomplete="off">
      <div class="invalid-bottom-bar"></div>
      </label>
    </fieldset>
    <fieldset class="one_off_currency">
      <label class="input-label" for="currency">
      <span class="field-name">Currency</span>
      <input id="currency" name="currency" class="input-field card-field" type="text" placeholder="Currency" autocomplete="off" step="any">
      <div class="invalid-bottom-bar"></div>
      </label>
    </fieldset>
    <fieldset class="one_off_amount">
      <label class="input-label" for="amount">
      <span class="field-name">Amount</span>
      <input id="amount" name="amount" class="input-field card-field" type="number" inputmode="numeric" placeholder="Amount" autocomplete="off" step="any">
      <div class="invalid-bottom-bar"></div>
      </label>
    </fieldset>
    <div class="btn_container">
      <input type="submit" name="make_payment" value="Make Payment" class="pay-btn">
      <span class="loader_img"></span> </div>
  </form>
</div>
</body>
</html>