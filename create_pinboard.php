<?php
session_start();

if(isset($_SESSION['user_id']))
	{
$user_id=$_SESSION['user_id'];

$name = strval($_POST['txt_pinboard_name']);
$desc = strval($_POST['txt_pinboard_desc']);
$privacy = strval($_POST['privacy']);

		
		//database code begins here
		include("dbconnection.php");
			
		mysql_connect($hostname,$user,$pass,$db_name);	
	
		$name=mysqli_real_escape_string($con,$name);
		$desc=mysqli_real_escape_string($con,$desc);
		$privacy=mysqli_real_escape_string($con,$privacy);
			
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			
				$sql="insert into pinboards(name,description,user_id,created_date,no_of_pins,privacy)
				values('".$name."','".$desc."','".$user_id."',sysdate(),0,'".$privacy."')";
						
				if (!mysqli_query($con,$sql))
				{
				die('Error: ' . mysqli_error($con));
				}
				else
				{
				echo "created pinboard successfully";
				$_SESSION['user_alert']="Pinboard created successfully";
				header("Location:mypinboards.php?value=");
				}
			
			}
				
	
	}
	else
	{
	header("Location:login.php");
	}
	 
?> 