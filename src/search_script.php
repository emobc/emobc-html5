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
 
echo'<div class="ui-grid-solo align-center">
       <div class="ui-block-a">
	   <form method="POST" action="search.php" data-ajax="false">
            <input type="text" name="search" />
	    <input type="submit" name="send_search" value="Buscar" />	
	   </form>
       </div>
   </div><br/><br/>';

if (trim($_GET['s']) != '') {
	if (isset($_COOKIE['searchContent'])) {
	   $searchValue = trim($_GET['s']);
	   for ($x = 0; $x < count($_COOKIE['searchContent']); $x++) {
	      for ($i = 0; $i < count($_COOKIE['searchContent'][$x]); $i++) {
		  $string = $_COOKIE['searchContent'][$x][$i];
		  $found = stripos($string,$searchValue);
		  
		  if ($found !== FALSE) {
		  	$contentValue[] = $string;
			$dataValue[] = $_COOKIE['searchData'][$x][$i];
			$levelValue[] = $_COOKIE['searchLevel'][$x];
		  }
	      }
	   }
	} else {
	   echo'<p> Ocurri&oacute; un error en la b&uacute;squeda. </p>';
	}
	if (isset($contentValue)) {
	   echo'<ul data-role="listview">';
	   for ($c = 0; $c < count($contentValue); $c++) {
	       echo'<li><a href="index.php?level='.$levelValue[$c].'&data='.$dataValue[$c].'">'.$contentValue[$c].'</a></li>
	       ';
	   }
	   echo'</ul>';
	}
} 
?>