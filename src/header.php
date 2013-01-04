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



if (!isset($_SESSION["start"]) || $_SESSION["start"] != 1) {
	@session_start();
	$_SESSION["start"] = 1;
}

if (isset($background) && $background != "") {
$bg = 'background:url('.$assetsPath.$background.') no-repeat center center fixed; background-size:cover;';
} else {
$bg = "background: none;";
}

include($classPath."seo.php");

if (file_exists($xmlPath.'seo.xml')) {
	$seodata = seo($xmlPath."seo.xml");
	$description = $seodata[0];
	$keywords = $seodata[1];
	$author = $seodata[2];
} else {
	$description = "Web Mobile: eMobc com";
	$keywords = "web, mobile, emobc";
	$author = "eMobc";
}

echo '<!DOCTYPE html>
	<head>';
	if ( isset($title) )
	echo'<title>'.$title.'</title>';
	echo'
	<base href="localhost" />
	<meta http-equiv="Pragma" content="no-cache">
	<META HTTP-EQUIV="Expires" CONTENT="-1">
	<link rel="apple-touch-icon" href="apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="apple-touch-icon-114x114.png" />
	<link rel="apple-touch-startup-image" href="images/splash.png" />
	<link rel="apple-touch-icon" href="images/icon.png" />
	<link rel="shortcut icon" href="images/icons/favicon.ico" type="image/x-icon" />
	<meta http-equiv=\'Content-Type\' content=\'text/html; charset=UTF-8\' />
	
	<!-- SEO -->
	<meta name="description" content="'.$description.'">
	<meta name="keywords" content="'.$keywords.'">
	<meta name="author" content="'.$author.'">
    
	<!-- JQUERY MOBILE -->
	<link rel="stylesheet" href="css/jquery-ui-1.8.22.custom.css">
	<link rel="stylesheet" href="css/jquery.mobile-1.1.1.css" />

	<meta name=\'viewport\' content=\'width=device-width, initial-scale=1\'/>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="white" />
	<script src="lib/js/jquery-1.7.2.min.js"></script>
	<script src="lib/js/jquery.mobile.js"></script>';
	
	if ($activity == "MAP_ACTIVITY")
		echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>';
	
	if (isset($dateEvent) && isset($titleEvent)) {
	echo '
	<!-- JQUERY DATEPICKER -->
	<script src="lib/js/jquery-ui-1.8.22.custom.min.js"></script>';
?>
	<script>
<?php
	echo'var dates = {';
	for ($e = 0; $e < count($dateEvent); $e++){
		if ($e == 0)
			echo"'".$dateEvent[$e]."':'".$titleEvent[$e]."'";
		else
			echo", '".$dateEvent[$e]."':'".$titleEvent[$e]."'";
	}
	echo'};
	';
	
	echo 'var info = [
	';
	for ($e = 0; $e < count($dateEvent); $e++) {
		if ($e == 0)
			echo '{ Title: "'.$titleEvent[$e].'", Date: "'.$dateEvent[$e].'", Time: "'.$timeEvent[$e].'", NextLevel: "'.$nextLevel[$e].'", NextData: "'.$nextData[$e].'", Text: "'.$textEvent[$e].'" }';
		else
			echo ',
			{ Title: "'.$titleEvent[$e].'", Date: "'.$dateEvent[$e].'", Time: "'.$timeEvent[$e].'", NextLevel: "'.$nextLevel[$e].'", NextData: "'.$nextData[$e].'", Text: "'.$textEvent[$e].'" }';	
	}
	echo '
	]';
?>	
$('#idCalendar').live('pageshow',function(){
	$('#datepicker').datepicker({
		   firstDay: 1,            
		   dateFormat: 'dd/mm/yy', 
		   dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                   monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo',
                                'Junio', 'Julio', 'Agosto', 'Septiembre',
                                'Octubre', 'Noviembre', 'Diciembre'],
                   monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr',
                                     'May', 'Jun', 'Jul', 'Ago',
                                      'Sep', 'Oct', 'Nov', 'Dic'],

		   beforeShowDay: function(date) {
	               var newMonth = date.getMonth()+1;	
		       var search = date.getDate() + "/" + (newMonth < 9 ? "0"+newMonth:newMonth) + "/" + (date.getFullYear());
		
		       if (dates[search]) {
		           return [true, 'ui-state-highlight', dates[search] || ''];
		       }
		
		       return [true, '', ''];
		   },
		           onSelect: function(date) {
			   $('#eventos').html('')
			   var i = 0;			   
			   	
			     while (i < info.length) {
			   	if (info[i].Date.valueOf() == date.valueOf()) {
					if (i == 1) {
				   	$('#eventos').hide().append('<li data-corners="true" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-corner-top ui-btn-up-c"><div class="ui-btn-inner ui-li ui-corner-top"><div class="ui-btn-text">' +
				   					'<a data-transition="slide" href="index.php?level='+ info[i].NextLevel +'&data='+ info[i].NextData +'" class="ui-link-inherit">' +
				   					'<h3>' + info[i].Time + ' ' + info[i].Title + '</h3>'+ info[i].Text +'</a>' +
				   					'</div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div></li>').slideDown('fast');
					}
					else {
				   	$('#eventos').hide().append('<li data-corners="true" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-c"><div class="ui-btn-inner ui-li ui-corner-top"><div class="ui-btn-text">' +
				   					'<a data-transition="slide" href="index.php?level='+ info[i].NextLevel +'&data='+ info[i].NextData +'" class="ui-link-inherit">' +
				   					'<h3>' + info[i].Time + ' ' + info[i].Title + '</h3>'+ info[i].Text +'</a>' +
				   					'</div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div></li>').slideDown('fast');
					}
				}
			            i++;
			     }
		           }	
	});
});
</script>
<?php
}

echo "
	<script type=\"text/javascript\" src=\"lib/js/klass.min.js\"></script>
	<link href=\"css/photoswipe.css\" type=\"text/css\" rel=\"stylesheet\" />
	<!-- SCRIPT GALERIA -->
	<script type='text/javascript' src='lib/js/code.photoswipe-3.0.4.min.js'></script>";
	?>
	<script type="text/javascript">
		(function(window, $, PhotoSwipe){
			$('#idGallery').live('pageshow',function(){
				var options = {};
				$("ul.gallery a").photoSwipe(options);
			});	
	}(window, window.jQuery, window.Code.PhotoSwipe));
	</script>	
	<!-- Gallery Script -->
<?php
echo"
	<script> 
		function Oculta() {
			document.getElementById('precarga').style.display = 'none';
		}
	</script>
	<style type='text/css'>
		#precarga{position:absolute; width:100%; height:100%; top:0; left:0; background-color: white; z-index: 16777271;}
		img{border:none;}
		.align-center{text-align:center;}
		.valign-middle{ vertical-align: middle;}
		.align-right{text-align: right;}
		.align-left{text-align:left;}
		.center-ads{margin: 0 auto;}
	</style>
</head>


	<!-- SPLASH DIV -->";
	if ($_SESSION["start"] != 1) {
	echo"<div id='precarga' class='align-center'><img src='images/splash.png' width='100%' height='100%' alt='splash'></div>";
	}
	if (isset($pageId) ) {
	$id_page = 'id="'.$pageId.'"';
	} else {
	$id_page = '';
	}
	echo"<!-- END SPLASH DIV -->
	<div data-role='page' style='".$bg."' ".$id_page."><!-- PAGINA -->
	";
	if ( isset($custom_css) )
		echo $custom_css;
		
		$sx = simplexml_load_file($xmlPath."portada.xml");
	
if(isset($_GET['level']))
	echo"
		<div data-role='header' data-position='fixed' class='align-center' ><!-- HEADER -->";
else {
	if (empty($sx->titleFileName))
		echo "<div data-role='header' data-position='fixed' class='align-center' ><!-- HEADER -->";
	else
		echo "<div data-role='' data-position='fixed' class='align-center' ><!-- HEADER -->";
}

	if ( isset($_GET['level']) && isset($_GET['data']) ) {
			if ( (isset($levelPoint) && $_GET['level'] != $levelPoint) && (isset($dataPoint) && $_GET['data'] != $dataPoint) ) {
				if (file_exists($xmlPath.'top_menu.xml')) {
					/**
					* Menu parser
					*/
					include($classPath.'menu_class.php');
					$top_menu = menu($xmlPath.'top_menu.xml');
					$titleMenu = $top_menu[0];
					$imageMenu = $top_menu[1];
					$actionMenu = $top_menu[2];
					$marginMenu = $top_menu[3];
					$widthMenu = $top_menu[4];
					$heightMenu = $top_menu[5];
					$nextLevelMenu = $top_menu[6];
					$nextDataMenu = $top_menu[7];
					/**
					* Search for sideMenu
					*/
					if (in_array('sideMenu',$actionMenu)) {
						$posMenu = array_search('sideMenu',$actionMenu);
					} else {
						$posMenu = 0;
					}
					/**
					* sideMenu navbar
					*/
					if ( $_GET['level'] == $nextLevelMenu[$posMenu]) {
						echo'<a href="#" data-rel="back" data-transition="reverse" data-icon="grid" data-iconpos="notext">Volver</a><h1></h1>';
					} else {
					echo'<div data-role="navbar"><ul>';
					for ($x = 0; $x < count($titleMenu); $x++) {
						if (isset($actionMenu[$x]) && $actionMenu[$x] != '') {
							if ($actionMenu[$x] == 'back') {
								$linkMenu = '<a href="#" data-rel="back" data-transition="reverse">';
							}
							else if ($actionMenu[$x] == 'home') {
								$linkMenu = '<a data-transition="slide" href="index.php">';
							}
						}
						if ( (isset($nextLevelMenu[$x]) && $nextDataMenu[$x]) && ($nextLevelMenu[$x] != '' && $nextDataMenu != '')) {
							$linkMenu = '<a data-transition="slide" href="'.$_SERVER['PHP_SELF'].'?level='.$nextLevelMenu[$x].'&data='.$nextDataMenu[$x].'">';
						}
						echo'<li>';
						if ( isset($imageMenu[$x]) && $imageMenu[$x] != '') {
							$size = GetImageSize($assetsPath.$imageMenu[$x]);
							echo $linkMenu.'<img src="'.$assetsPath.$imageMenu[$x].'" alt="'.$titleMenu[$x].'" style="width: '.$widthMenu[$x].'px; height: '.$heightMenu[$x].'px; margin-left: '.$marginMenu[$x].'px">';
						} else {
							echo $linkMenu.$titleMenu[$x];
						}
						echo'</a></li>';
					}
						echo'</ul>
					</div>';
					}

				} else {
			   	echo '
				<div data-role="navbar">
					<ul>
						<li><a href="#" data-rel="back" data-role="button" data-icon="arrow-l">Regresar</a></li>
						<li><a href="index.php" rel="external" data-role="button" data-icon="home" data-iconpos="right">Home</a></li>
					</ul>
				</div>';
				}
		         }
	       } else {
			if ($activity == 'COVER_ACTIVITY') {
				if (!isset($title) || $title != "")
					if ($imageFile == "") 
					echo"<h1>".$title."</h1>";
			} else {
				if (isset($_COOKIE['profile'])) {
	 				  if (isset($_GET['s'])) {
						   echo'<div data-role="navbar">
						   <ul>
						       <li><a href="index.php" data-role="button">Home</a></li>
						   </ul>
						   </div>';
					} else {
						echo'<div data-role="navbar">
							<ul>
								<li><a href="index.php" data-role="button">Home</a></li>
								<li><a href="profile.php?editmode" data-role="button" data-iconpos="right">Editar</a></li>
							</ul>
						</div>';
					}
				}
				
			} 
	       }
		echo"</div>
		<div data-role='content'><!-- CONTENT -->";
		if ( isset($imageFile) && $imageFile != '' ) {
					$size = GetImageSize($assetsPath.$imageFile);
				   	echo"<div style=\"text-align: center\"><img src='".$assetsPath.$imageFile."' alt='".$title."' height='".$size[1]."'/></div>";
				}
			if ($adsPos == "top") {
				echo "<div class='align-center' id='ads_banner' >";
				if ($adsType == 'custom') {
					$extensions = array('jpg','jpeg','gif','png','bmp');
			  		$folder_image_name = "images/ads/propia/";
			  		$images_folder_path = $folder_image_name;
			  		$url_to_folder = $folder_image_name;
			  		$images = array();
			  		if ($handle = opendir($images_folder_path)) {
			  	    		while (false !== ($file = readdir($handle))) {
			  	        		if ($file != "." && $file != "..") {
			  	          			$ext = strtolower(substr(strrchr($file, "."), 1));
			  	          			if(in_array($ext, $extensions)){
			  	            				$images[] = $url_to_folder.$file;
			  	          			}
			  	        		}
			  	    		}
			  	    		closedir($handle);
			  		}
			  	
			  		if(!empty($images)){
			  	       		$rand_key = array_rand($images, 1);
			  	        	$src = $images[$rand_key];
			  	        	echo "<img src='".$src."' class='valign-middle' alt='Publicidad' />";
			  		}
					echo'</div>';
				} else if ($adsType == 'yoc') {
				$adsUrl = 'ads_yoc.php?id='.$adsId.'';
				} else if ($adsType == 'admob') {
				$adsUrl = 'ads_admob.php?id='.$adsId.'';
				}
				echo'
				<iframe src="'.$adsUrl.'" id="iframe_ads" scrolling="no" style="width: 322px; overflow:hidden; border: 0px; margin-left:-24px; margin-right:0px; height:60px;" class="center-ads align-center"></iframe>';
				echo'</div>';
			}
		
?>
