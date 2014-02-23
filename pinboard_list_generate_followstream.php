<html>
<head>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/navbar.css" rel="stylesheet">
</head>
<body>

<?php

session_start();
//echo "page begiining";

if(isset($_SESSION['user_id']))
	{	
		$user_id=$_SESSION['user_id'];
		
				
		//echo $pinboard_id;		
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
			
			$sql="SELECT u_pb.pinboard_id,name FROM user_follow_pinboard u_pb, pinboards pb where u_pb.user_id=".$user_id." and u_pb.pinboard_id=pb.pinboard_id";
				
			$result = mysqli_query($con,$sql) or die(mysql_error());
							
			$count=mysqli_num_rows($result);
			
			if($count==0)
				{
				?>
				<div class="alert alert-warning" id="alert_success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong>Warning : </strong> You are not following any pinboards currently.
				</div>
				<select name="pinboard_list_followstream[]" id="pinboard_list" multiple="multiple" disabled="true" hidden="true">
				</select>
				<?php
				}
			else
				{
				?>
				
				<select name="pinboard_list_followstream[]" id="pinboard_list" multiple="multiple">
				<?php
				while($row = mysqli_fetch_array($result))
					{
					?>							
						<option value="<?php echo "".$row['pinboard_id'].""; ?>">
							<?php echo "".$row['name'].""; ?>
						</option>
										
					<?php 
					} ?>

				</select>
				<br>You can select multiple pinboards by holding Ctrl key
				<?php	
				//database code ends here
				}
				
			}
			mysqli_close($con);
				
	}
	else
	{
	#header( 'Location:home.php' ) ;
	echo "<p>failed</p>";
	}
	

?>
</body>
</html>




