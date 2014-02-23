<?php
session_start();

if(isset($_SESSION['user_id']))
{

$user_id=$_SESSION['user_id'];
$url=strval($_POST['txt_website_url']);

echo $url;
echo "<br>";

$desc = strval($_POST['txt_website_desc']);
$tags = strval($_POST['txt_website_tags']);
$pinboard_id = strval($_POST['pinboard_list']);

$content = file_get_contents($url);
$extension = substr($url,strripos($url, ".")+1,strlen($url));

echo $extension;
echo "<br>";

echo "Pictures/temp".$user_id.".".$extension;
echo "</br>";

file_put_contents("Pictures/temp/".$user_id.".".$extension, $content);

		
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
				$picture_id="";
				
				if($count==0)
					{
					echo "<p> <font color='red' style='bold'> error. ! </font> </p>";
					}	
				else
					{
					while($row = mysqli_fetch_array($result))
						{
						
						if (!rename("Pictures/temp/".$user_id.".".$extension,"Pictures/".$row['picture_id'].".".$extension))
							echo "error while renaming/moving";
						else
							echo "successfully moved and renamed file";
						
						echo "/Pictures/temp/".$user_id.".".$extension; echo "<br>";
						echo "/Pictures/".$row['picture_id'].".".$extension; echo "<br>";
						
						$picture_id=$row['picture_id'];
						}
						
						
						echo $pinboard_id;
						echo "Picture uploaded successfully";
						echo "<br>";
						echo "Picture id :";
						echo $row['picture_id'];
						
						#---insert into picture_pinboard--------------------
						$sql="insert into picture_pinboard(picture_id,pinboard_id,pinned_on,description,tags,refers_pinboard_id) 
								values(".$picture_id.",".$pinboard_id.",sysdate(),'".$desc."','".$tags."',".$pinboard_id.")";
						
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
						$sql="update pictures set path='//localhost/pinterest/pictures/".$picture_id.".".$extension."' where picture_id=".$picture_id."";
						
						echo $sql; 
						
						if (!mysqli_query($con,$sql))
						{
						die('Error: ' . mysqli_error($con));
						}
						else
						{
						echo "successfully updated path in pictures";
						$_SESSION['user_alert']="Successfully added pin !";
						}
							
						}
					}
				}
			
				
				
		mysqli_close($con);		
		//header("Location:pinboard_pictures.php?value=".$pinboard_id);
		
}
else
{
header( 'Location:login.php' ) ;
}
	 
?> 