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

/**
* Path include
*/

session_start();

define("RUTA_ABS",dirname(__FILE__));
include('conf/path.php');

if (isset($_GET["xmlDir"])) {
$rootPath = RUTA_ABS.'/';
$classPath = $rootPath.'parser/';
$assetsPath = $_GET["xmlDir"]."/";
$_SESSION["xmlDir"] = $assetsPath;
$xmlPath = $rootPath.$assetsPath.'/xml/';
$srcPath = $rootPath.'src/';
}	

if (isset($_SESSION["xmlDir"])) {
	$assetsPath = $_SESSION["xmlDir"]."/";
	$xmlPath = $rootPath.$assetsPath.'/xml/';
} else {
	$assetsPath = "assets/";
	$xmlPath = $rootPath.$assetsPath.'/xml/';
}

/**
* app parser include
*/
include($classPath.'app_class.php');
$result_app = app($xmlPath.'app.xml');
$adsType = $result_app[8];
$adsPos = $result_app[9];
$adsId = $result_app[10];

if (isset($result_app[14]) && $result_app[14] != '') {
	if (!isset($_COOKIE['profile']) || $_COOKIE['profile'] == 0) {
		header('location: profile.php?editmode');
		exit();
	}
}

/**
* Replace function to set page transitions
* @param string $pagetransition Transition mode
* @paaram string $pageLevel page Activity
* @return $trans Transition
*/
function replace($pagetransition,$pageLevel) {
	if ( ($pageLevel != 'CALENDAR_ACTIVITY') && ($pageLevel != 'MAP_ACTIVITY') ) {
		if ($pagetransition == 'flipLeft')
			$trans = 'data-transition="reverse flip"';
		else if ($pagetransition == 'flipRight')
			$trans = 'data-transition="flip"';
		else if ($pagetransition == 'CurlDown')
			$trans = 'data-transition="slidedown"';
		else if ($pagetransition == 'CurlUp')
			$trans = 'data-transition="slideup"';
		else
			$trans = 'data-transition="slide"';
	} else {
		$trans = 'data-rel="external" data-ajax="false"';
	}
return $trans;
}
/**
* If level and data not exists, search for entryPoint
*/
if ( (!isset($_GET['level']) || $_GET['level'] == '') && (!isset($_GET['data']) || $_GET['data'] == '') ) {
	$levelPoint = $result_app[4];
	$dataPoint = $result_app[5];
	if ( (isset($levelPoint) && $levelPoint != '') && (isset($dataPoint) && $dataPoint != '') ) {
		header('Location: '.$_SERVER['PHP_SELF'].'?level='.$levelPoint.'&data='.$dataPoint.'');
	}
}
/**
* if level exists, search in app.xml
*/
if ( isset($_GET['level']) && $_GET['level'] != '') {
	if ( isset($result_app[0]) ) {
		$levelId = $result_app[0];
		$levelFile = $result_app[2];
		$levelType = $result_app[3];
		$levelTransition = $result_app[11];
		// Estilos de pantalla
		$levelFormatBg = $result_app[12];
		$levelFormatComp = $result_app[13]; 
		/**
		* if entrypoint is the main section, header.php will not add home and back buttons
		*/
		$levelPoint = $result_app[4];
		$dataPoint = $result_app[5];
		
		/**
		* Search $_GET['level'] in levelId.
		*/
		$activity = '';
		$file = '';
		for ($x = 0; $x < count($levelId) ; $x++) {
			if ( trim($_GET['level']) == trim($levelId[$x]) ) {
				$activity = $levelType[$x];
				$file = $levelFile[$x];
				$formatBg = $levelFormatBg[$x];
				$formatComp = $levelFormatComp[$x];
			}
		}
	}
/**
* If level not exists, activity is cover.
*/
} else {
 $levelId = $result_app[0];
 $levelTransition = $result_app[11];
 $levelType = $result_app[3];
 $activity = 'COVER_ACTIVITY';
 $trans = 'data-transition="slide"';
}

/**
* Search for data in activity's xml, if data not exists, activity is cover
*/

if ( isset($_GET['data']) && $_GET['data'] != '') {
	if ($_GET['data'] == 'profile') {
		header('location: profile.php');
		exit();
	}
	if ($_GET['data'] == 'search') {
		header('location: search.php?s');
	}
	if ( isset($activity) && ($activity != 'COVER_ACTIVITY') ) {
		if ($activity != '') {
			/**
			* Activity parser, if activity exists and is not cover
			*/
			include($classPath.strtolower($activity).'.php');
			if(!strrpos("__".$file, "http")){
			$result = traer_info($xmlPath.$file,$_GET['data']);
			} else {
			$result = traer_info($file,$_GET['data']);
			}
		}
	}
}  else {
	/**
	* Cover parser
	*/
	include($classPath.'portada_class.php');
	$result = portada($xmlPath.'portada.xml');
}

/**
* Search for formatBg/formatComp in app.xml to apply specific activity style
*/
if ( (isset($formatBg) && $formatBg != '') || (isset($formatComp) && $formatComp != '') ) {
	$background = $formatBg;
	if ($formatComp != '') {
		/**
		* Format parser
		*/
		include($classPath.'format_class.php');
		$styleFormat = format($xmlPath.'formats.xml');
		$name = $styleFormat[0];
		$color = $styleFormat[1];
		$size = $styleFormat[2];
		$type = $styleFormat[3];
		$face = $styleFormat[4];
		$arrow = $styleFormat[5];
		
		$searchFormat = explode(';',$formatComp);
		for ($x = 0; $x < count($searchFormat); $x++) {
			if ($searchFormat[$x] != ''){
				$getFormat[] = explode('=',$searchFormat[$x]);
			}
		}
		$custom_css = '<style type="text/css">';
		for ($x = 0; $x < count($getFormat); $x++){
			$ssearch = array_search($getFormat[$x][1],$name);
			if ($ssearch != 'FALSE') {
				$custom_css .= '
				.'.$getFormat[$x][0].' {
				color: '.$color[$ssearch].';
				font-size: '.$size[$ssearch].';
				font-weight: '.$type[$ssearch].';
				font-face: '.$face[$ssearch].';
				}';
			}
		}
		$custom_css .='</style>';
	}
	/**
	* Search in styles.xml to apply styles to specific type of activity
	*/
} else {
	if ( file_exists($xmlPath.'styles.xml')) {
		include($classPath.'styles_class.php');
		$style = styles($xmlPath.'styles.xml');		
		for ($x = 0; $x < count($style[0]); $x++) {
			if ( trim($style[0][$x]) == trim($activity) ) {
				$background = $style[1][$x];
				$formatComp = $style[2][$x];
			}
		}
		if (isset($formatComp) ) {
		/**
		* Format parser
		*/
		include($classPath.'format_class.php');
			$styleFormat = format($xmlPath.'formats.xml');
			$name = $styleFormat[0];
			$color = $styleFormat[1];
			$size = $styleFormat[2];
			$type = $styleFormat[3];
			$face = $styleFormat[4];
			$arrow = $styleFormat[5];
			
			$searchFormat = explode(';',$formatComp);
			for ($x = 0; $x < count($searchFormat); $x++) {
				if ($searchFormat[$x] != ''){
					$getFormat[] = explode('=',$searchFormat[$x]);
				}
			}
			$custom_css = '<style type="text/css">';
			for ($x = 0; $x < count($getFormat); $x++){
				$ssearch = array_search($getFormat[$x][1],$name);
					$custom_css .= '
					.'.$getFormat[$x][0].' {
					color: '.$color[$ssearch].';
					font-size: '.$size[$ssearch].';
					font-weight: '.$type[$ssearch].';
					font-face: '.$face[$ssearch].';
					}';
			}
			$custom_css .='</style>';
		}
	}
}

switch ($activity) {
	/**
	* List_activity
	*/
	case 'LIST_ACTIVITY':
	$title = $result[4];
	/**
	* @ignore
	*/
	include($srcPath.'header.php');
	
	if ( isset($result[1]) ) {
		$text = $result[1];
		$description = $result[5];
		$image = $result[0];
		$nextLevel = $result[2];
		$nextData = $result[3];
		
		
		if ( isset($arrow) && $arrow != '') {
			echo'<style>
			.ui-icon-arrow-custom {
			background: url("'.$arrow.'");
			}
			</style>';
			$arrow_style = 'data-icon = "arrow-custom"';
		} else {
			$arrow_style = '';
		}
		
		echo'<ul data-role="listview" data-inset="true">';
		for ($x=0; $x < count($text); $x++) {
			if ( $nextLevel[$x] != '' && $nextData[$x] != '') {
				$tr = array_search(trim($nextLevel[$x]),$levelId);
				if ($tr != 'FALSE')
					$levelTrans = replace($levelTransition[$tr],$levelType[$tr]);
				echo'<li>
				<a '.$levelTrans.' href="'.$_SERVER['PHP_SELF'].'?level='.$nextLevel[$x].'&data='.$nextData[$x].'">';
				
				if ($image[$x] != '')
				echo'<img src="'.$assetsPath.$image[$x].'">';
				
				echo'<h3 class="selection_list">'.$text[$x].'</h3>
				<p class="selection_list">'.$description[$x].'</p>
				</a>
				</li>';
			} else {
				echo'<li>
				<a href="#">'.$text[$x].'</a>
				</li>';
			}
			
		}
		echo'</ul>';
	}
	/**
	* @ignore
	*/	
	include($srcPath.'base.php');
	break;
	
	/**
	* image_text_description_activity
	*/
	case 'IMAGE_TEXT_DESCRIPTION_ACTIVITY':
	if ( isset($result[2]) ) {
		$title = $result[0];
		$text = $result[2];
		if ( isset($result[1]) )
			$image = $result[1];
	/**
	* @ignore
	*/
		include($srcPath.'header.php');
		echo'<div class="ui-grid-solo align-center">
			<div class="ui-block-a"><h1 class="header">'.$title.'</h1></div>
			<div class="ui-block-a"><font class="basic_text">'.$text.'</font></div>';
		if ( isset($image) && $image != '')
			echo'<div class="ui-block-a"><img src="'.$assetsPath.$image.'" border="0" style="width: 100%; height: auto;"></div>';
		echo'
		</div>';
	/**
	* @ignore
	*/
		include($srcPath.'base.php');
	}
	break;
	
	/**
	* image_gallery_activity
	*/
	case 'IMAGE_GALLERY_ACTIVITY':
	if ( isset($result[0]) ) {
		$image = $result[0];
		$pageId = 'idGallery';
	/**
	* @ignore
	*/
		include($srcPath.'header.php');
		echo'<ul class="gallery">';
		for ($x = 0; $x < count($image); $x++)
			echo'<li><a href="'.$assetsPath.$image[$x].'" rel="external"><img src="'.$assetsPath.$image[$x].'" border="0"></a></li>';
		echo'</ul>';
		include($srcPath.'base.php');
	} else {
		include($srcPath.'header.php');
	echo '<div class="ui-grid-solo align-center">
			<div class="ui-block-a">
			<p class="basic_text">Esta galer&iacute;a no contiene im&aacute;genes.</p>
			</div>
	     </div>
	     ';
	/**
	* @ignore
	*/
		include($srcPath.'base.php');
	}
	break;
	
	/**
	* image_zoom_activity
	*/
	case 'IMAGE_ZOOM_ACTIVITY':
	if ( isset($result[0]) ) {
		$image = $result[0];
	/**
	* @ignore
	*/
		include($srcPath.'header.php');
		echo'<div class="ui-grid-solo align-center">
			<div class="ui-block-a"><img src="'.$assetsPath.$image.'" border="0" style="width: 100%; height: auto;"></div>
		     </div>';
	/**
	* @ignore
	*/
		include($srcPath.'base.php');
	}
	break;
	
	/**
	* image_list_activity
	*/
	case 'IMAGE_LIST_ACTIVITY':
	if ( isset($result[1]) || isset($result[0]) ) {
		if ( isset($arrow) && $arrow != '') {
			echo'<style>
			.ui-icon-arrow-custom {
			background: url("'.$arrow.'");
			}
			</style>';
			$arrow_style = 'data-icon = "arrow-custom"';
		} else {
			$arrow_style = '';
		}
	/**
	* @ignore
	*/
		include($srcPath.'header.php');
		if ( isset($result[0]) && $result[0] != '') {
			$image = $result[0];
			echo'<div class="ui-grid-solo align-center">
				<div class="ui-block-a"><img src="'.$assetsPath.$image.'" border="0"></div>
			     </div>';
		}
		$text = $result[1];
		$nextLevel = $result[2];
		$nextData = $result[3];
		echo'<ul data-role="listview" data-inset="true">';
		for ($x=0; $x < count($text); $x++) {
			if ( $nextLevel[$x] != '' && $nextData[$x] != '') {
				$tr = array_search(trim($nextLevel[$x]),$levelId);
				if ($tr != 'FALSE')
					$levelTrans = replace($levelTransition[$tr],$levelType[$tr]);
				else
					$levelTrans = '';
				echo'<li>
				<a '.$levelTrans.' href="'.$_SERVER['PHP_SELF'].'?level='.$nextLevel[$x].'&data='.$nextData[$x].'">
				<h3 class="selection_list">'.$text[$x].'</h3></a>
				</li>';
			} else {
				echo'<li>
				<a href="#"><h3 class="selection_list">'.$text[$x].'</h3></a>
				</li>';
			}
			
		}
		echo'</ul>';
	/**
	* @ignore
	*/
		include($srcPath.'base.php');
	}
	break;
	
	/**
	* pdf_activity
	*/
	
	case 'PDF_ACTIVITY':
	if ( isset($result[1]) ) {
		$title = $result[0];
		$pdfURL = $result[1];
	/**
	* @ignore
	*/
		include($srcPath.'header.php');
			echo'<div class="ui-grid-solo align-center">
				<div class="ui-block-a"><h1 class="header">'.$title.'</h1></div>
				<div class="ui-block-a"><object width="100%" height="500" type="text/html" data="'.$pdfURL.'"></object></div>
			    </div>';
	/**
	* @ignore
	*/
		include($srcPath.'base.php');
	}
	break;
	
	/**
	* video_activity
	*/
	case 'VIDEO_ACTIVITY':
	if ( isset($result[1]) ) {
	$title = $result[0];
	$videoURL = $result[1];
	$v = explode("v=",$videoURL);
	/**
	* @ignore
	*/
		include($srcPath.'header.php');
			echo'<div class="ui-grid-solo align-center">
				<div class="ui-block-a"><h1 class="header">'.$title.'</h1></div>
				<div class="ui-block-a"><object width="100%" height="300" type="text/html" data="http://www.youtube.com/embed/'.$v[1].'"></object></div>
			    </div>';
	/**
	* @ignore
	*/
		include($srcPath.'base.php');
	}
	break;
	
	/**
	* web_activity
	*/
	case 'WEB_ACTIVITY':
	if ( isset($result[1]) ) {
		$title = $result[0];
		$webURL = $result[1];
		if (strpos($webURL, "http") !== false)
			$webURL = $webURL;
		else
			$webURL = "assets/html/".$webURL;

	/**
	* @ignore
	*/
		include($srcPath.'header.php');
			echo'<div class="ui-grid-solo align-center">
				<div class="ui-block-a"><h1 class="header">'.$title.'</h1></div>
				<div class="ui-block-a"><object width="100%" height="500" type="text/html" data="'.$webURL.'"></object></div>
			    </div>';
	/**
	* @ignore
	*/
		include($srcPath.'base.php');
	}
	break;
	
	/**
	* audio_activity
	*/
	case 'AUDIO_ACTIVITY':
	if ( isset($result[1]) ) {
		$title = $result[0];
		$audioURL = $result[1];
	/**
	* @ignore
	*/
		include($srcPath.'header.php');
		echo'<div class="ui-grid-solo align-center">	
			<div class="ui-block-a"><h1 class="header">'.$title.'</h1></div>
			<div class="ui-block-a">
			<audio controls="controls">
			  <source src="'.$audioURL.'" type="audio/mp3" />
			  Tu navegador no soporta audio.
			</audio>
			</div>
		     </div>';
	/**
	* @ignore
	*/
		include($srcPath.'base.php');
	}
	break;
	
	/**
	* map_activity
	*/
	case 'MAP_ACTIVITY':
	$pageId = 'idMap';
	/**
	* @ignore
	*/
	include($srcPath.'header.php');
	echo'<div class="ui-grid-solo align-center">
		<div class="ui-block-a">
		<div id="map_canvasRoast" stlye="width: 100%; height: 450px"></div>
		</div>
	     </div>';	
	?>
	<script type="text/javascript">
	var center;
	var map = null;
	
	function Newinitialize(lat,lng) {
	center = new google.maps.LatLng(lat,lng);
	var myOptions = {
	zoom: 6,
	center: center,
	mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById("map_canvasRoast"), myOptions);
	}
	
	function detectBrowser() {
	var mapdivMap = document.getElementById("map_canvasRoast");
	mapdivMap.style.width = '100%';
	mapdivMap.style.height = '450px';
	};			
	
	/*if(navigator.geolocation) {
	detectBrowser();
	navigator.geolocation.getCurrentPosition(function(position){
	Newinitialize(position.coords.latitude,position.coords.longitude);
	});
	}
	else {*/
		detectBrowser();
		Newinitialize(40.2513,3.4221);
		google.maps.event.trigger(center, 'resize');
	
	//}
	function add_marker(lat,lng,title,icon,address,dir,level,data) {
 
 		var infowindow = new google.maps.InfoWindow({								  
		  content: address+'<br><small>'+title+'</small><br><a data-transition="slide" href="'+dir+'?level='+level+'&data='+data+'">Informaci&oacute;n</a>'
		});
		var marker = new google.maps.Marker({
		  position: new google.maps.LatLng(lat,lng),
		  title: title,
		  icon: icon
		});
		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		});	
		marker.setMap(map);
	}
	<?php
	if ( isset($result[0])) {
		$positionTitle = $result[0];
		$positionAddress = $result[1];
		$positionLat = $result[2];
		$positionLon = $result[3];
		$icon = $result[4];
		$nextLevelDataId = $result[5];
		$nextLevelId = $result[6];
		
		$dir = $_SERVER['PHP_SELF'];
		
		for ($x = 0; $x < count($result[0]); $x++) {
		if ( isset($nextLevelId[$x]) && $nextLevelId[$x] != '') {
			$level = $nextLevelId[$x];
			$data = $nextLevelDataId[$x];
		} else {
			$level = 'error';
			$data = 'error';
		}
			echo'add_marker('.$positionLat[$x].','.$positionLon[$x].',"'.$positionTitle[$x].'","'.$assetsPath.$icon[$x].'","'.$positionAddress[$x].'","'.$dir.'","'.$level.'","'.$data.'");';			
		}
	}
	?>
	
	</script>
	<!-- 	FIN DE CREACION DEL MAPA -->
	<?php
	/**
	* @ignore
	*/
	include($srcPath.'base.php');
	break;
	
	/**
	* quiz_activity
	*/
	case 'QUIZ_ACTIVITY':
	if ( isset ($result[3]) ) {
		$title = $result[0];
		$description = $result[1];
		$first = $result[2];
		$idQuestion = $result[3];
		$imageFile = $result[4];
		$text = $result[5];
		$point = $result[6];
		$answer = $result[7];
		$correct = $result[8];
		$next = $result[9];
	/**
	* @ignore
	*/
		include($srcPath.'header.php');
			echo '<div class="ui-grid-solo align-center">
			<div class="ui-block-a">
			<h2 class="header">'.$title.'</h2>
			<p class="basic_text">'.$description.'</p><br />';	
			if ( isset($_SESSION['start']) && $_SESSION['start'] == 1) {
				/**
				* Save in quiz questions which already shown
				*/
				if ( !isset($_SESSION['quiz'.$_GET['data']]) ){
					$_SESSION['quiz'.$_GET['data']][] = '';
					$_SESSION['quiz'.$_GET['data'].'point'] = '';
				}
			}
			

			if ( isset($_POST['send_resp']) && (isset($_POST['answers']) && $_POST['answers'] != '' ) ) {
				/**
				* Normal mode
				*/
				if ($first == '') {
					$_SESSION['quiz'.$_GET['data']][] = $_POST['question_num'];

					if ($correct[$_POST['question_num']][$_POST['answers']] == 'true') {
					
						$_SESSION['quiz'.$_GET['data'].'point']	= $_SESSION['quiz'.$_GET['data'].'point'] + $point[$_POST['question_num']];		

					}
					$question = rand(0,(count($idQuestion)-1));
					$xx = array_search($question, $_SESSION['quiz'.$_GET['data']]);
					if ( $xx != FALSE ) {
						while($xx != FALSE) {
							$question = rand(0,(count($idQuestion)-1));
							$xx = array_search($question, $_SESSION['quiz'.$_GET['data']]);
						}
					}
				/**
				* Adventure mode.
				*/
				} else {
					$question = array_search(trim(strtolower($next[$_POST['question_num']][$_POST['answers']])),$idQuestion);

					if ($question === FALSE){
						echo'
						<font class="basic_text">Has acabado el modo aventura</font>
						</div></div>';
						include('base.php');
						exit;
					}
				}
			/**
			* First time.
			*/
			} else {
				/**
				* if is it normal mode, start on random question.
				*/
				if ($first == '') {			
					$question = rand(0,(count($idQuestion)-1));
				} else {
				/**
				* adventure mode, start on first question
				*/
					$question = array_search(trim(strtolower($first)),$idQuestion);
				}
			}
			if ( isset($resp) )
				echo $resp;
				
			if ( count( $_SESSION['quiz'.$_GET['data']]) >= (count($idQuestion)+1) ) {
				echo'
				<font class="basic_text">Has conseguido una puntuaci&oacuten de '.$_SESSION['quiz'.$_GET['data'].'point'].'</font><br>
				</div></div>';
				unset ($_SESSION['quiz'.$_GET['data']]);
				unset ($_SESSION['quiz'.$_GET['data'].'point']);
			} else {
			echo'<form method="POST" action="'.$_SERVER['PHP_SELF'].'?level='.$_GET['level'].'&data='.$_GET['data'].'" data-ajax="false">
			<input type="hidden" name="question_num" value="'.$question.'">
			<h3>Pregunta '.($question+1).':</h3>
			<fieldset data-role="controlgroup">
			<legend>';
			if ( isset($imageFile[$question]) && $imageFile[$question] != '')
				echo'<img src="'.$assetsPath.$imageFile[$question].'" style="width: 50%; height: auto;">';
			echo'<p class="basic_text">'.$text[$question].'</p></legend>';
			for ($x = 0; $x < count($answer[$question]); $x++) {
				echo '
				<input type="radio" name="answers" value="'.$x.'" id='.$question.'x'.$x.'>
				<label for="'.$question.'x'.$x.'"><font class="basic_text">'.$answer[$question][$x].'</font></label>';
			}
			echo'</fieldset>
			<br /><input type="submit" name="send_resp" value="Enviar">
			</form>
			</div>
			</div>';
			}
	/**
	* @ignore
	*/
		include($srcPath.'base.php');
	} else {
		$title = $result[0];
		$description = $result[1];
	/**
	* @ignore
	*/	
		include($srcPath.'header.php');
	echo '<div class="ui-grid-solo align-center">
			<div class="ui-block-a">
			<h2 class="header">'.$title.'</h2>
			<p class="basic_text">'.$description.'</p><br />			
			<p class="basic_text">Este quiz no dispone de preguntas.</p>
			</div>
	     </div>
	     ';
	/**
	* @ignore
	*/
		include($srcPath.'base.php');
	}
	break;
	
	/**
	* Calendar_activity
	*/
	case 'CALENDAR_ACTIVITY':
	$title = $result[0];
	$titleEvent = $result[1];
	$dateEvent = $result[4];
	$timeEvent = $result[5];
	$textEvent = $result[6];
	$nextLevel = $result[2];
	$nextData = $result[3];
	$pageId = 'idCalendar';
	/**
	* @ignore
	*/
	include($srcPath.'header.php');	
	echo'
	<center>
	<div id="datepicker"></div>
	</center>
	<div class="ui-grid-solo align-center">
	<div class="ui-block-a align-center">
	<ul data-role="listview" data-inset="true" id="eventos">
	</ul></div>
	</div>
';
	/**
	* @ignore
	*/
	include($srcPath.'base.php');
	break;
	
	/**
	* form_activity
	*/
	case 'FORM_ACTIVITY':
	if ( isset($result[3]) ) {
		/**
		* If actionURL not exists, search in app.xml for next level
		*/
		$nextLevel = $result[0];
		$actionUrl = $result[2];
		
		$fieldType = $result[3];
		$fieldLabel = $result[4];
		$fieldName = $result[5];
		$fieldParam = $result[6];
		
		if ( isset($_POST['send_form']) ) {

			if ( !isset($_POST['action']) || $_POST['action'] == '') {
				for ($p = 0; $p < $_POST['total_campos']; $p++) {
					if ($p == 0 && $_POST[$_POST['datos0']] != "") {
						$nextdata.= strtolower($_POST[$_POST['datos0']]);
					} else if ($p != 0 && $_POST[$_POST['datos0']] == "") {
						$nextdata.= "_".strtolower($_POST[$_POST['datos$p']]);
					} else {
						$nextdata.= strtolower($_POST[$_POST['datos$p']]);
				        }
				}
				header('Location: '.$_SERVER['PHP_SELF'].'?level='.$_POST['level'].'&data='.$nextdata.'');

			} else {
					for ($p = 0; $p < $_POST['total_campos']; $p++){
						if ($_POST[$_POST["datos$p"]] != "")
							if ($p == 0)
							$pagina = $_POST['datos$p'].'='.$_POST[$_POST['datos$p']];
							else
							$pagina = $pagina.'&'.$_POST['datos$p'].'='.$_POST[$_POST['datos$p']];				
					}
					
					$ch = curl_init($_POST['action']);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
					curl_setopt ($ch, CURLOPT_POST, 1);
					curl_setopt ($ch, CURLOPT_POSTFIELDS, $pagina);
					$response = curl_exec ($ch);
					curl_close ($ch);
						@session_start();
						unset($_SESSION['app']);
						$_SESSION['app'] = "";
						$_SESSION['app'] = $response;
						$app = simplexml_load_string($_SESSION['app']);
							foreach($app->levels->level as $item2){
								if ($item2->levelId == $_POST['level']){
									$dirnext = $item2->levelFile;
									$datos = $item2->levelId;
								}
							}
						header('Location: '.$_SERVER['PHP_SELF'].'?level='.$dirnext.'&data='.$datos.'');
			}
		}
	/**
	* @ignore
	*/
		include($srcPath.'header.php');
		echo'<div class="ui-grid-solo align-center">
		<div class="ui-block-a">
		<form method="POST" action="'.$_SERVER['PHP_SELF'].'?level='.$_GET['level'].'&data='.$_GET['data'].'" data-ajax="false">
		';
		if ($actionUrl != '') {
			echo'<input type="hidden" name="action" value="'.$actionUrl.'">
			';
		}
			echo'<input type="hidden" name="level" value="'.$nextLevel.'">
			';
		for ($x = 0; $x < count($fieldType); $x++) {
			echo'<input type="hidden" name="datos'.$x.'" value="'.$fieldName[$x].'">';
			if ( ($fieldType[$x] != 'INPUT_IMAGE') && ($fieldType[$x] != 'INPUT_CHECK') ) {
				echo'<label for="'.$fieldName[$x].'"><font class="basic_text">'.$fieldLabel[$x].'</font></label><br />';
			}
			switch ($fieldType[$x]) {
				case 'INPUT_PICKER':
				echo'<br /><select name="'.$fieldName[$x].'">';
				for ($i = 0; $i < count($fieldParam[$x]); $i++) {
					echo'<option name="'.$fieldParam[$x][$i].'">'.$fieldParam[$x][$i].'</option>
					';
				}
				echo'</select>';
				break;
				
				case 'INPUT_TEXT':
				echo'<br /><input type="text" name="'.$fieldName[$x].'">';
				break;
				
				case 'INPUT_TEXTVIEW':
				echo'<br /><textarea name="'.$fieldName[$x].'"></textarea>';
				break;
				
				case 'INPUT_NUMBER':
				echo'<br /><input type="number" name="'.$fieldName[$x].'">';
				break;
				
				case 'INPUT_EMAIL':
				echo'<br /><input type="email" name="'.$fieldName[$x].'">';
				break;
				
				case 'INPUT_PHONE':
				echo'<br /><input type="tel" name="'.$fieldName[$x].'">';
				break;
				
				case 'INPUT_PASSWORD':
				echo'<br /><input type="password" name="'.$fieldName[$x].'">';
				break;
				
				case 'INPUT_CHECK':
				echo'<fieldset data-role="controlgroup">
				<legend>'.$fieldLabel[$x].'</legend>';
				for ($i = 0; $i < count($fieldParam[$x]); $i++) {
					echo'<input type="radio" name="'.$fieldName[$x].'" value="'.$fieldParam[$x][$i].'" id="'.$x.'x'.$i.'">
					<label for="'.$x.'x'.$i.'"><font class="basic_text">'.$fieldParam[$x][$i].'</font></label>';
				}
				echo'</fieldset>';
				break;								
			}
		}
		echo'<input type="hidden" name="total_campos" value="'.$x.'">
		<br /><input type="submit" name="send_form" value="Enviar">
		</form>
		</div>
		</div>';
	/**
	* @ignore
	*/
		include($srcPath.'base.php');
	}
	break;
	
	/**
	* buttons_activity
	*/
	case 'BUTTONS_ACTIVITY':
	/**
	* @ignore
	*/
	include($srcPath.'header.php');
	if ( isset($result[1]) ) {
		$titleButton = $result[1];
		if (isset($result[0]))
			$imgButton = $result[0];
		$nextLevel = $result[2];
		$nextData = $result[3];
		echo'<div class="ui-grid-a align-center">
		';
		$block = 'b';
		for ($x = 0; $x < count($titleButton); $x++) {
		
		if ($block == 'b')
			$block = 'a';
		else
			$block = 'b';
			 
		echo'<div class="ui-block-'.$block.' align-center">
		';
			if ( $nextLevel[$x] != '' && $nextData[$x] != ''){
				$tr = array_search(trim($nextLevel[$x]),$levelId);
				if ($tr != 'FALSE')
					$levelTrans = replace($levelTransition[$tr],$levelType[$tr]);
				$url = $_SERVER['PHP_SELF'].'?level='.$nextLevel[$x].'&data='.$nextData[$x];
			} else {
				$url = '#';
				$levelTrans = '';
			}
			if ( $imgButton[$x] != '' )
				echo'<a '.$levelTrans.' href="'.$url.'"><img src="'.$assetsPath.$imgButton[$x].'" title="'.$titleButton[$x].'"></a>';
			else
				echo'<a '.$levelTrans.' data-role="button" href="'.$url.'"> '.$titleButton[$x].'</a><br />';
		echo'</div>
		';
		}
		echo'</div>
		';
	}
	/**
	* @ignore
	*/
	include($srcPath.'base.php');
	break;
	
	/**
	* Canvas Activity (NOT IMPLEMENTED YET)
	*/
/*	case 'CANVAS_ACTIVITY':
	$pageId = 'idCanvas';
	include($srcPath.'header.php');
	echo'
	<div class="ui-grid-solo align-center">
		<div class="ui-block-a">
		<canvas class="pad" style="border: 1px solid #000" id="canvasDiv" width="400px" height="400px"></canvas>
		</div>
		<script src="lib/js/jquery.signaturepad.min.js"></script>
		  <script>
		    $("#idCanvas").live("pageshow",function(){
		      $("#canvasDiv").signaturePad({drawOnly:true});
		    });
		  </script>
		   <script src="lib/js/json2.min.js"></script>
	</div>
	';
	include($srcPath.'base.php');
	break;
*/
	/**
	* QR_activity (only in android/ios version)
	*/
	case 'QR_ACTIVITY':
	/**
	* @ignore
	*/
	include($srcPath.'header.php');
	echo'<p> El servicio de QR s&oacute;lo est&aacute; disponible en la versi&oacute;n nativa. </p>
	';
	/**
	* @ignore
	*/
	include($srcPath.'base.php');
	break;
	/**
	* cover_activity
	*/
	case 'COVER_ACTIVITY':
	$title = $result[0];
	$imageFile = $result[6];
	$facebook = $result[7];
	$twitter = $result[8];
	$web = $result[9];
	/**
	* @ignore
	*/
	include($srcPath.'header.php');
	
	if ( isset($result[1]) ) {
		$titleButton = $result[1];
		if (isset($result[2]))
			$imgButton = $result[2];
		$nextLevel = $result[3];
		$nextData = $result[4];
		echo'<div class="ui-grid-a align-center">
		';
		$block = 'b';
		for ($x = 0; $x < count($titleButton); $x++) {

		if ($block == 'b')
			$block = 'a';
		else
			$block = 'b';
			 
		echo'<div class="ui-block-'.$block.' align-center">
		';
			if ( $nextLevel[$x] != '' && $nextData[$x] != ''){
				$tr = array_search(trim($nextLevel[$x]),$levelId);
				if ($tr !== FALSE)
					$levelTrans = replace($levelTransition[$tr],trim($levelType[$tr]));
					
				$url = $_SERVER['PHP_SELF'].'?level='.$nextLevel[$x].'&data='.$nextData[$x];
			} else {
				$url = '#';
				$levelTrans = '';
			}
			if ( $imgButton[$x] != '' )
				echo'<a '.$levelTrans.' href="'.$url.'"><img src="'.$assetsPath.$imgButton[$x].'" title="'.$titleButton[$x].'" /></a>';
			else
				echo'<a '.$levelTrans.' data-role="button" href="'.$url.'"> '.$titleButton[$x].'</a><br />';
		echo'</div>
		';
		}
		echo'</div>';
		
		echo "<center>";
		if ($facebook != '') 
			echo "<a target='_blank' href=".$facebook.">Facebook</a>";
		if ($facebook != '' && twitter != '')
			echo " | ";
		if ($twitter != '')
			echo "<a target='_blank' href=".$twitter.">Twitter</a></center>";
		echo "</center>";
		if ($web != '')
			echo "<br/><center><a target='_blank' href=".$web.">Desktop website</a></center>";			
	}
	/**
	* @ignore
	*/
	include($srcPath.'base.php');
	
	break;
	
	/**
	* Error when activity is other.
	*/
	default:
	/**
	* @ignore
	*/
	include($srcPath.'header.php');
	echo'ERROR';
	/**
	* @ignore
	*/
	include($srcPath.'base.php');
}

?>
