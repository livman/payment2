<html>
   <head>
      <title>Login Form</title>
   </head>
   <body>
      @if (count($errors) > 0)
         <div class = "alert alert-danger">
            <ul>
               @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
      @endif

      <?php
         echo Form::open(array('url'=>'/checkout'));
      ?>

      <table border = '1'>

         <tr>
            <td align='center' colspan='2'>Checkout</td>
         </tr>
         
         <tr>
            <td>First name</td>
            <td><?php echo Form::text('firstname'); ?></td>
         </tr>

          <tr>
            <td>Last name</td>
            <td><?php echo Form::text('lastname'); ?></td>
         </tr>

         <tr>
            <td>Currency</td>
            <td><?php echo Form::select('currency', array('USD' => 'USD', 'THB' => 'THB'), 'USD'); ?></td>
         </tr>

         <tr>
            <td>Amount</td>
            <td><?php echo Form::text('amount'); ?></td>
         </tr>
           
         <tr>
            <td align = 'center' colspan = '2'>
               <?php echo Form::submit('Process'); ?></td>
         </tr>
      </table>

      <?php
         echo Form::close();
      ?>
      
   </body>
</html>
