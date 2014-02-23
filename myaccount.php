<html>
  <head>

    <title>Pinterest - My Account</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/signin.css" rel="stylesheet">


  </head>

  <body>

    <div class="container">

      <?php include("navigation_bar.php"); ?>
	  <?php include("main_modal.php"); ?>



    </div> <!-- /container -->
	

<!--copied from v1 -->

<div class="container">
<div class="content">

<?php


  if(isset($_SESSION['user_alert']))
	{
	?>
	<div class="alert alert-success alert-dismissable" id="alert_success">
	  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	  <strong>Message:</strong> <?php echo $_SESSION['user_alert']; ?>
	</div>
	<?php
	unset($_SESSION['user_alert']);
	}
	?>
	
<?php

if(isset($_SESSION['user_id']))
	{
			$user_id=$_SESSION['user_id'];
			#$_SESSION['pinboard']="";
	
			$db_name="pinterest"; 												//database name
			$con = mysqli_connect("localhost:3306","root","root",$db_name);		//create connection
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
						
		    $sql="select first_name,last_name,email_id,date_of_joining,gender,about_yourself,website,location,language,country,email_notifications
			from users where user_id=".$user_id."";
			
			$result = mysqli_query($con,$sql) or die(mysql_error());
									
			$count=mysqli_num_rows($result);
			
				if($count==0)
					{
					echo "<p> <font color='red' style='bold'> error ! </font> </p>";
					#header( 'Location:home.php' ) ;
					}
				else
					{
						while($row = mysqli_fetch_array($result)) 
						{
						?>
						<form id="form_profile" class="form-signin" role="form" action="update_account.php" method="post" style="float:left">
						<h2 class="form-signin-heading">Profile</h2>
						First Name <input type="text" name="txt_first_name" class="form-control" required value="<?php echo "".$row['first_name'].""; ?>" required> 
						<br>
						Last Name <input type="text" name="txt_last_name" class="form-control" value="<?php echo "".$row['last_name'].""; ?>" required>
						<br>
						Email id <input type="text" name="txt_email" disabled="true" class="form-control" value="<?php echo "".$row['email_id'].""; ?>">
						<br>
						Date of Joining <input type="text" name="txt_date_of_joining" disabled="true" class="form-control" value="<?php echo "".$row['date_of_joining'].""; ?>">
						<br>
						Gender <input type="text" name="txt_gender" class="form-control" value="<?php echo "".$row['gender'].""; ?>">
						<br>
						About Yourself <textarea rows=3 cols=42 name="txt_about_yourself"><?php echo "".$row['about_yourself'].""; ?></textarea>
						<!-- <input type="textarea" name="txt_about_yourself" class="form-control" value="<?php echo "".$row['about_yourself'].""; ?>"> -->
						<br>
						Website<input type="text" name="txt_website" class="form-control" value="<?php echo "".$row['website'].""; ?>">
						<br>
						Location<input type="text" name="txt_location" class="form-control" value="<?php echo "".$row['location'].""; ?>">
						<br>
						Language<input type="text" name="txt_language" class="form-control" value="<?php echo "".$row['language'].""; ?>">
						<br>
						Country<input type="text" name="txt_country" class="form-control" value="<?php echo "".$row['country'].""; ?>">
						<br>
						<label class="checkbox">
						<input type="checkbox" name="chk_email_notifications" <?php if ($row['email_notifications']=="Y") echo "checked"; else echo ""; ?>>
						Email Notifications
						</label>
						<button class="btn btn-lg btn-primary btn-block" type="submit" disabled="true" id="profile_update_button">Update</button>
						</form>
						
						<?php
						}
					}
			}
	}
else
	{
	header( 'Location:login.php' ) ;
	}	
?>



	<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>


<script>

$(document).ready(function() {

	$("#myaccount1").toggleClass("active", true);

    $(".content").css("display", "none");
 
    $(".content").fadeIn(1000);
 
    $("a.transition").click(function(event){
        event.preventDefault();
        linkLocation = this.href;
        $(".content").fadeOut(1000, redirectPage);     
    });
	
	$("a.navbar-brand").click(function(event){
		event.preventDefault();
		linkLocation = this.href;
		$(".content").fadeOut(1000, redirectPage);     
    });
         
    function redirectPage() {
        window.location = linkLocation;
    }
	
	//$('#form_profile').find('input, textarea, select').attr('disabled','disabled');
	
	
});


$("#logout_button").click(function(){
  $.get('logout.php');
  $(".content").css("display", "none");
  alert( "Logged out sucessfully !" );
  window.location.href = "login.php";
});


$("#form_profile").change(function() {

	$("#profile_update_button").prop("disabled",false);
	
});



</script>

</body>
</html>