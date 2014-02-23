

<?php

session_start();

if(isset($_SESSION['user_id']))
	{
			$user_id=$_SESSION['user_id'];
			
			$comment_id = strval($_POST['btn_delete_comment']);
			$value = strval($_POST['comment_pinboard_picture_id']);						
			
			include("dbconnection.php");
			
			$comment_id=mysqli_real_escape_string($con,$comment_id);
			$value=mysqli_real_escape_string($con,$value);
			
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
						
		    $sql="delete from comments where comment_id=".$comment_id."";
						
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






