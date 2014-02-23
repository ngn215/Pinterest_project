<html>

<body>

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
		
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
				mysqli_select_db($con,$db_name);
				
				$sql="insert into friends(user_id,friend_id,status) values(".$user_id.",".$friend_id.",'S')";
								
				if (!mysqli_query($con,$sql))
				{
				die('Error: ' . mysqli_error($con));
				}
								
				
				mysqli_close($con);
				$_SESSION['user_alert']="Friend request successfully !";
				header("location:myfriends.php");
				
			}
	}
	else
	{
	header( 'Location:home.php' ) ;
	//echo "<p>failed</p>";
	}
	

?>
</body>
</html>




