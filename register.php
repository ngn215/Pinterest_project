<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

    <title>New User Registration</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

  </head>

  <body>

    <div class="container">

	<div class="jumbotron">
	<h1><font style="font-family:Lucida Handwriting; color:rgb(205,31,40)">Pinterest</font></h1>
	<p>Pin your pictures. Share/Like/Comment on pictures.</p>
	</div>
	
	<?php
	session_start();

  if(isset($_SESSION['user_alert']))
	{
	?>
	<div class="alert alert-info alert-dismissable">
	  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	  <strong>Message:</strong> <?php echo $_SESSION['user_alert']; ?>
	</div>
	<?php
	unset($_SESSION['user_alert']);
	}
	?>	
	
      <form class="form-signin" role="form" action="register_submit.php" method="post">
        <h2 class="form-signin-heading">Register</h2>
		<input type="text" name="txt_first_name" class="form-control" placeholder="First Name" required autofocus>
		<input type="text" name="txt_last_name" class="form-control" placeholder="Last Name" required autofocus>
        <input type="text" name="txt_email" class="form-control" placeholder="Email address" required autofocus>
        <input type="password" name="txt_password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
		<br>
		</form>

    </div> <!-- /container -->

  </body>
</html>
