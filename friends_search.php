<html>
<head>
<link href="css/bootstrap-glyphicons.css" rel="stylesheet">
</head>
<body>

<?php

session_start();

if(isset($_SESSION['user_id']))
	{	
		$user_id=$_SESSION['user_id'];
		$friend_name = strval($_GET['value']);
				
		//echo $pinboard_id;		
		//database code begins here

		include("dbconnection.php");
		$friend_name=mysqli_real_escape_string($con,$friend_name);
		
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
			
			$sql="SELECT user_id,first_name,last_name FROM users where (first_name like '%!".$friend_name."%' ESCAPE '!' or 
					last_name like '%!".$friend_name."%' ESCAPE '!')
					and user_id not in (select friend_id from friends where user_id=".$user_id.")
					and user_id not in (select user_id from friends where friend_id=".$user_id.") and user_id<>".$user_id."";
							
			$result = mysqli_query($con,$sql) or die(mysql_error());
							
			$count=mysqli_num_rows($result);
			
			if($count==0)
				{
				?>
				<div class="alert alert-warning" id="alert_success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong></strong> No matches found. Results will not appear for friends to whom requests have already been sent or people who already are your friends.
				</div>
				<?php
				}
			else
				{
				echo "<br>";
				echo "Click name of friend to send him friend request (Below list contains only those names which are not your friends currently)";
				?>
					<table border=0>
				<?php	
					while($row = mysqli_fetch_array($result))
					{
					?>
					<tr>
					<td><a href="send_friend_request.php?value=<?php echo "".$row['user_id'].""; ?>">
					<?php echo "".$row['first_name']." ".$row['last_name'].""; ?></a>
					</tr>
					<?php
					}
					?>
					</table>
				<?php
				//database code ends here
				}
				
			}
			mysqli_close($con);
				
	}
	else
	{
	header( 'Location:home.php' ) ;
	//echo "<p>failed</p>";
	}
	

?>
</body>
</html>




