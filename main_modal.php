<html>    
<head>
	<link href="css/navbar.css" rel="stylesheet">
</head>	
	
<body>	
<!-- Modal for upload picture-->
<form action="upload_picture_desktop.php" method="post" enctype="multipart/form-data" name="Form_upload_picture_desktop">
<div class="modal fade" id="modal_upload_pic" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Upload from desktop</h4>
      </div>
      <div class="modal-body">
		<input type="file" name="file" id="file">
		
		<br>
	  
		<div class="input-group">
		<span class="input-group-addon">Description<font color="red">*</font></span>
		<input type="text" name="txt_desc" id="txt_desc" class="form-control" placeholder="" maxlength="100">
		</div>
		<br>
		
		<div class="input-group">
		<span class="input-group-addon">Tags<font color="red">*</font></span>
		<input type="text" name="txt_tags" id="txt_tags" class="form-control" placeholder="" maxlength="30">
		</div>
		<br>
		
		<div id="upload_pic_pinboard_list"></div>
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" onclick="return check_form_upload_picture_desktop()">Submit</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>


<!-- Modal for add website-->
<form action="add_website.php" method="post" enctype="multipart/form-data" name="Form_add_website" onsubmit="return check_form_add_website();">
<div class="modal fade" id="modal_add_website" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add from website</h4>
      </div>
      <div class="modal-body">
		<div class="input-group">
		<span class="input-group-addon">URL<font color="red">*</font></span>
		<input type="text" name="txt_website_url" id="txt_website_url" class="form-control" placeholder="">
		</div>
		
		<br>
	  
		<div class="input-group">
		<span class="input-group-addon">Description<font color="red">*</font></span>
		<input type="text" name="txt_website_desc" id="txt_website_desc" class="form-control" placeholder="" maxlength="100">
		</div>
		<br>
		
		<div class="input-group">
		<span class="input-group-addon">Tags<font color="red">*</font></span>
		<input type="text" name="txt_website_tags" id="txt_website_tags" class="form-control" placeholder="" maxlength="30">
		</div>
		<br>
		
		<div id="add_website_pinboard_list"></div>
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" onclick="return check_form_add_website();">Submit</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>


<!-- Modal for create pinboard-->
<form action="create_pinboard.php" method="post" name="Form_create_pinboard">
<div class="modal fade" id="create_pinboard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Create Pinboard</h4>
      </div>
      <div class="modal-body">
		<div class="input-group">
		<span class="input-group-addon">Name<font color="red">*</font></span>
		<input type="text" name="txt_pinboard_name" id="txt_pinboard_name" class="form-control" placeholder="" maxlength="40">
		</div>
		
		<br>
	  
		<div class="input-group">
		<span class="input-group-addon">Description<font color="red">*</font></span>
		<input type="text" name="txt_pinboard_desc" id="txt_pinboard_desc" class="form-control" placeholder="" maxlength="100">
		</div>
		<br>
		
		<div class="input-group">
		<span class="input-group-addon">Privacy<font color="red">*</font></span>
		<select name="privacy" style="height:30px; vertical-align:center;" id="privacy">
		<option value="all">All</option>
		<option value="friends">Friends</option>
		<option value="private">Private</option>
		</select>
		</div>
		<br>
			
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" onclick="return check_form_create_pinboard();">Submit</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>


<!-- Modal for create followstream-->
<form action="create_followstream.php" method="post" name="Form_create_followstream">
<div class="modal fade" id="create_followstream" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Create Followstream</h4>
      </div>
      	  
	<div class="modal-body">
		<div class="input-group">
		<span class="input-group-addon">Name<font color="red">*</font></span>
		<input type="text" name="txt_followstream_name" id="txt_followstream_name" class="form-control" placeholder="" maxlength="40">
		</div>
		<br>
		  
		<div class="input-group">
		<span class="input-group-addon">Description<font color="red">*</font></span>
		<input type="text" name="txt_followstream_desc" id="txt_followstream_desc" class="form-control" placeholder="" maxlength="100">
		</div>
		<br>
		
		
		<div class="input-group">
		<span class="input-group-addon">Tags<font color="red">*</font></span>
		<input type="text" name="txt_followstream_tags" id="txt_followstream_tags" class="form-control" placeholder="" maxlength="40">		
		</div>
		<br>
		
		<div class="input-group">
		<span class="input-group-addon">Receive Flag<font color="red">*</font></span>
		<select name="receive_flag" id="receive_flag" style="height:30px; vertical-align:center;" onchange="receive_flag_change()">
		<option value="all">--Select--</option>
		<option value="all">All</option>
		<option value="onlypinboards">Only Pinboards</option>
		<option value="onlytags">Only Tags</option>
		</select>
		</div>
		
		<br>
		<div id="create_followstream_pinboard_list"></div>
		</div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" onclick="return check_form_create_followstream()">Submit</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>


<!-- Modal for repin-->
<form action="repin.php" method="post" name="Form_repin">
<div class="modal fade" id="repin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Repin</h4>
      </div>
      <div class="modal-body">
	  
	  				<div class="input-group">
					<span class="input-group-addon">Description<font color="red">*</font></span>
					<input type="text" name="txt_repin_desc" id="txt_repin_desc" class="form-control" placeholder="" maxlength="100">
					</div>
					<br>
	  
					<div class="input-group">
					<span class="input-group-addon">Tags<font color="red">*</font></span>
					<input type="text" name="txt_repin_tags" id="txt_repin_tags" class="form-control" placeholder="" maxlength="40">
					</div>
					<br>
								
					<div class="input-group">
					<div id="repin_pinboard_list"></div>
					</div>
					<br>
					
					<input type="hidden" name="repin_txt_picture_id" id="repin_txt_picture_id" value=""></input><br>
					<input type="hidden" name="repin_txt_pinboard_id" id="repin_txt_pinboard_id" value=""></input>
  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" onclick="return check_form_repin()">Submit</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>


<script language="javascript">








</script>

</body>
</html>