<html>

<body>

 
<?php
			session_start();
			
			if(isset($_SESSION['user_id']))
				{
					$pinboard_id=strval($_GET['pinboard_id']);
					$user_id=$_SESSION['user_id'];
					
					include("dbconnection.php");
			
					if (!$con)
					{
					  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
					  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
					}
					else
					{
					mysqli_select_db($con,$db_name);
								
					$sql="delete from user_follow_pinboard where user_id=".$user_id." and pinboard_id=".$pinboard_id."";
							
					//echo $sql;
								
					if (!mysqli_query($con,$sql))
						{
						die('Error: ' . mysqli_error($con));
						}
						else
						{
						echo "successfully deleted values";
						$_SESSION['user_alert']="Successfully unfollowed pinboard !";
						}
					}
					
					mysqli_close($con);
					//header("Location:myfriends.php");
				}
				else
				{
				header("Location:login.php");
				}


?>		

</body>
</html>	