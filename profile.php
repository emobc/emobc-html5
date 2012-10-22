<?php
/**
* Path include
*/
if (isset($_POST['send_profile'])) {
	for ($p = 0; $p < $_POST['total_campos']; $p++) {
		setcookie($_POST["datos$p"],$_POST[$_POST["datos$p"]], time() + 31536000); 
	}
	setcookie("profile",1, time() + 31536000);
	header('location: profile.php');
	exit();
}

define("RUTA_ABS",dirname(__FILE__));
include('conf/path.php');
$activity = 'FORM_ACTIVITY';
$title = 'Profile';
include($srcPath.'header.php');
include('src/profile_script.php');
include($srcPath.'base.php');
?>