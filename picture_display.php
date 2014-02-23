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

<body>

<?php

			

			//$user_id=$_SESSION['user_id'];
			
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
			$value=$query['value'];	
									
			$pinboard_id = substr($value,0,strpos($value, "_"));
			$picture_id = substr($value,strpos($value, "_")+1,strlen($value));		
	
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
						
		    $sql="select 
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
					(select count(1) from picture_pinboard a, picture_pinboard b 
					where a.picture_id=p.picture_id
					and a.picture_id=b.picture_id
					and a.pinboard_id=p_pb.pinboard_id
					and b.refers_pinboard_id=a.pinboard_id
					and a.pinboard_id<>b.pinboard_id) repins,
					case when pb.user_id=u1.user_id and p_pb.pinboard_id=p_pb.refers_pinboard_id then 'upload'
						 else 'repin' end as repin_flag
					from
					pictures p,
					picture_pinboard p_pb,
					pinboards pb,
					users u,
					pinboards pb2,
					users u1
					where 
					p.picture_id=".$picture_id."
					and
					p_pb.pinboard_id=".$pinboard_id."
					and
					p.picture_id=p_pb.picture_id
					and
					pb.pinboard_id = p_pb.pinboard_id
					and
					p.user_id=u.user_id
					and p_pb.refers_pinboard_id=pb2.pinboard_id
					and pb2.user_id=u1.user_id
					order by p_pb.pinned_on desc";
						
			$result = mysqli_query($con,$sql) or die(mysql_error());
									
			$count=mysqli_num_rows($result);
			
			if($count==0)
				{
				echo "<p> <font color='red' style='bold'> Not Found ! </font> </p>";
				#header( 'Location:home.php' ) ;
				}
			else
				{

					while($row = mysqli_fetch_array($result))
					{
				?>
				
				<table border=0 align="center" cellpadding=5>
				<tr>
				<td align="center">
				<font id="form_headings">
				<h1><?php echo "".$row['description']."";?></h1>
				</font>
				</tr>
					
				<tr>	
				<td>
				<img src="<?php echo "".$row['path']."" ?>" width="900" height="600">
				</tr>
				
				<tr>
				<td><font style="font-size:15px;"><b>Tags : </b><?php echo $row['tags']; ?></font>
				</tr>
				
				<tr>
				<td><font style="font-size:15px;"><b>Pinboard : </b><a href="pinboard_pictures.php?value=<?php echo $row['pinboard_id']; ?>"><?php echo $row['pinboard_name']; ?></a></font>
				</tr>
				
				<tr>
				<?php 
					if($row['repin_flag']=="upload")
						{
						?>
						<td>
						<?php
							if($row['url']=="USER-PC")
								{
								?>
								<font style="font-size:15px;"><b>Uploaded by : </b>
								<a href="mypinboards.php?value=<?php echo "".$row['uploader_user_id'].""; ?>"> <?php echo "".$row['uploaded_by'].""; ?> </a>
								</font>
								<?php
								}
								else
								{
								?>
								<font style="font-size:15px;"><b>Added by : </b>
								<a href="mypinboards.php?value=<?php echo "".$row['uploader_user_id'].""; ?>"> <?php echo "".$row['uploaded_by'].""; ?> </a>
								</font>
								<?php
								}
						}
					else
						{
						?>
						<td>
						<font style="font-size:15px;"><b>Repinned via : </b>
						<a href="mypinboards.php?value=<?php echo "".$row['repinned_from_user_id'].""; ?>"> <?php echo "".$row['repinned_from'].""; ?> </a>
						</font>
						<?php
						}
					?>
				</tr>
				
				<tr>
				<?php 
					if($row['url']<>"USER-PC")
						{
						?>
						<td>
						<font style="font-size:15px;"><b>Website : </b>
						<a href="<?php echo "".$row['url'].""; ?>"> <?php echo "".$row['url'].""; ?> </a>
						</font>
						<?php
						}
					?>
				</tr>
				
				<tr>
				
				<td>
				<table border=0>
				<font id="form_sub_headings">
				<tr>
				<td>
				
				<?php
				
				if (isset($_SESSION['user_id']))
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
				
				} ?>
				
				
				<td>
				<b>Likes :</b>
				<td><div id="<?php echo "likes_values_".$row['picture_id'].""; ?>"><?php echo "".$row['no_of_likes'].""; ?></div>
				<td>&nbsp;
				
				<?php
				if(isset($_SESSION['user_id']))
				{
				?>
				<td>
				<button type="submit" style="cursor:pointer;" value="<?php echo "repin_".$row['pinboard_id']."_".$row['picture_id'].""; ?>" 
					onclick="pinboard_list_populate('repin',this.value);repin_click(this.value)" data-toggle="modal" data-target="#repin">
				<img src="//localhost/pinterest/images/pin_it.jpg" height=30 width=30 style="cursor:pointer;">
				</img></button>
				<?php
				}
				?>
				

				
				<td align="center">
				<b>Repins : </b>
				<td><div id="<?php echo "".$row['pinboard_id'].""; ?>"><?php echo "".$row['repins'].""; ?></div>
				</font>
				
				<td>&nbsp;
				<td>&nbsp;
				<td>&nbsp;
				<td>&nbsp;
				<td>&nbsp;
				
				<?php
				
				
				if(isset($_SESSION['user_id']))
					{
					$sql="SELECT count(1) from pinboards where pinboard_id=".$row['pinboard_id']." 	and user_id=".$user_id."";
								
					$result_owner_check = mysqli_query($con,$sql) or die(mysql_error());
					while($row_owner_check = mysqli_fetch_array($result_owner_check))
						{$count_owner_check= $row_owner_check['count(1)']; }
					
						if($count_owner_check>0)
							{
							?>
							<td>
							<button type="submit" value="<?php echo "delete_".$row['pinboard_id']."_".$row['picture_id'].""; ?>" onclick="delete_picture_click(this.value)">
							<span class='glyphicon glyphicon-trash'></span>
							</button>Delete Pin
							<?php
							}
							else
							{
							?>
							
							<?php
							}
					}

				?>
				
				<td>&nbsp;
				<td>&nbsp;
				<td>&nbsp;
				<td>&nbsp;
				<td>&nbsp;
				
							
				</table>
				</tr>
						
								
				</tr>	
				
				<!--
				<tr>
					<table border=1>
					<tr>
					<td align="center">
					<b>Tags :</b>
					<?php //if ($row['tags']=="") 
							//echo "No tags"; 
							//else 
							//echo "".$row['tags'].""; ?>
					</tr>	
					</table>
				</tr>
				-->
				
				<tr>
				
				
				<td>
				<table border=1 width=900 style="border-width:1px;border-style:dotted;">
				<tr>
				<td align="left" colspan=2><font id="form_sub_headings"><b>Comments : </b></font>
				</tr>
				
				<?php
					$sql="SELECT 
								comment_id,
								picture_id,
								pinboard_id,
								c.user_id,
								first_name,
								comment,
								commented_on
						  FROM
								comments c, users u
						  WHERE
								picture_id = ".$picture_id." and pinboard_id = ".$pinboard_id." and c.user_id=u.user_id
								order by commented_on";
								
					$result1 = mysqli_query($con,$sql) or die(mysql_error());
					$count1=mysqli_num_rows($result);

					if($count1==0)
					{
					echo "<p>error occured</p>";
					}
					else
					{
					while($row1 = mysqli_fetch_array($result1))
						{
					?>
				
				<tr>
				
				<td>
				<table border=0 width="740">
				<tr>
				<td width="680"><?php echo "".$row1['comment'].""; ?>
				<td align="right" valign="center">
				
				<?php
				if (isset($_SESSION['user_id']))
				{
								
					$sql="select
						(select count(1) from comments where comment_id=".$row1['comment_id']."  and user_id=".$user_id.")
						+
						(select count(1) from pinboards where pinboard_id=".$pinboard_id." and user_id=".$user_id.") total_count";
						
						$result_comment_owner = mysqli_query($con,$sql) or die(mysql_error());
												
						while($row_comment_owner = mysqli_fetch_array($result_comment_owner))
							{$count_comment_owner= $row_comment_owner['total_count'];}
							
							if($count_comment_owner>0)
							{
							
				?>			
				<form name="Form_delete_comment" action="delete_comments.php" method="post" id="Form_delete_comment">
				<button type="submit" name="btn_delete_comment" value="<?php echo "".$row1['comment_id'].""; ?>">
				<span class='glyphicon glyphicon-trash'></span></button>
				<input type="hidden" name="comment_pinboard_picture_id" value="<?php echo "".$pinboard_id."_".$picture_id.""; ?>">
				</form>
				<?php 
							}
				}
				?>
				
				</tr>
				</table>
				
				<td><i><font size="2"><?php echo "".$row1['first_name']." on ".$row1['commented_on'].""; ?></i></font>
				
				</tr>
				

				
					<?php }
					?>
				
				
				<?php if (isset($_SESSION['user_id']))
				{
				?>
					<!-- check if user is friend -->
					<?php
					$sql="select
							(
							SELECT count(1) 
								from friends
								where status='A'
								and 
								(
									user_id=".$user_id." and friend_id=(select user_id from pinboards where pinboard_id=".$pinboard_id.")
									or
									user_id=(select user_id from pinboards where pinboard_id=".$pinboard_id.") and friend_id=".$user_id."
								)
							)
							+(select count(1) from pinboards where pinboard_id=".$pinboard_id." and privacy='all')
							+(select count(1) from pinboards where pinboard_id=".$pinboard_id." and user_id=".$user_id.") total_count";
						
						$result_friends = mysqli_query($con,$sql) or die(mysql_error());
												
						while($row_friends = mysqli_fetch_array($result_friends))
							{$count_friends= $row_friends['total_count'];}
													
							if($count_friends>0)
							{
					?>
				
							<tr>
							<form name="Form_add_comment" action="add_comments.php" method="post" id="Form_add_comment">
							<td colspan=2><input type="text" name="txt_comment" id="txt_comment" value="Add Comment" size=145></input>
							<!--<td align="center"><input type="submit" value="SUBMIT" name="sub_comment" id="sub_comment"></input> -->
							<input type="hidden" name="comment_pinboard_picture_id" value="<?php echo "".$pinboard_id."_".$picture_id.""; ?>">
							</form>
							</tr>
				<?php
							}
				}
				?>
				
				</table>
								
				</tr>
				</table>
				
				</tr>
				
				</table>
				<div id="comment_navigate"></div>
				
				<?php
					}
					}
				
				}
			
			mysqli_close($con);
			#session_destroy(); 
			
			//database code ends here
			}

?>

</div>
</div>

<html>
<script src="jquery-1.10.2.js"></script>
<script>
$("#txt_comment").click(function(){
 if( $(this).val() == 'Add Comment') 
  {
  $(this).val('');
	}
 else
 {
 
 }
 
});
 
 
$("#txt_comment").blur(function(){
 if( $(this).val() == '') 
  {
  $(this).val('Add Comment');
	}
 else
 {
 
 }
  
});


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
  window.location.href = "http://localhost/pinterest/login.php";
}); 

</script>

<script language="javascript">

function setSessionVariable($picture_pinboard_id,$type)
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
		document.getElementById("likes_values_"+$picture_id).innerHTML=xmlhttp.responseText;
		changeIconLikeUnlike($picture_id,$button_image_id);
	}
  }  

  if(document.getElementById($button_image_id).src == "http://localhost/pinterest/images/like.png")  
	xmlhttp.open("GET","like.php?picture_id="+$picture_id,true);
else
	xmlhttp.open("GET","unlike.php?picture_id="+$picture_id,true);
	
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


function delete_picture_click($delete_pinboard_picture_id)
	{
	//alert("delete_picture.php?picture_id="+$delete_pinboard_picture_id);
	
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
		window.location.href = "http://localhost/pinterest/home.php";
		//alert("working");
		}
	  }
	xmlhttp.open("GET","delete_pin.php?delete_pinboard_picture_id="+$delete_pinboard_picture_id,true);  
	xmlhttp.send();
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

</body>
</html>




