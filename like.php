<?php

session_start();

if(isset($_SESSION['user_id']) AND isset($_SESSION['picture_id']))
	{	
		$user_id=$_SESSION['user_id'];
		$picture_id=$_SESSION['picture_id'];
		
		//database code begins here

		include("dbconnection.php");
		
		
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
		
			#---insert into likes--------------------
			$sql="insert into likes(picture_id,user_id,liked_on) values(".$picture_id.",".$user_id.",sysdate())";
						
			#if (!mysqli_query($con,$sql))
			#{
			#die('Error: ' . mysqli_error($con));
			#}
						
			mysqli_query($con,$sql) or die(mysql_error());
			#----------------------------------------
			
				
			$sql="select no_of_likes from pictures where picture_id=".$picture_id."";
			
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
					echo "".$row['no_of_likes']."";
					}
					
				//database code ends here
				}
				
			}
			mysqli_close($con);
				
	}
	else
	{
	//header( 'Location:home.php' ) ;
	echo "<p>oops something went wrong</p>";
	}
	

?>





