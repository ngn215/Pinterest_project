<?php
session_start();

if(isset($_SESSION['user_id']))
	{
		$type = strval($_GET['type']);
		$value = strval($_GET['value']);
		
		if($type=="pinboard")
		{
		$_SESSION['pinboard_id']=$value;
		}
		else if($type=="picture")
		{
		$_SESSION['picture_id']=$value;
		}
		else if($type=="picture_pinboard")
		{
		$pinboard_id = substr($value,0,strpos($value, "_"));
		$picture_id = substr($value,strpos($value, "_")+1,strlen($value));
		
		echo $pinboard_id;
		echo $picture_id;
		
		$_SESSION['picture_id']=$picture_id;
		$_SESSION['pinboard_id']=$pinboard_id;
		}
				

			echo "session variables have been set";
	}	
else
	{
	echo "session expired";
	}
	

?>
