<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

    <title>Pinterest - Pinboard</title>

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
			$pinboard_id=$query['value'];	
			
			if(isset($_SESSION['user_id']))
				$user_id=$_SESSION['user_id'];
		
		//database code begins here

			include("dbconnection.php");
			
			mysql_connect($hostname,$user,$pass,$db_name);	
			$pinboard_id = mysql_real_escape_string($pinboard_id);	
			//echo "hello".$pinboard_id;
					
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
			echo "<br>";
			
			//get owner of pinboard. this will be used to display or hide certain buttons
			$sql="select user_id,privacy from pinboards where pinboard_id=".$pinboard_id.""; //echo $sql;
			$result_get_owner = mysqli_query($con,$sql) or die(mysql_error());
			$count_get_owner=mysqli_num_rows($result_get_owner);
			
			
			if($count_get_owner==0)
			{
			echo "error";
			}
			else
			{
			while($row_get_owner = mysqli_fetch_array($result_get_owner))
				{
				$pinboard_owner_id=$row_get_owner['user_id']; 
				$privacy=$row_get_owner['privacy']; 
				}
			}
				
			?>
			<!-- follow button -->
					<?php
					if(isset($_SESSION['user_id']) && ($pinboard_owner_id<>$_SESSION['user_id']) && $privacy<>'private')
						{
						
						$sql="select * from user_follow_pinboard where user_id=".$user_id." and pinboard_id=".$pinboard_id."";
						//$sql="select * from user_follow_pinboard where user_id=? and pinboard_id=?";
						
						$result_follows = mysqli_query($con,$sql) or die(mysql_error());
						$count_follows=mysqli_num_rows($result_follows);
							
						
						if($count_follows==0)
						  {
						  ?>
						  <p><button type="submit" onclick="follow_click(<?php echo $pinboard_id; ?>)">
						  <span class="glyphicon glyphicon-ok"></span>
						  Follow</button></p>
						  <?php
						  }
						  else
						  {
						  ?>
						  <p><button type="submit" onclick="unfollow_click(<?php echo $pinboard_id; ?>)">
						   Unfollow</button></p>
						  <?php
						  }
						}
					?>
					
				<!-- delete pinboard button -->
					<?php
					if(isset($_SESSION['user_id']) && ($pinboard_owner_id==$_SESSION['user_id']))
						{
						
						  ?>
						  <p><button type="submit" onclick="delete_pinboard(<?php echo $pinboard_id; ?>)">
						  <span class="glyphicon glyphicon-trash"></span>
						  </button></p>
						 <?php
						}
					?>
			
			<?php
			
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
				pinboards pb,
				users u,
				users u1,
				pinboards pb2
			WHERE
				pb.pinboard_id = ".$pinboard_id."
					AND pb.pinboard_id = p_pb.pinboard_id
					AND p.picture_id = p_pb.picture_id
					AND p.user_id = u.user_id
					and p_pb.refers_pinboard_id = pb2.pinboard_id
					and pb2.user_id = u1.user_id";
			//echo $sql;
			
			if(isset($_SESSION['user_id']) && $pinboard_owner_id==$_SESSION['user_id'])
				$sql=$sql." order by pinned_on desc";
			else
				$sql=$sql." and pb.privacy<>'private' order by pinned_on desc";
				
			
			$result = mysqli_query($con,$sql) or die(mysql_error());

			$count=mysqli_num_rows($result);

			if($count==0)
				{
				//echo "<p> <font color='red' style='bold'> No pictures available ! </font> </p>";
				?>
				<div class="alert alert-info alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong>Message:</strong> No Pictures have been uploaded to this pinboard.
				</div>
				<?php
				}
			else
				{
							
					$sql_pinboard_name="SELECT 
					name,description,first_name,last_name,users.user_id from pinboards,users 
					where pinboards.user_id=users.user_id and pinboard_id=".$pinboard_id."";
					$result_pinboard_name = mysqli_query($con,$sql_pinboard_name) or die(mysql_error());
									
							
					while($row_pinboard_name = mysqli_fetch_array($result_pinboard_name))
					  {
					   ?>
					  <font style="font-family:Segoe UI;">

						<p>
						<div class="page-header">
						<h1><font style="color:rgb(205,31,40);">Pinboard : <?php echo $row_pinboard_name['name']; ?> </font></h1>
						</div>
						<h4>Description : <?php echo $row_pinboard_name['description']; ?></h4>
						<h4>Created by : <a href="mypinboards.php?value=<?php echo "".$row_pinboard_name['user_id'].""; ?>" >
						<?php echo "".$row_pinboard_name['first_name']." ".$row_pinboard_name['last_name'].""; ?></a></h4>
						</p>
						
					  <?php
					  }
				?>
					
					
					
				
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
					<td align="center">
					<img src="<?php echo "".$row['path']."" ?>" width="200" height="150" name="<?php echo "".$pinboard_id."_".$row['picture_id'].""; ?>" 
					style="cursor:pointer;" onclick="picture_click(this.name)" style="">
					</tr>
					
					<tr>
					<td><font style="font-size:13px;"><b>Tags : </b><?php echo $row['tags']; ?></font>
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
					<div id="<?php echo "".$row['picture_id'].""; ?>"> <?php echo "".$row['no_of_likes'].""; ?></div>					
					</font>
					
					
					<?php
					if(isset($_SESSION['user_id']))
						{
					$sql="select count(1) from likes where picture_id=".$row['picture_id']." and user_id=".$user_id."";
					$result1 = mysqli_query($con,$sql) or die(mysql_error());
					
					while($row1 = mysqli_fetch_array($result1))
						{$count1=$row1['count(1)'];}
						
					echo "<button type='submit' style='cursor:pointer;' value='".$row['picture_id']."' onclick='likeunlike_click(this.value)'>";					
					if($count1==0) 
					{
					echo "<img id='button_image_".$row['picture_id']."' src='http://localhost/pinterest/images/like.png' height=20 width=20>";
					}
					else
					{	
					echo "<img id='button_image_".$row['picture_id']."' src='http://localhost/pinterest/images/unlike.png' height=20 width=20>";
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
function likeunlike_click($picture_id)
{
//alert($picture_id);
//set session variables
setSessionVariable($picture_id);
$button_image_id="button_image_"+$picture_id;

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
	document.getElementById($picture_id).innerHTML=xmlhttp.responseText;
	changeIconLikeUnlike($button_image_id);
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

<!--alert($picture_id);-->

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

function changeIconLikeUnlike($picture_id)
{
if(document.getElementById($picture_id).src == "http://localhost/pinterest/images/unlike.png")
	document.getElementById($picture_id).src="http://localhost/pinterest/images/like.png";
else
	document.getElementById($picture_id).src="http://localhost/pinterest/images/unlike.png";
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

function follow_click($pinboard_id)
{
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
	location.reload();
	}
  }
xmlhttp.open("GET","follow_pinboard.php?pinboard_id="+$pinboard_id,true);
xmlhttp.send();
}

function unfollow_click($pinboard_id)
{
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
	location.reload();
	}
  }
xmlhttp.open("GET","unfollow_pinboard.php?pinboard_id="+$pinboard_id,true);
xmlhttp.send();
}

function delete_pinboard($pinboard_id)
{
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
	window.location.href = "mypinboards.php?value=";
	}
  }
xmlhttp.open("GET","delete_pinboard.php?pinboard_id="+$pinboard_id,true);
xmlhttp.send();
}

</script>

</body>
</html>



