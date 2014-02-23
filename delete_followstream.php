
<?php

session_start();

if(isset($_SESSION['user_id']))
	{
			$user_id=$_SESSION['user_id'];
			
			$fs_id = strval($_GET['fs_id']);
		
			include("dbconnection.php");
			$fs_id=mysqli_real_escape_string($con,$fs_id);

			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
			
			$sql="select count(1) from followstream where fs_id=".$fs_id." and user_id=".$user_id."";
			
			$result = mysqli_query($con,$sql) or die(mysql_error());
									
			while($row = mysqli_fetch_array($result))
						{$count=$row['count(1)'];}
			
			if($count==0)
				{
				echo "you do not have permission to delete this pinboard.";
				}
				else
				{
						
					echo "deleting followstream";
					
					$sql_delete="delete from followstream where fs_id =".$fs_id."";
			
						if (!mysqli_query($con,$sql_delete))
						{
						die('Error: ' . mysqli_error($con));
						}
						else
						{
						echo "deleted followstream successfully ! <br>";
						$_SESSION['user_alert']="Successfully deleted followstream !";
						}
								
						
				}
			
			}
			mysqli_close($con);
			
	}
else
	{
	header( 'Location:login.php' ) ;
	}	
?>

