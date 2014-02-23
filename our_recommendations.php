<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

    <title>Pinterest - Our Recommendations</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/navbar.css" rel="stylesheet">

	

  </head>

  <body>

    <div class="container">

      <?php include("navigation_bar.php"); ?>
	  <?php include("main_modal.php"); ?>

      <!-- Main component for a primary marketing message or call to action -->
		<!--
		<div class="jumbotron">
        <h1>Navbar example</h1>
        <p>This example is a quick exercise to illustrate how the default, static navbar and fixed to top navbar work. It includes the responsive CSS and HTML, so it also adapts to your viewport and device.</p>
        <p>
          <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">View navbar docs &raquo;</a>
        </p>
      </div>
	  -->

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	
</script>

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

if(isset($_SESSION['user_id']))
	{
	$user_id=$_SESSION['user_id'];
		
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
						
			$sql="SELECT 
				p.picture_id,
				pb.name pinboard_name,
				p_pb.pinboard_id,
				p_pb.description,
				p.path,
				p.url,
				p_pb.tags,
				p.no_of_likes,
				p_pb.pinned_on,
				u.user_id uploader_user_id,
				u.first_name uploaded_by,
				u1.user_id repinned_from_user_id,
				u1.first_name repinned_from,
				(select 
						count(1)
					from
						picture_pinboard a,
						picture_pinboard b
					where
						a.picture_id = p.picture_id
							and a.picture_id = b.picture_id
							and a.pinboard_id = p_pb.pinboard_id
							and b.refers_pinboard_id = a.pinboard_id
							and a.pinboard_id <> b.pinboard_id) repins,
				case
					when pb.user_id = u1.user_id and p_pb.pinboard_id=p_pb.refers_pinboard_id then 'upload'
					else 'repin'
				end as repin_flag
			FROM
				pictures p,
				picture_pinboard p_pb,
				(
				select pinboard_id from pinboards
				where user_id in (
				select distinct user_id from pictures where
				picture_id in
				(
				select picture_id from likes
				where user_id=".$user_id."
				) and user_id<>".$user_id.")
				UNION
				select distinct refers_pinboard_id from picture_pinboard
				where pinboard_id<>refers_pinboard_id and pinboard_id in
				(select pinboard_id from pinboards where user_id=".$user_id.")
				UNION
				select pinboard_id from pinboards where user_id in (
				select distinct user_id from likes where picture_id in
				(select picture_id from likes
				where user_id=".$user_id."))
				and user_id<>".$user_id."
				UNION
				select pinboard_id from pinboards where user_id in
				(
				select distinct user_id from likes where picture_id in
				(select picture_id from pictures where user_id=".$user_id.")
				and user_id<>".$user_id."
				)
				)pblist,
				pinboards pb,
				users u,
				users u1,
				pinboards pb2
			WHERE
					pblist.pinboard_id not in (select pinboard_id from pinboards where user_id=".$user_id.")
					AND pblist.pinboard_id not in (select pinboard_id from user_follow_pinboard where user_id=".$user_id.")
					AND pb.privacy<>'private'
					AND pblist.pinboard_id=pb.pinboard_id
					AND pb.pinboard_id = p_pb.pinboard_id
					AND p.picture_id = p_pb.picture_id
					AND p.user_id = u.user_id
					and p_pb.refers_pinboard_id = pb2.pinboard_id
					and pb2.user_id = u1.user_id
			order by pinned_on desc";
							
			//echo $sql;
			$result = mysqli_query($con,$sql) or die(mysql_error());

			$count=mysqli_num_rows($result);

			if($count==0)
				{
				//echo "<p> <font color='red' style='bold'> No pictures available ! </font> </p>";
				?>
				<div class="alert alert-info alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong>Message:</strong> We dont have any recommendations for you at this moment.
				</div>
				<?php
				}
			else
				{
				?>			
				<div class="page-header">
				<font style="color:rgb(205,31,40);"><h1>Our recommendations</h1></font>
				</div>	
				<?php
				while($row = mysqli_fetch_array($result))
				  {
				   	# echo "<p>".$row['picture_id']." ".$row['path']." ".$row['description']."</p>";
					?>
					
					<div style="float:left;">
					
					<table style="border-width:2px; border-style:dotted solid;" cellpadding=8>
					<tr>
					<td align="center"><?php echo "".$row['description']."";?>
					</tr>
					
					<tr>
					<td align='center'>
					<img src="<?php echo "".$row['path']."" ?>" width="200" height="150" name="<?php echo "".$row['pinboard_id']."_".$row['picture_id'].""; ?>" 
					style="cursor:pointer;" onclick="picture_click(this.name)" style="">
					</tr>
					
					<tr>
					<td><font style="font-size:13px;"><b>Tags : </b><?php echo $row['tags']; ?></font>
					</tr>
					
					<tr>
					<td><font style="font-size:13px;"><b>Pinboard : </b><a href="pinboard_pictures.php?value=<?php echo $row['pinboard_id']; ?>"><?php echo $row['pinboard_name']; ?></a></font>
					</tr>
					
					<tr>
					<td align="center">
					
					<table border=0 align="left">
					<tr>
					<td align="center" id="like">
					<?php
					if(!isset($_SESSION['user_id']))
						echo "Likes:";
					?>
					<font style="color:rgb(205,31,40); font-size:15px"">
					<div id="<?php echo "likes_values_".$row['pinboard_id']."_".$row['picture_id'].""; ?>" class="<?php echo "likes_values_".$row['picture_id'].""; ?>">
					<?php echo "".$row['no_of_likes'].""; ?></div>				
					</font>
					
					
					<?php
					if(isset($_SESSION['user_id']))
						{
					$sql="select count(1) from likes where picture_id=".$row['picture_id']." and user_id=".$user_id."";
					$result1 = mysqli_query($con,$sql) or die(mysql_error());
					
					while($row1 = mysqli_fetch_array($result1))
						{$count1=$row1['count(1)'];}
						
					echo "<button type='submit' style='cursor:pointer;' value='".$row['pinboard_id']."_".$row['picture_id']."' 
					onclick='likeunlike_click(this.value)'>";					
					if($count1==0) 
					{
					echo "<img id='button_image_".$row['pinboard_id']."_".$row['picture_id']."' class='button_image_".$row['picture_id']."' 
					src='http://localhost/pinterest/images/like.png' height=20 width=20>";
					}
					else
					{	
					echo "<img id='button_image_".$row['pinboard_id']."_".$row['picture_id']."' class='button_image_".$row['picture_id']."' 
					src='http://localhost/pinterest/images/unlike.png' height=20 width=20>";
					}
						}
					
					?>
					</button>
					
					<td>&nbsp;
										
					<td align="center">
					<?php
					if(!isset($_SESSION['user_id']))
						echo "Repins:";
					?>
					<font style="color:rgb(205,31,40); font-size:15px"">
					<div id="<?php echo "".$row['repins'].""; ?>"><?php echo "".$row['repins'].""; ?></div>
					</font>
					<?php
					if(isset($_SESSION['user_id']))
						{ 
						?>
					<button type="submit" style="cursor:pointer;" value="<?php echo "repin_".$row['pinboard_id']."_".$row['picture_id'].""; ?>" 
					onclick="pinboard_list_populate('repin',this.value);repin_click(this.value)" data-toggle="modal" data-target="#repin">
					<img src="http://localhost/pinterest/images/pin_it.jpg" height=20 width=20"> 
					</button>
						<?php
						}
						?>
					
					<td>&nbsp;
					
					<td align="right">
					<?php 
					if($row['repin_flag']=="upload")
						{
						?>
						<td align="right">
						<?php
							if($row['url']=="USER-PC")
								{
								?>
								<font style="font-size:11px;">Uploaded by :<br>
								<a href="mypinboards.php?value=<?php echo "".$row['uploader_user_id'].""; ?>"> <?php echo "".$row['uploaded_by'].""; ?> </a>
								</font>
								<?php
								}
								else
								{
								?>
								<font style="font-size:11px;">Added by :<br>
								<a href="mypinboards.php?value=<?php echo "".$row['uploader_user_id'].""; ?>"> <?php echo "".$row['uploaded_by'].""; ?> </a>
								</font>
								<?php
								}
								?>
						<?php
						}
					else
						{
						?>
						<td align="right">
						<font style="font-size:11px;">Repinned via:<br>
						<a href="mypinboards.php?value=<?php echo "".$row['repinned_from_user_id'].""; ?>"> <?php echo "".$row['repinned_from'].""; ?> </a>
						</font>
						<?php
						}
					?>
					
					
															
					</tr>		
					
					</table>
					
					</tr>
					</table>
					
					<br>
					</div>					
					
					<div style="float:left;">
					<table border=0>
					<tr>
					<td>&nbsp;
					</tr>
					</table>
					</div>
						
					<?php 
					}
				}
			
			
			//display secret pinboards
			
			
			mysqli_close($con);
			//database code ends here
			}
	}
		

?>

</div>

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
function likeunlike_click($pinboard_picture_id)
{
//alert($pinboard_picture_id);
$pinboard_id = $pinboard_picture_id.substring(0, $pinboard_picture_id.indexOf('_')); 
$picture_id = $pinboard_picture_id.substring($pinboard_picture_id.indexOf('_')+1, $pinboard_picture_id.length); 

//set session variables
setSessionVariable($picture_id,"picture");
$button_image_id="button_image_"+$pinboard_picture_id;

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
	for (var i = 0; i < document.getElementsByClassName("likes_values_"+$picture_id).length; i++) 
		{
		document.getElementsByClassName("likes_values_"+$picture_id)[i].innerHTML=xmlhttp.responseText;
		}
	//alert(document.getElementsByClassName($picture_id).length);
	changeIconLikeUnlike($picture_id,$button_image_id);
	}
  }

if(document.getElementById($button_image_id).src == "http://localhost/pinterest/images/like.png")  
	xmlhttp.open("GET","like.php?picture_id="+$picture_id,true);
else
	xmlhttp.open("GET","unlike.php?picture_id="+$picture_id,true);
	
xmlhttp.send();
}

function setSessionVariable($picture_id)
{

//alert($picture_id);
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
	}
  }
xmlhttp.open("GET","session_variable.php?type=picture&value="+$picture_id,true);
xmlhttp.send();
}

function changeIconLikeUnlike($picture_id,$button_image_id)
{
$picture_id="button_image_"+$picture_id;
if(document.getElementById($button_image_id).src == "http://localhost/pinterest/images/unlike.png")
	{
	//document.getElementById($button_image_id).src="http://localhost/pinterest/images/like.png";
		for (var i = 0; i < document.getElementsByClassName($picture_id).length; i++) 
		{
		document.getElementsByClassName($picture_id)[i].src="http://localhost/pinterest/images/like.png";
		}
	}
else
	{
	//document.getElementById($button_image_id).src="http://localhost/pinterest/images/unlike.png";
		for (var i = 0; i < document.getElementsByClassName($picture_id).length; i++) 
		{
		document.getElementsByClassName($picture_id)[i].src="http://localhost/pinterest/images/unlike.png";
		}
	}
}

function picture_click($pinboard_picture_id)
{
//alert($pinboard_picture_id);
$pinboard_id = $pinboard_picture_id.substring(0, $pinboard_picture_id.indexOf('_')); 
$picture_id = $pinboard_picture_id.substring($pinboard_picture_id.indexOf('_')+1, $pinboard_picture_id.length); 

window.location.href = "picture_display.php?value="+$pinboard_picture_id;
}

function repin_click($repin_pinboard_picture_id)
{
//alert($repin_pinboard_picture_id);
$repin_pinboard_picture_id=$repin_pinboard_picture_id.replace("repin_","");
//alert($repin_pinboard_picture_id);
$pinboard_id=$repin_pinboard_picture_id.substring(0,$repin_pinboard_picture_id.indexOf('_'));
//alert($pinboard_id);
$picture_id=$repin_pinboard_picture_id.substring($repin_pinboard_picture_id.indexOf('_')+1,$repin_pinboard_picture_id.length);
//alert($picture_id);

document.getElementById("repin_txt_picture_id").value=$picture_id;
document.getElementById("repin_txt_pinboard_id").value=$pinboard_id;
}

</script>

</body>
</html>



