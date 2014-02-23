<!DOCTYPE html>
<html lang="en">
  <head>


    <title>Pinterest - Login</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
	
	<script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

  </head>

  <body>

    <div class="container">
	
	<div class="jumbotron">
	<h1><font style="font-family:Lucida Handwriting; color:rgb(205,31,40)">Pinterest</font></h1>
	<p>Pin your pictures. Share/Like/Comment on pictures.</p>
	<a href="explore.php">Explore Pinterest</a>
	</div>
	
	
	
	<?php
	session_start();
?>
 
	
      <form class="form-signin" role="form" action="checklogin.php" method="post">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" name="txt_email" class="form-control" placeholder="Email address" required autofocus>
        <input type="password" name="txt_password" class="form-control" placeholder="Password" required>
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
		
		<?php
	   if(isset($_SESSION['user_alert']))
	{
	?>
	<div class="alert alert-danger alert-dismissable">
	  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	  <strong>Message:</strong> <?php echo $_SESSION['user_alert']; ?>
	</div>
	<?php
	unset($_SESSION['user_alert']);
	}
	?>
		
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		<br>
		<a href="register.php" id="register">New User?</a>
	  </form>
    </div> <!-- /container -->

  </body>
</html>
