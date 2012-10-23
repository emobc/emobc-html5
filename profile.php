<?php
/**
 * Copyright 2012 Neurowork Consulting S.L.
 *
 * This file is part of eMobc.
 *
 *  eMobc is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  eMobc is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with eMobc.  If not, see <http://www.gnu.org/licenses/>.
 *
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