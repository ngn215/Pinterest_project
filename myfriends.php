<html>
  <head>


    <title>Pinterest - My Friends</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
	    <link href="css/signin.css" rel="stylesheet">

	<!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet"> -->

  </head>

  <body>

    <div class="container">

      <?php include("navigation_bar.php"); ?>
	  <?php include("main_modal.php"); ?>

      

    </div> <!-- /container -->



	
	
	
<div class="container">
<div class="content">




<?php
			if(isset($_SESSION['user_id']))	
			{
			
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
				
			
			
			include("dbconnection.php");
			$user_id=$_SESSION['user_id'];
	
					if (!$con)
					{
					  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
					  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
					}
					else
					{
					mysqli_select_db($con,$db_name);
					
					//check for friend notifications
					$sql="select f.user_id,u.first_name,u.last_name from friends f, users u where u.user_id=f.user_id and status='S' and f.friend_id=".$user_id."";
				
					$result_notification = mysqli_query($con,$sql) or die(mysql_error());
					$count_notification = mysqli_num_rows($result_notification);
					
					if($count_notification>0)
					{
					?>
					<div class="page-header">
					<h1>Friend Notifications</h1>
					</div>	
					
					<div class="alert alert-success alert-dismissable" id="alert_success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>Message:</strong> You have friend notifications
					</div>

					<table border=0>
					<?php
					while($row_notification = mysqli_fetch_array($result_notification))
						{
						?>
						<tr>
						<td><a href="mypinboards.php?value=<?php echo $row_notification['user_id']; ?>"> 
						<?php echo "".$row_notification['first_name']." ".$row_notification['last_name'].""; ?></a>
						<td>&nbsp;
						<td>
						<form name="form_accept_friend" action="accept_friend.php" method="post">
						<button type="submit" name="accept_friend_button" value="<?php echo "".$row_notification['user_id'].""; ?>" >
						</form>
						<span class="glyphicon glyphicon-ok"></span>
						</button>
						<td>&nbsp;
						<td>
						<form name="form_reject_friend" action="reject_friend.php" method="post">
						<button type="submit" name="reject_friend_button" value="<?php echo "".$row_notification['user_id'].""; ?>" >
						</form>
						<span class="glyphicon glyphicon-remove"></span>
						</button>
						</tr>
						</tr>
						<?php
						}
						?>
						</table>
					<?php	
					}
					
					$sql="select f.friend_id,u.first_name,u.last_name from friends f, users u where u.user_id=f.friend_id and status='A' and f.user_id=".$user_id."
					union
					select f.user_id,u.first_name,u.last_name from friends f, users u where u.user_id=f.user_id and status='A' and f.friend_id=".$user_id."";
					
					//echo $sql;
								
					$result = mysqli_query($con,$sql) or die(mysql_error());
											
					$count=mysqli_num_rows($result);
					?>
					
					<br>
					<div class="page-header">
					<h1>Friends</h1>
					</div>		
					
					
					<?php
						if($count==0)
						{
						?>
						<div class="alert alert-success alert-dismissable" id="alert_success">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Message:</strong> No friends have been added.
						</div>
						<?php
						}
						else
						{
						echo "<table border=0>";
						
							
							while($row = mysqli_fetch_array($result))
							{	
								echo "<tr>";
								echo "<td>";
								echo "<a href='mypinboards.php?value=".$row['friend_id']."'> ".$row['first_name']." ".$row['last_name']." </a>";
								echo "<td><a href='remove_friend.php?value=".$row['friend_id']."'><button type='submit'>
								<span class='glyphicon glyphicon-trash'></span></button></a><br>";			
								echo "</tr>";
							}
						echo "</table";
						}

					

					
					
					}
					
			?>	
					<br><br>
					<div class="page-header">
					<h1>Add Friends</h1>
					</div>	
					
					
					Friend Name : <input type="text" id="txt_friend_search">
					<button type="submit" name="friend_search_button" onclick="friends_search()">Search</button>
					<br>
					<div id="friend_list"></div>
									
			<?php		
			mysqli_close($con);
			}
else
{
header("Location:login.php");
}
	
?>			

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

<script src="js/jquery-1.10.2.js"></script>
<script>	  

$(document).ready(function() {
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

function friends_search()
{
//alert("hello");
$search_text=document.getElementById("txt_friend_search").value
	
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
		document.getElementById("friend_list").innerHTML=xmlhttp.responseText;
		//alert("working");
		}
	  }

	xmlhttp.open("GET","friends_search.php?value="+$search_text,true);  
	xmlhttp.send();
}

</script>

</body>
</html>