
<?php

session_start();

if(isset($_SESSION['user_id']))
	{
			$user_id=$_SESSION['user_id'];
			
			$pinboard_id = strval($_GET['pinboard_id']);
		
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
			
			$sql="select count(1) from pinboards where pinboard_id=".$pinboard_id." and user_id=".$user_id."";
			
			$result = mysqli_query($con,$sql) or die(mysql_error());
									
			while($row = mysqli_fetch_array($result))
						{$count=$row['count(1)'];}
			
			if($count==0)
				{
				echo "you do not have permission to delete this pinboard.";
				}
				else
				{
						
							echo "deleting files";
							$sql="select distinct path,p.picture_id from pictures p,picture_pinboard p_pb where p_pb.pinboard_id=".$pinboard_id." and
							p.picture_id=p_pb.picture_id and p.user_id=".$user_id."";
							
							$result1 = mysqli_query($con,$sql) or die(mysql_error());
									
							while($row1 = mysqli_fetch_array($result1))
								{
								$count1=$row1['path'];
								
								echo "hello";
								
								$myFile = "pictures/".strrchr($count1,'/');
								unlink($myFile);
								
								echo "deleted file ".$myFile;
								
								
								$sql_delete="delete from pictures where picture_id =".$row1['picture_id']."";
						
									if (!mysqli_query($con,$sql_delete))
									{
									die('Error: ' . mysqli_error($con));
									}
									else
									{
									echo "deleted pictures inside pinboard successfully ! <br>";
									}
								}
								
										echo "now we need to delete pinboard <br>";
															
										
										$sql="delete from pinboards where pinboard_id=".$pinboard_id."";
									
										if (!mysqli_query($con,$sql))
										{
										die('Error: ' . mysqli_error($con));
										}
										else
										{		
										echo "successfully deleted pinboard";
										$_SESSION['user_alert']="Successfully deleted pinboard !";
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

