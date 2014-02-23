<?php
session_start();


if(isset($_SESSION['user_id']))
{

$user_id=$_SESSION['user_id'];

$receive_flag = strval($_POST['receive_flag']);
$tags="";
if($receive_flag=="all" || $receive_flag=="onlytags")
	{
	$tags = strval($_POST['txt_followstream_tags']);
	}

$name = strval($_POST['txt_followstream_name']);
$desc = strval($_POST['txt_followstream_desc']);


	
		//database code begins here

			include("dbconnection.php");
			
			$tags=mysqli_real_escape_string($con,$tags);
			$name=mysqli_real_escape_string($con,$name);
			$desc=mysqli_real_escape_string($con,$desc);
			
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			
				$sql="insert into followstream(user_id,name,description,tags,created_on,no_of_pinboards,receive_flag)
						values(".$user_id.",'".$name."','".$desc."','".$tags."',sysdate(),0,'".$receive_flag."')";
				
				if (!mysqli_query($con,$sql))
				{
				die('Error: ' . mysqli_error($con));
				}
				else
				{
				echo "created followstream successfully";
				//$_SESSION['user_alert']="Pinboard created successfully";
				
					$sql="select fs_id from followstream where created_on = (select max(created_on) from followstream where user_id=".$user_id.")";
					
					$result = mysqli_query($con,$sql) or die(mysql_error());
							
					$count=mysqli_num_rows($result);
					
					if($count==0)
					{
					echo "select query returned no results";
					}
					else
					{
						$fs_id="";
						while($row = mysqli_fetch_array($result))
						{$fs_id=$row['fs_id'];}
						
						if($receive_flag=="onlypinboards" || $receive_flag=="all")
							{
						
							echo "now inserting pinboards into followstreams";
							
													
								foreach ($_POST['pinboard_list_followstream'] as $pinboards)
									{
										echo "Inserting pinboard_id ".$pinboards."<br/>";
										
										$sql="insert into followstream_pinboards(fs_id,pinboard_id,added_on)
										values(".$fs_id.",'".$pinboards."',sysdate())";
										
										if (!mysqli_query($con,$sql))
										{
										die('Error: ' . mysqli_error($con));
										}
										else
										{
										echo "Inserted pinboard_id ".$pinboards."<br/>";
										}
											
									}
								
							}
								$_SESSION['user_alert']="Followstream created successfully";
						
					}
				
				
				}
			
			mysqli_close($con);
			header("Location:myfollowstreams.php");
			
			}
}
else				
{
header("Location:login.php");
}
	 
?> 