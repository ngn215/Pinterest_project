<?php

session_start();

if(isset($_SESSION['user_id']) AND isset($_SESSION['picture_id']))
	{	
		$user_id=$_SESSION['user_id'];
		$picture_id=$_SESSION['picture_id'];
		
		//database code begins here

			$db_name="pinterest"; 												//database name
			$con = mysqli_connect("localhost:3306","root","root",$db_name);		//create connection
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
		
			#---delete from likes--------------------
			$sql="delete from likes where picture_id=".$picture_id." AND user_id=".$user_id."";
						
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
	header( 'Location:home.php' ) ;
	#echo "<p>failed</p>";
	}
	

?>





