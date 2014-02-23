
 
<?php
			session_start();
			
			if(isset($_SESSION['user_id']))
				{
					$pinboard_id=strval($_GET['pinboard_id']);
					$user_id=$_SESSION['user_id'];
					
					include("dbconnection.php");
					$pinboard_id=mysqli_real_escape_string($con,$pinboard_id);
			
					if (!$con)
					{
					  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
					  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
					}
					else
					{
					mysqli_select_db($con,$db_name);
								
					$sql="insert into user_follow_pinboard(user_id,pinboard_id,followed_on) values(".$user_id.",".$pinboard_id.",sysdate())";
							
					//echo $sql;
								
					if (!mysqli_query($con,$sql))
						{
						die('Error: ' . mysqli_error($con));
						}
						else
						{
						echo "successfully inserted values";
						$_SESSION['user_alert']="You are now following this pinboard !";
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

