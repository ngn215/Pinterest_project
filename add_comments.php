
<?php

session_start();

if(isset($_SESSION['user_id']))
	{
			$user_id=$_SESSION['user_id'];
			
			$value = strval($_POST['comment_pinboard_picture_id']);
			$comment = strval($_POST['txt_comment']);
			
			$pinboard_id = substr($value,0,strpos($value, "_"));
			$picture_id = substr($value,strpos($value, "_")+1,strlen($value));	
				
			//echo "Location:picture_display.php?value=".$value."#Form_delete_comment";	
	
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
						
		    $sql="INSERT INTO comments
					(picture_id,
					pinboard_id,
					user_id,
					comment,
					commented_on)
					VALUES
					(".$picture_id.",
					".$pinboard_id.",
					".$user_id.",
					'".$comment."',
					sysdate()
					)";
						
			//mysqli_query($con,$sql) or die(mysql_error());
									
			if (!mysqli_query($con,$sql))
			{
			die('Error: ' . mysqli_error($con));
			}
			
			mysqli_close($con);
			#session_destroy(); 
			
			//database code ends here
			}
			
			header("Location:picture_display.php?value=".$value."#comment_navigate") ;
	
	}
	
	else
	{
	echo "session expired";
	header( 'Location:home.php' ) ;
	}
	

?>







