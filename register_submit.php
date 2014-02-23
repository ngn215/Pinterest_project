<html>

<body>

<?php

session_start();
//echo "page begiining";

		$first_name = strval($_POST['txt_first_name']);
		$last_name = strval($_POST['txt_last_name']);
		$email_id = strval($_POST['txt_email']);
		$password = strval($_POST['txt_password']);
				
		include("dbconnection.php");
		
			
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
			
			$sql="SELECT * FROM users where email_id='".$email_id."'";
										
			$result = mysqli_query($con,$sql) or die(mysql_error());
							
			$count=mysqli_num_rows($result);
						
						
			if($count>0)
				{
				echo "<p> <font color='red' style='bold'> This email has already has an account associated with it ! </font> </p>";
				$_SESSION['user_alert']="This email has already has an account associated with it.";
				}
			else
				{
				$sql="insert into users(first_name,last_name,email_id,password,date_of_joining) 
					  values('".$first_name."','".$last_name."','".$email_id."','".$password."',sysdate())";
					  
				echo $sql;
				
					if (!mysqli_query($con,$sql))
						{
						die('Error: ' . mysqli_error($con));
						}
						else
						{
						echo "successfully registered";
						$_SESSION['user_alert']="Registration Success ! Please login with your credentials now.";
						}
				}
				
			}
			
			if($count>0)
			{
			header( 'Location:register.php' ) ;
			mysqli_close($con);
			}
			else
			{
			header( 'Location:login.php' ) ;
			mysqli_close($con);
			}
				

?>
</body>
</html>




