<html>    
	<head>
	<link href="css/navbar.css" rel="stylesheet">
	<link href="css/bootstrap-glyphicons.css" rel="stylesheet">
	</head>
	
	<body>

<?php session_start(); ?>

<!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="home.php" id="project_title" class="transition">Pinterest</a>
        </div>
        <div class="navbar-collapse collapse">
		
		<?php
		if(isset($_SESSION['user_id']))
			{
				?>
		  <ul class="nav navbar-nav">
            <li id="newsfeed"><a href="home.php" class="transition">Newsfeed</a></li>
            <li id="MyPinboards"><a href="mypinboards.php?value=" class="transition">My Pinboards</a></li>
            <li id="myfollowstreams"><a href="myfollowstreams.php" class="transition">My Followstreams</a></li>
			<li id="myfriends"><a href="myfriends.php" class="transition">My Friends</a></li>
			<li id="myaccount1"><a href="myaccount.php" class="transition">My Account</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp; Add<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="" name="upload_pin" data-toggle="modal" data-target="#modal_upload_pic" onclick="pinboard_list_populate(this.name,'')">
				<span class="glyphicon glyphicon-upload"></span>&nbsp; Upload a pin
				</a></li>
                <li><a href="" name="add_website" data-toggle="modal" data-target="#modal_add_website" onclick="pinboard_list_populate(this.name,'')">
				<span class="glyphicon glyphicon-plus"></span>&nbsp; Add from website
				</a></li>
                <li class="divider"></li>
                <li class="dropdown-header"></li>
                <!-- <li><a href="#">Separated link</a></li> -->
                <li><a href="" data-toggle="modal" data-target="#create_pinboard">
				<span class="glyphicon glyphicon-list-alt"></span>&nbsp; Create a Pinboard
				</a></li>
				<li><a href="" name="create_followstream" data-toggle="modal" data-target="#create_followstream" onclick="pinboard_list_populate(this.name,'')">
				<span class="glyphicon glyphicon-list-alt"></span>&nbsp; Create a Followstream
				</a></li>
              </ul>
            </li>
          </ul>
				<?php
				}
				?>


	            
		  <ul class="nav navbar-nav navbar-right">
			<li>
			<form class="navbar-form navbar-left" onsubmit="return false;">
			<div class="form-group">
			<input type="text" class="form-control" placeholder="Search" name="txt_search" id="txt_search">
			</div>
			<button type="submit" onclick="search()">
			<span class="glyphicon glyphicon-search" ></span></button>
			</form>
			</li>
		  
		  		<?php
		if(isset($_SESSION['user_id']))
			{
				?>
            <li><a href="" id="logout_button">Logout</a></li>
            <?php
			}
			
		else
			{
			?>
			<li><a href="login.php" id="login_button">Login</a></li>
			<?php
			}
			?>
          </ul>
		 
		  
        </div><!--/.nav-collapse -->
      </div>
	  
	  </body>
	  

	  
<script language="javascript">	  

function pinboard_list_populate($str,$repin_pinboard_picture_id)
{
//alert($str+" "+$repin_pinboard_picture_id);
$repin_pinboard_picture_id=$repin_pinboard_picture_id.replace("repin_","");
$pinboard_id=$repin_pinboard_picture_id.substring(0,$repin_pinboard_picture_id.indexOf('_'));
$picture_id=$repin_pinboard_picture_id.substring($repin_pinboard_picture_id.indexOf('_')+1,$repin_pinboard_picture_id.length);
//alert($pinboard_id);
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
		//alert("working");
		if($str=="upload_pin")
			document.getElementById("upload_pic_pinboard_list").innerHTML=xmlhttp.responseText;
		else if($str=="add_website")
			document.getElementById("add_website_pinboard_list").innerHTML=xmlhttp.responseText;
		else if($str=="repin")
			document.getElementById("repin_pinboard_list").innerHTML=xmlhttp.responseText;
		else if($str=="create_followstream")
			document.getElementById("create_followstream_pinboard_list").innerHTML=xmlhttp.responseText;
	}
  }
  
if($str=="create_followstream")
	xmlhttp.open("GET","pinboard_list_generate_followstream.php",true);
else
	xmlhttp.open("GET","pinboard_list_generate.php?value="+$pinboard_id,true);
	
xmlhttp.send();
}

function search()
{
if(document.getElementById("txt_search").value=='' || document.getElementById("txt_search").value.replace(/ /g, "").length==0)
	return false;
//alert("hello");
//alert(document.getElementById("txt_search").value);
$search_text=document.getElementById("txt_search").value
//alert($search_text);
window.location.href = "search.php?txt_search="+$search_text+"&search_by=tags";
}

function add_pinboard()
{
//alert("hello");
var option = document.createElement("option");
var elt = document.getElementById("pinboard_list");
option.text = elt.options[elt.selectedIndex].text;
option.value = elt.value;
var select = document.getElementById("pinboards_added");
select.appendChild(option);
}

function receive_flag_change()
{
//alert("hello");

if(document.getElementById("pinboard_list").length==0 && (document.getElementById("receive_flag").value=="all" 
|| document.getElementById("receive_flag").value=="onlypinboards"))
	{
	alert("You cannot choose this option since you are not following any pinboards currently");
	document.getElementById("receive_flag").value="onlytags";
	document.getElementById("pinboard_list").disabled="true";	
	document.getElementById("txt_followstream_tags").disabled="";
	}
else if(document.getElementById("receive_flag").value=="all")
	{
	document.getElementById("txt_followstream_tags").disabled="";
	document.getElementById("pinboard_list").disabled="";
	}
else if(document.getElementById("receive_flag").value=="onlypinboards")
	{
	document.getElementById("txt_followstream_tags").disabled="true";
	document.getElementById("pinboard_list").disabled="";	
	}
else if(document.getElementById("receive_flag").value=="onlytags")
	{
	document.getElementById("pinboard_list").disabled="true";	
	document.getElementById("txt_followstream_tags").disabled="";
	}
}

function check_form_create_followstream()
{
//alert("hello");

if(document.getElementById("txt_followstream_name").value=='' || document.getElementById("txt_followstream_desc").value=='' 
|| document.getElementById("txt_followstream_name").value.replace(/ /g, "").length==0
|| document.getElementById("txt_followstream_desc").value.replace(/ /g, "").length==0)
	{
	alert("Please enter name and description. No special characters are allowed");
	return false;
	}

if(document.getElementById("receive_flag").value=="all" && (document.getElementById("txt_followstream_tags").value=="" 
|| document.getElementById("txt_followstream_tags").value.replace(/ /g, "").length==0 || document.getElementById("pinboard_list").value==''))
	{
	alert("Please enter valid values for tags and select pinboards");
	return false;
	}	
else if(document.getElementById("receive_flag").value=="onlypinboards" && (document.getElementById("pinboard_list").value==''))
	{
	alert("please select one or more pinboards");
	return false;
	}
else if(document.getElementById("receive_flag").value=="onlytags" && (document.getElementById("txt_followstream_tags").value=="" 
|| document.getElementById("txt_followstream_tags").value.replace(/ /g, "").length==0))
	{
	alert("please enter valid values for tags");
	return false;
	}
	
}

function check_form_upload_picture_desktop()
{
//alert("check upload picture");
if(document.getElementById("txt_desc").value=='' || document.getElementById("txt_tags").value==''
|| document.getElementById("txt_desc").value.replace(/ /g, "").length==0
|| document.getElementById("txt_tags").value.replace(/ /g, "").length==0)
	{
	alert("Please enter all values. No special characters or spaces are allowed");
	return false;
	}
else if(document.getElementById("pinboard_list").value=='select pinboard')
	{
	alert("Please select a pinboard from the list");
	return false;
	}
else if(document.getElementById("file").value=='')
	{
	alert("Please select a picture from desktop");
	return false;
	}

}

function check_form_add_website()
{
//alert("adding website");
if(document.getElementById("txt_website_desc").value=='' || document.getElementById("txt_website_tags").value=='' 
|| document.getElementById("txt_website_url").value=='' || document.getElementById("txt_website_desc").value.replace(/ /g, "").length==0 
|| document.getElementById("txt_website_tags").value.replace(/ /g, "").length==0
|| document.getElementById("txt_website_url").value.replace(/ /g, "").length==0)
	{
	alert("Please enter all values. No special characters or spaces are allowed");
	return false;
	}
else if(document.getElementById("pinboard_list").value=='select pinboard')
	{
	alert("Please select a pinboard from the list");
	return false;
	}
}

function check_form_create_pinboard()
{
//alert("check create pinboard");
if(document.getElementById("txt_pinboard_name").value=='' || document.getElementById("txt_pinboard_desc").value=='' 
|| document.getElementById("txt_pinboard_tags").value=='' || document.getElementById("txt_pinboard_name").value.replace(/ /g, "").length==0
|| document.getElementById("txt_pinboard_desc").value.replace(/ /g, "").length==0 || document.getElementById("txt_pinboard_tags").value.replace(/ /g, "").length==0)
	{
	alert("Please enter all values. No special characters or spaces are allowed");
	return false;
	}
}

function check_form_repin()
{
if(document.getElementById("txt_repin_desc").value=='' || document.getElementById("txt_repin_tags").value=='' 
|| document.getElementById("txt_repin_desc").value.replace(/ /g, "").length==0
|| document.getElementById("txt_repin_tags").value.replace(/ /g, "").length==0)
	{
	alert("Please enter all values. No special characters or spaces are allowed");
	return false;
	}
else if(document.getElementById("pinboard_list").value=='select pinboard')
	{
	alert("Please select a pinboard");
	return false;
	}
	
}


</script>

