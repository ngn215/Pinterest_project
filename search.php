<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

    <title>Pinterest - Search</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

	<!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet"> -->

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
	
	
	
<div class="container">
<div class="content">




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
			$search_text=$query['txt_search'];	
			$search_by=$query['search_by'];	
			//echo $search_by;	
			//echo $search_text;	
				
			include("dbconnection.php");
	
			if (!$con)
			{
			  echo "<b><font color='red'>Database connection Failed !!!</font></b><br>";
			  //die('Could not connect: ' . mysqli_error($con));				//if connection fails then show error
			}
			else
			{
			mysqli_select_db($con,$db_name);
			
			if($search_by=="tags")
				{
		    $sql="select 
					p.picture_id,
					pb.name pinboard_name,
					p_pb.pinboard_id,
					p_pb.description,
					p.path,
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
				from
					pictures p,
					pinboards pb,
					picture_pinboard p_pb,
					users u,
					pinboards pb2,
					users u1
				where
					(p_pb.tags like '%!".$search_text."%' ESCAPE '!')
						and pb.pinboard_id = p_pb.pinboard_id
						and p.picture_id = p_pb.picture_id
						and pb.user_id = u.user_id
						and p_pb.refers_pinboard_id = pb2.pinboard_id
						and pb2.user_id = u1.user_id
						and pb.privacy<>'private'";
					}
			else if($search_by=="name")
				{
				$sql="select pinboard_id,name,description,u.user_id,privacy,u.first_name creator
						from
						pinboards pb, users u
						where 
						name like '%!".$search_text."%' ESCAPE '!'
						and pb.user_id=u.user_id
						and pb.privacy<>'private'";
				}
			
			//echo $sql;
						
					
			$result = mysqli_query($con,$sql) or die(mysql_error());
									
			$count=mysqli_num_rows($result);
			?>
			
			<div class="page-header">
			<h1>Search</h1>
			</div>		
			
			<!-- search options -->
			<br>
			<div style="float:left">
			Search by : 
			<input type="radio" <?php if($search_by=="tags") echo "checked=true"; ?> 
			onclick="window.location='search.php?txt_search=<?php echo $search_text; ?>&search_by=tags'"> Tags </input> &nbsp; 
			<input type="radio" <?php if($search_by=="name") {echo "checked=true";} ?> 
			onclick="window.location='search.php?txt_search=<?php echo $search_text; ?>&search_by=name'"> Name </input>
			</div>
			<br>
			<br>
			
		
			<!-- ----------------------->
			<?php
			if($search_by=="name")
			{
					$result = mysqli_query($con,$sql) or die(mysql_error());
					$count=mysqli_num_rows($result);
					
					if($count==0)
					{
					?>
					<div class="alert alert-success alert-dismissable" id="alert_success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>Message:</strong> No Matches Found for <?php echo "'".$search_text."'"; ?><br>
					Try to search by name or input different search word.
					</div>
					<?php
					}
					else
					{
					?>
					Search results for <?php echo "'".$search_text."'"; ?> <br><br>
					
					<table border=1>
						<tr>
						<th style="text-align:center">Name
						<th style="text-align:center">Description
						<th style="text-align:center">Creator
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
						echo "<td align='center'><a href='mypinboards.php?value=".$row['user_id']."'> ".$row['creator']."</a>";
						echo "<td align='center'> ".$row['privacy']." </tr>";
						}
					}
			}
			
			else
			{		
			
				if($count==0)
				{
				?>
				<div class="alert alert-success alert-dismissable" id="alert_success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong>Message:</strong> No Matches Found for <?php echo "'".$search_text."'"; ?><br>
				Try to search by name or input different search word.
				</div>
				<?php
				}
				else
				{
				?>
					Search results for <?php echo "'".$search_text."'"; ?> <br><br>
				<?php
					while($row = mysqli_fetch_array($result))
					{
						?>
										
						<div style="float:left;">				
						<table style="border-width:2px; border-style:dotted solid;" cellpadding=8>
						<tr>
						<td align="center"><?php echo "".$row['description']."";?>
						</tr>
							
						<tr>
						<td>
						<img src="<?php echo "".$row['path']."" ?>" width="200" height="150" name="<?php echo "".$row['pinboard_id']."_".$row['picture_id'].""; ?>" 
						onclick="picture_click(this.name)" style="cursor:pointer;">
						</tr>
						
				
						<tr>
						<td><font style="font-size:13px;"><b>Tags : </b><?php echo $row['tags']; ?></font>
						</tr>
					
						<tr>
						<td><font style="font-size:13px;"><b>Pinboard : </b><a href="pinboard_pictures.php?value=<?php echo $row['pinboard_id']; ?>"><?php echo $row['pinboard_name']; ?></a></font>
						</tr>
						
						<tr>
						<td>
						<table border=0>
						<td align="center" id="like">
						<?php
						if(!isset($_SESSION['user_id']))
						echo "Likes:";
						?>
						<font style="color:rgb(205,31,40); font-size:11px"">
						<div id="<?php echo "likes_values_".$row['pinboard_id']."_".$row['picture_id'].""; ?>" class="<?php echo "likes_values_".$row['picture_id'].""; ?>">
						<?php echo "".$row['no_of_likes'].""; ?></div>
						
						<?php
						if(isset($_SESSION['user_id']))
						{
						$user_id=$_SESSION['user_id'];
						
						$sql="select count(1) from likes where picture_id=".$row['picture_id']." and user_id=".$user_id."";
						$result1 = mysqli_query($con,$sql) or die(mysql_error());
						
						while($row1 = mysqli_fetch_array($result1))
							{$count1=$row1['count(1)'];}
						
						?>
						
						<?php
						echo "<button type='submit' style='cursor:pointer;' value='".$row['pinboard_id']."_".$row['picture_id']."' onclick='likeunlike_click(this.value)'>";					
						if($count1==0) 
						{
						echo "<img id='button_image_".$row['pinboard_id']."_".$row['picture_id']."' class='button_image_".$row['picture_id']."' 
						src='http://localhost/pinterest/images/like.png' height=25 width=25>";
						}
						else
						{	
						echo "<img id='button_image_".$row['pinboard_id']."_".$row['picture_id']."' class='button_image_".$row['picture_id']."' 
						src='http://localhost/pinterest/images/unlike.png' height=25 width=25>";
						}
						}
						?>
						</button>
						</font>
						
						<td>&nbsp;
						
						<td align="center">
						<?php
						if(!isset($_SESSION['user_id']))
						echo "Repins:";
						?>
						<font style="color:rgb(205,31,40); font-size:11px"">
						<div id="<?php echo "".$row['repins'].""; ?>"><?php echo "".$row['repins'].""; ?></div>
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
						</font>
						
						<td>&nbsp;
						
						<?php 
						if($row['repin_flag']=="upload")
						{
						?>
						<td align="right">
						<font style="font-size:11px;">Uploaded by:<br>
						<a href="mypinboards.php?value=<?php echo "".$row['uploader_user_id'].""; ?>"> <?php echo "".$row['uploaded_by'].""; ?> </a>
						</font>
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
			}
	mysqli_close($con);
?>			

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
function setPinboardval($str)
	{
	window.location.href = "http://localhost/pinterest/pinboard_pictures.php?value="+$str;
	}

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

function picture_click($pinboard_picture_id)
{
//alert($pinboard_picture_id);
$pinboard_id = $pinboard_picture_id.substring(0, $pinboard_picture_id.indexOf('_')); 
$picture_id = $pinboard_picture_id.substring($pinboard_picture_id.indexOf('_')+1, $pinboard_picture_id.length); 

window.location.href = "picture_display.php?value="+$pinboard_picture_id;
}


function setSessionVariable($picture_id,$type)
{
//alert($picture_id);
//alert("session_variable.php?type="+$type+"&value="+$picture_id);
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
	//alert("working");
	}
  }
xmlhttp.open("GET","session_variable.php?type="+$type+"&value="+$picture_id,true);
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




function repin_click($repin_pinboard_picture_id)
{
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
