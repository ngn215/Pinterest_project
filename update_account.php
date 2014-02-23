<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

    <title>Pinterest - My Account</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/signin.css" rel="stylesheet">


  </head>

  <body>

    <div class="container">

      <?php include("navigation_bar.php"); ?>
	  <?php include("main_modal.php"); ?>

      <!-- Main component for a primary marketing message or call to action -->
		<!--
		<div class="jumbotron">
        <h1>Navbar example</h1>
        <p>This example is a quick exercise to illustrate how the default, static navbar and fixed to top navbar work. It includes the responsive CSS and HTML, so it also adapts to your viewport and device.</p>
        <p>
          <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">View navbar docs &raquo;</a>
        </p>
      </div>
	  -->

    </div> <!-- /container -->
	

<!--copied from v1 -->

<div class="container">
<div class="content">
<?php



if(isset($_SESSION['user_id']))
	{
			$user_id=$_SESSION['user_id'];
			#$_SESSION['pinboard']="";
			
			$first_name=strval($_POST['txt_first_name']);
			$last_name=strval($_POST['txt_last_name']);
			$gender=strval($_POST['txt_gender']);
			$about_yourself=strval($_POST['txt_about_yourself']);
			$website=strval($_POST['txt_website']);
			$location=strval($_POST['txt_location']);
			$language=strval($_POST['txt_language']);
			$country=strval($_POST['txt_country']);
			
			
			if(isset($_POST['chk_email_notifications'])) 
			{
				$email_notifications="Y";
				
			}
			else if(!isset($_POST['chk_email_notifications'])) 
			{
				$email_notifications="N";
			}
			
				
			
			include("dbconnection.php");
			$first_name=mysqli_real_escape_string($con,$first_name);
			$last_name=mysqli_real_escape_string($con,$last_name);
			$gender=mysqli_real_escape_string($con,$gender);
			$about_yourself=mysqli_real_escape_string($con,$about_yourself);
			$website=mysqli_real_escape_string($con,$website);
			$location=mysqli_real_escape_string($con,$location);
			$language=mysqli_real_escape_string($con,$language);
			$country=mysqli_real_escape_string($con,$country);
			
			
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
						
		    $sql="update users
			set first_name='".$first_name."',
				last_name='".$last_name."',
				gender='".$gender."',
				about_yourself='".$about_yourself."',
				website='".$website."',
				location='".$location."',
				language='".$language."',
				country='".$country."',
				email_notifications='".$email_notifications."'
			where user_id=".$user_id."";
					
			
			
			
					
				if (!mysqli_query($con,$sql))
				{
				die('Error: ' . mysqli_error($con));
				}
				else
				{
				echo "successfully updated";
				$_SESSION['user_alert']="Successfully updated profile !";
				}

			}
		header( 'Location:myaccount.php' ) ;
	}
else
	{
	header( 'Location:login.php' ) ;
	}	
?>


</body>
</html>