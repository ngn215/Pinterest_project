
<html>
  <head>

    <title>Pinterest - My Pinboards</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/navbar.css" rel="stylesheet">

	

  </head>

  <body>

    <div class="container">

      <?php include("navigation_bar.php"); ?>
	  <?php include("main_modal.php"); ?>


    </div> <!-- /container -->



	


<div class="container">
<div class="content">

<!--copied from v1 -->


<?php

  if(isset($_SESSION['user_alert']))
	{
	?>
	<div class="alert alert-success alert-dismissable" id="alert_success">
	  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	  <strong>Message:</strong> <?php echo $_SESSION['user_alert']; ?>
	</div>
	<?php
	unset($_SESSION['user_alert']);
	}
	?>


	
	
<?php

			$curpageURL = 'http';
			if ($_SERVER["HTTPS"] == "on") {$curpageURL.= "s";}
			$curpageURL.= "://";
			if ($_SERVER["SERVER_PORT"] != "80") {
			$curpageURL.= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			} else {
			$curpageURL.= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}
			$url= $curpageURL;
			
			$parts = parse_url($url);
			parse_str($parts['query'], $query);
			$user_id_val=$query['value'];	
									
						
			include("dbconnection.php");
			$user_id_val=mysqli_real_escape_string($con,$user_id_val);			
			//echo $user_id_val;
				
			if($user_id_val=='' && isset($_SESSION['user_id']))	
				$user_id=$_SESSION['user_id'];
			else if($user_id_val=='' && !(isset($_SESSION['user_id'])))	
				{
				echo "Error: No such page"; exit();
				}
			else if($user_id_val<>'' && isset($_SESSION['user_id']))
				{
				$user_id=$user_id_val;
				}
			else if($user_id_val<>'' && !(isset($_SESSION['user_id'])))
				{
				$user_id=$user_id_val;
				}
			
			#$_SESSION['pinboard']="";
	
	
			//-- friend button -->
			/*
			if(isset($_SESSION['user_id']))
			{
					echo $user_id;
					echo "<br>";
					echo $_SESSION['user_id'];
					if(isset($_SESSION['user_id']) && ($user_id<>$_SESSION['user_id']))
						{
						
						$sql="select user_id,friend_id,status from friends where (user_id=".$_SESSION['user_id']." and friend_id=".$user_id.")
						or (friend_id=".$_SESSION['user_id']." and user_id=".$user_id.")";
						//$sql="select * from user_follow_pinboard where user_id=? and pinboard_id=?";
						//echo $sql;
						
						$result_friends1 = mysqli_query($con,$sql) or die(mysql_error());
						$count_friends1=mysqli_num_rows($result_friends1);
						$status="";
						
						
						if($count_friends1>0)
						  {
							while($row_friends1 = mysqli_fetch_array($result_friends1))
								{
									$status=$row_friends1['status'];
									if($status=='A')
										{
										//echo "you are friends with this person";
										echo "<button type='submit'>Unfriend</button>";
										}
									else if($_SESSION['user_id']==$row_friends1['user_id'] && $user_id==$row_friends1['friend_id'] && $status=='S')
										{
										echo "<button type='submit' disabled='true'>Friend request sent</button>";
										}
								}
						  
						  }
						  else
						  {
						  echo "<button type='submit'>Send friend request</button>";
						  }
						}
			}
			*/
			//------------------------------------------------------------
					
	
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			
						
			mysqli_select_db($con,$db_name);
			$sql="SELECT first_name,last_name FROM users WHERE user_id=".$user_id." " ;
			#echo "<p>'".$sql."'</p>";
						
			$result = mysqli_query($con,$sql) or die(mysql_error());
									
			$count=mysqli_num_rows($result);
			
			if($count==0)
				{
				echo "<p> <font color='red' style='bold'> login failed ! </font> </p>";
				#header( 'Location:home.php' ) ;
				}
			else
				{
								
				?>
				
				<?php
					while($row = mysqli_fetch_array($result))
					{
					echo "<div class='page-header'><h1>".$row['first_name']."'s Pinboards</h1></div>";
					
					}
				?>
					<!-- <form name="Form_pinboards" action="" method="get"> -->
					<?php
					if(isset($_SESSION['user_id']) && ($user_id==$_SESSION['user_id']))
						$sql="SELECT name,description,pinboard_id,no_of_pins,privacy FROM pinboards WHERE user_id=".$user_id."";
					else
						$sql="SELECT name,description,pinboard_id,no_of_pins,privacy FROM pinboards WHERE user_id=".$user_id." and privacy<>'private'" ;
					
					
					$result = mysqli_query($con,$sql) or die(mysql_error());
					$count=mysqli_num_rows($result);
					
					if($count==0)
					{
					?>
					<br>
					<div class="alert alert-info alert-dismissable" id="alert_success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>Info : </strong> Pinboards are like boards where you can pin your pictures too.... <br>
					Start by creating a pinboard
					</div>
					<?php
					}
					else
					{
						?>
						

						
						<!-- Pinboards section -->
						<div id="pinboards_section">
						<p>
						<div class="page-header">
						<font style="color:rgb(205,31,40);"><h1>Created Pinboards</h1></font>
						</div>
											
						<table border=1>
						<tr>
						<th style="text-align:center">Name
						<th style="text-align:center">Description
						<th style="text-align:center">Privacy
						</tr>
						
						<?php
						while($row = mysqli_fetch_array($result)) 
						{
						echo "<tr>";
						echo "<td align='left'>";
						echo "<input type='button' border=0 style='color:black; background-color: white; border-color: transparent; cursor:pointer; font-family:Segoe UI; font-size:16px'
						value='".$row['name']."' name='".$row['pinboard_id']."' onclick='setPinboardval(this.name)' class='transition'></input>";
						echo "<td align='left'> ".$row['description']." ";
						echo "<td align='center'> ".$row['privacy']." ";
						
						/*
						echo "<td>";
						if($row['no_of_pins']=="0" && isset($_SESSION['user_id']))
							$disabled="";
						else
							$disabled="disabled";
							
						echo "<button type='button' name='".$row['pinboard_id']."' onclick='delPinboard(this.name)' ".$disabled.">
						<span class='glyphicon glyphicon-trash'></span></button>";
						*/
						
						echo "</tr>";
										
						} 
						?>
						<!-- </form> -->
											
						</table>
						</p>					
						</div>
						
																
						
						</font>
						
					  <?php 
					//echo "</table>";
				
					}?>
					
											<!-- Following section -->
						<div id="following_section">
						<p>
						<div class="page-header">
						<font style="color:rgb(205,31,40);"><h1>Followed Pinboards</h1></font>
						</div>
						<table border=1>
						
						<tr>
						<th style="text-align:center">Name
						<th style="text-align:center">Description
						<th style="text-align:center">Created by
						</tr>
						
						<?php
						$sql="SELECT p.name,p.pinboard_id,p.description,u.first_name creator,u.user_id FROM pinboards p,user_follow_pinboard uf,users u 
						WHERE uf.user_id=".$user_id." and p.pinboard_id=uf.pinboard_id and u.user_id=p.user_id" ;
						$result = mysqli_query($con,$sql) or die(mysql_error());
						$count=mysqli_num_rows($result);
						while($row = mysqli_fetch_array($result)) 
						{
						echo "<tr><td>" ;
						echo "<input type='button' border=0 style='color:black; background-color: white; border-color: transparent; cursor:pointer; font-family:Segoe UI; font-size:16px'
						value='".$row['name']."' name='".$row['pinboard_id']."' onclick='setPinboardval(this.name)'></input>";
						echo "<td align='left'>".$row['description']."";
						echo "<td align='center'><a href='mypinboards.php?value=".$row['user_id']."'> ".$row['creator']."</a></tr>";
						} 
						?>
						
						</table>
						</p>
						</div>
					
				<?php	
				}
			
			mysqli_close($con);
			#session_destroy(); 
			
			//database code ends here
			}
	
	
?>

</div>
</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>


<script src="js/jquery-1.10.2.js"></script>
<script>

$(document).ready(function() {

	$("#MyPinboards").toggleClass("active", true);
	
    $(".content").css("display", "none");
 
    $(".content").fadeIn(1000);
 
    $("a.transition").click(function(event){
        event.preventDefault();
        linkLocation = this.href;
        $(".content").fadeOut(1000, redirectPage);     
    });
	
	$("a.navbar-brand").click(function(event){
		event.preventDefault();
		linkLocation = this.href;
		$(".content").fadeOut(1000, redirectPage);     
    });
	
	$("input.transition").click(function(event){
		$(".content").fadeOut(1000);
		
		
    });
         
	 
    function redirectPage() {
        window.location = linkLocation;
    }
	
	
});

$("#logout_button").click(function(){
  $.get('logout.php');
  $(".content").css("display", "none");
  alert( "Logged out sucessfully !" );
  window.location.href = "login.php";
}); 


</script>

<script language="javascript">
function setPinboardval($str)
	{
	window.location.href = "http://localhost/pinterest/pinboard_pictures.php?value="+$str;
	}

	
function delPinboard($pinboard_id)
	{
	//alert("delete_pinboard.php?pinboard_id="+$pinboard_id);
	
	//ajax code
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{	
		window.location.href = "http://localhost/pinterest/mypinboards.php?value=";
		//alert("working");
		}
	  }

	xmlhttp.open("GET","delete_pinboard.php?pinboard_id="+$pinboard_id,true);  
	xmlhttp.send();
	}

</script>

</body>
</html>




