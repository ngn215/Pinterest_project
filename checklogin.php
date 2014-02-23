<?php 

		
		session_start();
				
		$email = strval($_POST['txt_email']);
		$password = strval($_POST['txt_password']);
		

			//database code begins here
		include("dbconnection.php");
		
		$email = mysqli_real_escape_string($con, $email );	
		$password = mysqli_real_escape_string($con, $password );	
		
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
			$sql="SELECT user_id FROM users WHERE email_id='".$email."' AND password='".$password."'" ;
			
			$result = mysqli_query($con,$sql) or die(mysql_error());

			$count=mysqli_num_rows($result);

			if($count==0)
				{
				echo "<p> <font color='red' style='bold'> login failed ! </font> </p>";
				$_SESSION['user_alert']="Invalid username or password";
				header( 'Location:login.php' ) ;
				}
			else
				{
				while($row = mysqli_fetch_array($result))
				  {
					echo "<p> <font color='red' style='bold'> login successfull ! </font> </p>";
					$_SESSION['user_id']="".$row['user_id']."";
					echo "session_id = ".$_SESSION['user_id'];
					}
					
					header( 'Location:home.php' ) ;
				}
			
			mysqli_close($con);
			//database code ends here
			}

?>


