
<?php

session_start();

if(isset($_SESSION['user_id']))
	{
			$user_id=$_SESSION['user_id'];
			
			$tags = strval($_POST['txt_repin_tags']);
			$desc = strval($_POST['txt_repin_desc']);
			$from_pinboard_id = strval($_POST['repin_txt_pinboard_id']);
			$picture_id = strval($_POST['repin_txt_picture_id']);
			$to_pinboard_id= strval($_POST['pinboard_list']);
			
			include("dbconnection.php");

			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
						
		    $sql="insert into picture_pinboard(picture_id,pinboard_id,pinned_on,description,tags,refers_pinboard_id)
					values(".$picture_id.",".$to_pinboard_id.",sysdate(),'".$desc."','".$tags."',".$from_pinboard_id.")";
			
			echo $sql;
					
				if (!mysqli_query($con,$sql))
				{
				die('Error: ' . mysqli_error($con));
				}
				else
				{
				echo "successfully pinned to pinboard";
				}

			}
		header("Location:pinboard_pictures.php?value=".$to_pinboard_id) ;
	}
else
	{
	header( 'Location:login.php' ) ;
	}	
?>

