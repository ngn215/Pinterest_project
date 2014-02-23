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
		$pinboard_id = strval($_GET['value']);
				
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
			
			if($pinboard_id=='')
				$sql="SELECT pinboard_id,name FROM pinboards where user_id=".$user_id."";
			else
				$sql="SELECT pinboard_id,name FROM pinboards where user_id=".$user_id." and pinboard_id<>".$pinboard_id."";
				
			$result = mysqli_query($con,$sql) or die(mysql_error());
							
			$count=mysqli_num_rows($result);
			
			if($count==0)
				{
				?>
				<div class="alert alert-warning" id="alert_success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong>Warning : </strong> Pictures cannot be uploaded without a pinboard. A Picture can be uploaded to a pinboard only once.
				</div>
				<select name="pinboard_list" id="pinboard_list">
				<option value="select pinboard">No Pinboards</option>
				<select>
				<?php
				}
			else
				{
				?>
				
				<select name="pinboard_list" id="pinboard_list">
				<option value="select pinboard">Select Pinboard</option>
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




