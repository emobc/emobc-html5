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


define("RUTA_ABS",dirname(__FILE__));
include('conf/path.php'); 
if (isset($_POST['send_search'])) {
$searchValue = trim(htmlspecialchars($_POST['search']));

	if (!isset($_COOKIE['searchContent'])) {
	error_log('entro aqui');
		include($classPath.'app_class.php');
		$result_app = app($xmlPath.'app.xml');
		$levelFile = $result_app[2];
		$levelId = $result_app[0];
		
		for ($x = 0; $x < count($levelId); $x++) {
			setcookie("searchLevel[$x]",$levelId[$x], time() + 604800);
			if(!strrpos("__".$levelFile[$x], "http")){
			   $sx = simplexml_load_file($xmlPath.$levelFile[$x]);
			} else {
  			   $content = file_get_contents($levelFile[$x]);
			   $sx = simplexml_load_string($content);
			   $i = 0;
			   foreach($sx->data as $item) {
			   	setcookie("searchData[$x][$i]",$item->dataId, time() + 604800);
				setcookie("searchContent[$x][$i]",$item->headerText,time() + 604800);
				$i++;
			   }
			}
		}
		
	}
	header('location: search.php?s='.$searchValue.'');
	exit();
}

$activity = 'FORM_ACTIVITY';
$title = 'Search';
include($srcPath.'header.php');
include('src/search_script.php');
include($srcPath.'base.php');
?>