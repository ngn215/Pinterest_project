<?php
session_start();

$user_id=$_SESSION['user_id'];

$desc = strval($_POST['txt_desc']);
$tags = strval($_POST['txt_tags']);
$pinboard_id = strval($_POST['pinboard_list']);



$allowedExts = array("gif", "jpeg", "JPG", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
echo $extension;
echo $_FILES["file"]["name"];
echo "<br>";


if (in_array($extension, $allowedExts))
  {



		$url="USER-PC";
		
		//database code begins here

			include("dbconnection.php");
			$desc=mysqli_real_escape_string($con,$desc);
			$tags=mysqli_real_escape_string($con,$tags);
			$pinboard_id=mysqli_real_escape_string($con,$pinboard_id);
			
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
		
			#---insert into pictures--------------------
			
			$sql="insert into pictures(user_id,path,url,no_of_repins,no_of_likes) values(".$user_id.",'','".$url."',0,0)";
						
			if (!mysqli_query($con,$sql))
			{
			die('Error: ' . mysqli_error($con));
			}
			else
			{
				//mysqli_query($con,$sql) or die(mysql_error());
				#----------------------------------------
					
					
				$sql="select picture_id from pictures where user_id=".$user_id." order by picture_id desc LIMIT 1";
				
				$result = mysqli_query($con,$sql) or die(mysql_error());

				$count=mysqli_num_rows($result);	
					
				if($count==0)
					{
					echo "<p> <font color='red' style='bold'> error. ! </font> </p>";
					}	
				else
					{
					while($row = mysqli_fetch_array($result))
						{
						
						$file_format = substr($_FILES["file"]["name"],strpos($_FILES["file"]["name"], ".")+1,strlen($_FILES["file"]["name"]));	  
						$_FILES["file"]["name"]=$row['picture_id'].".".$file_format;
						move_uploaded_file($_FILES["file"]["tmp_name"],	"Pictures/" . $_FILES["file"]["name"]);
						//copy($file,"Pictures/".$_FILES["file"]["name"]);
						
						echo $_FILES["file"]["tmp_name"];echo "<br>";
						echo "Pictures/" . $_FILES["file"]["name"]; echo "<br>";			
						
						echo $pinboard_id;
						echo "Picture uploaded successfully";
						echo "<br>";
						
						
						#---insert into picture_pinboard--------------------
						$sql="insert into picture_pinboard(picture_id,pinboard_id,pinned_on,description,tags,refers_pinboard_id) 
								values(".$row['picture_id'].",".$pinboard_id.",sysdate(),'".$desc."','".$tags."',".$pinboard_id.")";
						
						echo $sql;
									
						if (!mysqli_query($con,$sql))
						{
						die('Error: ' . mysqli_error($con));
						}
						else
						{
						echo "successfully pinnned to pinboard";
						}
						
						#---update path in pictures--------------------
						$sql="update pictures set path='//localhost/pinterest/pictures/".$_FILES["file"]["name"]."' where picture_id=".$row['picture_id']."";					
						
						echo $sql; 
						
						if (!mysqli_query($con,$sql))
						{
						die('Error: ' . mysqli_error($con));
						}
						else
						{
						echo "successfully updated path in pictures";
						$message="Picture uploaded successfully !!";
						$_SESSION['user_alert']=$message;
						}
							
						}
					}
				}
			
				
				
			}
			
					mysqli_close($con);		
					
			
			header("Location:pinboard_pictures.php?value=".$pinboard_id);
			
		}

		
		
		else
{
echo "<p>Invalid file</p>";
}		
			

?> 