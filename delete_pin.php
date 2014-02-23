
<?php

session_start();

if(isset($_SESSION['user_id']))
	{
			$user_id=$_SESSION['user_id'];
			
			$delete_pinboard_picture_id=strval($_GET['delete_pinboard_picture_id']);
			$delete_pinboard_picture_id=str_replace("delete_","",$delete_pinboard_picture_id);
			
			$pinboard_id = substr($delete_pinboard_picture_id,0,strpos($delete_pinboard_picture_id, "_"));
			$picture_id = substr($delete_pinboard_picture_id,strpos($delete_pinboard_picture_id, "_")+1,strlen($delete_pinboard_picture_id));
			
			
			include("dbconnection.php");
			$pinboard_id=mysqli_real_escape_string($con,$pinboard_id);
			$picture_id=mysqli_real_escape_string($con,$picture_id);

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
				echo "you do not have permission to delete this picture.";
				}
				else
				{
					$sql="select count(1) from picture_pinboard where picture_id=".$picture_id." and pinboard_id=refers_pinboard_id
					and pinboard_id=".$pinboard_id."";
					
					echo $sql;
					
					$result1 = mysqli_query($con,$sql) or die(mysql_error());
					while($row1 = mysqli_fetch_array($result1))
						{$count1=$row1['count(1)'];}
					
					if ($count1==0)
						{
							echo "this is request to delete pin";
							$sql="delete from picture_pinboard where picture_id=".$picture_id." and pinboard_id=".$pinboard_id."";
							
							if (!mysqli_query($con,$sql))
								{
								die('Error: ' . mysqli_error($con));
								}
								else
								{
								$sql="delete from picture_pinboard where picture_id=".$picture_id." and refers_pinboard_id=".$pinboard_id."";
								
									if (!mysqli_query($con,$sql))
									{
									die('Error: ' . mysqli_error($con));
									}
									else
									{
									echo "successfully delete pin";
									$_SESSION['user_alert']="Successfully deleted pin !";
									}
								}
						}
						else
						{
							echo "this is request to delete picture";
							$sql="select path from pictures where picture_id=".$picture_id."";
						
							$result2 = mysqli_query($con,$sql) or die(mysql_error());
							
							while($row2 = mysqli_fetch_array($result2))
								{$count2=$row2['path'];}
								
							$myFile = "pictures/".strrchr($count2,'/');
							//$fh = fopen($myFile, 'w') or die("can't open file");
							//fclose($fh);
							unlink($myFile);
							
							echo "deleted file ".$myFile;
										
							$sql="delete from pictures where picture_id=".$picture_id."";
							
							if (!mysqli_query($con,$sql))
								{
								die('Error: ' . mysqli_error($con));
								}
								else
								{		
								echo "successfully deleted picture";
								$_SESSION['user_alert']="Successfully deleted pin and picture !";
								}
						}
						
				
				}
			
			echo $sql;
				
			
			}
		//header( 'Location:mypinboards.php' ) ;
	}
else
	{
	header( 'Location:login.php' ) ;
	}	
?>

