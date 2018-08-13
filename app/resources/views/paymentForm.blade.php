
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>
  <script src="{{ URL::asset('js/jquery.payment.js') }}"></script>

  <style type="text/css" media="screen">
     .has-error input {
       border-width: 2px;
     }
     .validation.text-danger:after {
       content: 'Validation failed';
     }
     .validation.text-success:after {
       content: 'Validation passed';
     }
   </style>

   <script>

    function checkEmptyInput(object)
    {
      if(object.val() == '')
      {
        object.toggleInputError(true); 
      }
      else
      {
        object.toggleInputError(false); 
      }
    }

    function submitForm()
    {
      var cardType = $.payment.cardType($('.cc-number').val());
      $('.cc-number').toggleInputError(!$.payment.validateCardNumber($('.cc-number').val()));
      $('.cc-exp').toggleInputError(!$.payment.validateCardExpiry($('.cc-exp').payment('cardExpiryVal')));
      $('.cc-cvc').toggleInputError(!$.payment.validateCardCVC($('.cc-cvc').val(), cardType));
      $('.cc-brand').text(cardType);
      $('.validation').removeClass('text-danger text-success');

      checkEmptyInput($('#c_firstname'));
      checkEmptyInput($('#c_lastname'));
      checkEmptyInput($('#c_email'));
      checkEmptyInput($('#c_phonenumber'));
      checkEmptyInput($('#currency'));
      checkEmptyInput($('#amount'));

      
      

      if( $('.has-error').length  )
      {
         $('.validation').addClass('text-danger');
         //$('form').preventDefault();
         return false;
      } else {
         //$('form').submit();
         return true;
      }
    }


     jQuery(function($) {

      $('#amount').keypress(function (e) {

        var code = !e.charCode ? e.which : e.charCode;
        var value = $('#amount').val();


          var regex = new RegExp("^[0-9.]+$");
          var str = String.fromCharCode(code);
          if (regex.test(str)) {
              return true;
          }

          e.preventDefault();
          return false;
      });

       //$('[data-numeric]').payment('restrictNumeric');
       
       
       $('.cc-number').payment('formatCardNumber');
       $('.cc-exp').payment('formatCardExpiry');
       $('.cc-cvc').payment('formatCardCVC');
       $.fn.toggleInputError = function(erred) {
         this.parent('.form-group').toggleClass('has-error', erred);
         return this;
       };


     });
   </script>

<title>Checkout</title>
</head>
<body>
<link href="{{ URL::asset('css/style.css') }}" type="text/css" rel="stylesheet" />
<h1 class="bt_title">Payment Info</h1>
<div class="dropin-page">


  <form novalidate autocomplete="on" method="POST" onsubmit="return submitForm()" action="{{ route('payment.payment_process') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-group">
          <label for="c_firstname" class="control-label">First Name</label>
          <input id="c_firstname" name="c_firstname" type="tel" class="input-lg form-control cc-firstname" required>
        </div>

        <div class="form-group">
          <label for="c_lastname" class="control-label">Last Name</label>
          <input id="c_lastname" name="c_lastname" type="tel" class="input-lg form-control" required>
        </div>

        <div class="form-group">
          <label for="c_email" class="control-label">Email</label>
          <input id="c_email" name="c_email" type="tel" class="input-lg form-control" required>
        </div>

        <div class="form-group">
          <label for="c_phonenumber" class="control-label">Phone Number</label>
          <input id="c_phonenumber" name="c_phonenumber" type="tel" class="input-lg form-control" required>
        </div>


        <div class="form-group">
          <label for="cc-number" class="control-label">Card number formatting <small class="text-muted">[<span class="cc-brand"></span>]</small></label>
          <input id="cc-number" name="card_number" type="tel" class="input-lg form-control cc-number" autocomplete="cc-number" placeholder="•••• •••• •••• ••••" required>
        </div>

        <div class="form-group">
          <label for="cc-exp" class="control-label">Card expiry formatting</label>
          <input id="cc-exp" type="tel" name="exp_date" class="input-lg form-control cc-exp" autocomplete="cc-exp" placeholder="•• / ••" required>
        </div>

        <div class="form-group">
          <label for="cc-cvc" class="control-label">Card CVC formatting</label>
          <input id="cc-cvc" type="tel" name="cvv" class="input-lg form-control cc-cvc" autocomplete="off" placeholder="•••" required>
        </div>

        <div class="form-group">
          <label for="currency" class="control-label">Currency</label>
          <input id="currency" name="currency" type="tel" class="input-lg form-control" required>
        </div>

        <div class="form-group">
          <label for="amount" class="control-label">Amount</label>
          <input id="amount" name="amount" type="tel" class="input-lg form-control" data-amount required>
        </div>


        <button type="submit" id="submit" class="btn btn-lg btn-primary">Submit</button>

        <h2 class="validation"></h2>
      </form>

</div>
</body>
</html>