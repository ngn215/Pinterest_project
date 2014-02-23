<html>
  <head>

    <title>Pinterest - Followstream</title>

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
			
			$user_id=$_SESSION['user_id'];
			
			
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
			$fs_id=$query['value'];	
		
		//database code begins here

			include("dbconnection.php");
			$fs_id=mysqli_real_escape_string($con,$fs_id);
		
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
			?>
			<!-- delete button -->
			
			<br>
			<p><button type="submit" onclick="delete_followstream(<?php echo $fs_id; ?>)">
						  <span class="glyphicon glyphicon-trash"></span>
						  </button></p>
						  
			<!-- -->			  
			<?php		
			//---------------------------------------------------------------------------------------		
			//Followstream header
			$sql="SELECT 
					name,description,tags,first_name,last_name,u.user_id from followstream fs,users u
					where fs.user_id=u.user_id and fs_id=".$fs_id."";
					
					$result_pinboard_name = mysqli_query($con,$sql) or die(mysql_error());	
							
					while($row_pinboard_name = mysqli_fetch_array($result_pinboard_name))
					  {
					   ?>
					  <font style="font-family:Segoe UI;">

						<p>
						<div class="page-header">
						<h1><font style="color:rgb(205,31,40);">Followstream : <?php echo $row_pinboard_name['name']; ?> </font></h1>
						</div>
						<h4>Description : <?php echo $row_pinboard_name['description']; ?></h4>
						<h4>Tags : <?php echo $row_pinboard_name['tags']; ?></h4>
						<h4>Created by : <?php echo "".$row_pinboard_name['first_name']." ".$row_pinboard_name['last_name'].""; ?></h4>
						</p>
						
					  <?php
					  }		
					
			//---------------------------------------------------------------------------------------		
					
		
			//---------------------------------------------------------------------------------------		
			//Followstream pinboards list
			$sql="SELECT 
						name
					from
						followstream_pinboards fs_pb,
						pinboards pb
					where
						fs_pb.pinboard_id = pb.pinboard_id
							and fs_pb.fs_id = ".$fs_id."";
					
					$result_pinboard_list = mysqli_query($con,$sql) or die(mysql_error());	
					?>
					
					<h4>Pinboards :
					<select>
					<?php
					while($row_pinboard_list = mysqli_fetch_array($result_pinboard_list))
					  {
					   ?>
					  <option><?php echo "".$row_pinboard_list['name'].""; ?></option>
						
					  <?php
					  }
						?>
					</select>
					</h4>
					<br>
					<?php
			//---------------------------------------------------------------------------------------	
		
			$sql="
			SELECT * from
			(
			SELECT 
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
				followstream fs,
				followstream_pinboards fs_pb,
				pictures p,
				picture_pinboard p_pb,
				pinboards pb,
				users u,
				users u1,
				pinboards pb2
			WHERE
					fs.fs_id=".$fs_id."
					and fs.fs_id=fs_pb.fs_id
					and	pb.pinboard_id = fs_pb.pinboard_id
					AND pb.pinboard_id = p_pb.pinboard_id
					AND p.picture_id = p_pb.picture_id
					AND p.user_id = u.user_id
					and p_pb.refers_pinboard_id = pb2.pinboard_id
					and pb2.user_id = u1.user_id
			UNION
			SELECT 
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
				followstream fs,
				pictures p,
				picture_pinboard p_pb,
				pinboards pb,
				users u,
				users u1,
				pinboards pb2
			WHERE
					fs.fs_id=".$fs_id."
					and p_pb.tags like concat(concat('%',fs.tags),'%')
					and fs.tags<>''
					AND pb.privacy<>'private'
					AND pb.pinboard_id = p_pb.pinboard_id
					AND p.picture_id = p_pb.picture_id
					AND p.user_id = u.user_id
					and p_pb.refers_pinboard_id = pb2.pinboard_id
					and pb2.user_id = u1.user_id)X
			order by pinned_on desc
			";			
			//echo $sql;
			
			$result = mysqli_query($con,$sql) or die(mysql_error());

			$count=mysqli_num_rows($result);

			if($count==0)
				{
				?>
					<div class="alert alert-success alert-dismissable" id="alert_success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>Message:</strong> No data available at this moment !
					<?php
				}
			else
				{
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
				}
			
			
			//display secret pinboards
			
			
			mysqli_close($con);
			//database code ends here
}
else
{
header( 'Location:login.php' ) ;	
}		

?>

</div>

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

function delete_followstream($fs_id)
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
	window.location.href = "myfollowstreams.php";
	}
  }
xmlhttp.open("GET","delete_followstream.php?fs_id="+$fs_id,true);
xmlhttp.send();
}

</script>

</body>
</html>



