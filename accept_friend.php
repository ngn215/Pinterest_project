
<?php
			session_start();
			
			if(isset($_SESSION['user_id']))
	{
			
			$user_id=$_SESSION['user_id'];
			$friend_id=strval($_POST['accept_friend_button']);
				
			include("dbconnection.php");
			$friend_id = mysqli_real_escape_string($con,$friend_id);
	
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
						
		    $sql="update friends set status='A' where friend_id=".$user_id." and user_id=".$friend_id."";
					
			//echo $sql;
						
			if (!mysqli_query($con,$sql))
				{
				die('Error: ' . mysqli_error($con));
				}
				else
				{
				echo "successfully updated";
				$_SESSION['user_alert']="Friend request accepted !";
				}
			}

			mysqli_close($con);
			header("Location:myfriends.php");
	}
	else
	{
	echo "session expired";
	header( 'Location:home.php' ) ;
	}
			
?>			