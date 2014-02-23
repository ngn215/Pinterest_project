<html>
  <head>


    <title>Pinterest - My Followstreams</title>

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
		?>
		
		<br>
		<h4><a href="our_recommendations.php">Our Recommendations</a></h4>
	
		<?php
		$user_id=$_SESSION['user_id'];
		include("dbconnection.php");
		
		mysqli_select_db($con,$db_name);
		$sql="select fs_id,name,description,tags,created_on,no_of_pinboards,receive_flag from followstream where user_id=".$user_id."";
							
		$result = mysqli_query($con,$sql) or die(mysql_error());
								
		$count=mysqli_num_rows($result);
		
		if($count==0)
			{
			?>
			<div class="alert alert-info alert-dismissable" id="alert_success">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>Message:</strong> No followstreams have been created. Click on add to create a followstream.
			</div>
			<?php
			}
			else
			{
			?>
				
						<div class="page-header">
						<font style="color:rgb(205,31,40);"><h1>Your Followstreams</h1></font>
						</div>
											
						<table border=1>
						<tr>
						<th style="text-align:center">Name
						<th style="text-align:center">Description
						<th style="text-align:center">Tags
						<th style="text-align:center">receive_flag
						</tr>
						
			<?php
				
				while($row = mysqli_fetch_array($result))
				{
					echo "<tr>";
						echo "<td align='center'><a href='followstream_pictures.php?value=".$row['fs_id']."'>".$row['name']." ";
						echo "<td align='center'> ".$row['description']." ";
						echo "<td align='center'> ".$row['tags']." ";
						echo "<td align='center'> ".$row['receive_flag']." ";
					echo "</tr>";
				}
				echo "</table>";
			}
			
	}
	else
	{
	header( 'Location:login.php' ) ;
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

	$("#myfollowstreams").toggleClass("active", true);
	
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




