
<?php
			session_start();
			
			if(isset($_SESSION['user_id']))
	{
			
			$user_id=$_SESSION['user_id'];
			
			$curpageURL = 'http';
			if ($_SERVER["HTTPS"] == "on") {$curpageURL.= "s";}
			$curpageURL.= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
			$curpageURL.= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
			$curpageURL.= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}
			$url= $curpageURL;
			
			$parts = parse_url($url);
			parse_str($parts['query'], $query);
			$friend_id=$query['value'];	
				
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
						
		    $sql="delete from friends where ((user_id=".$user_id." and friend_id=".$friend_id.") or (friend_id=".$user_id." and user_id=".$friend_id."))
			and status='A'";
					
			//echo $sql;
						
			if (!mysqli_query($con,$sql))
				{
				die('Error: ' . mysqli_error($con));
				}
				else
				{
				echo "successfully deleted";
				$_SESSION['user_alert']="Friend deleted !";
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