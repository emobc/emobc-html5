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


			if ($adsPos == 'bottom') {
				echo "<div class='align-center' id='publi'>";
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
			  	
			  		if(!empty($images)){ // si tenemos algo que mostrar...
			  	       		$rand_key = array_rand($images, 1);
			  	        	$src = $images[$rand_key];
			  	        	echo "<img src='".$src."' class='valign-middle' >";
			  		}
				} else if ($adsType == 'yoc') {
				$adsUrl = 'ads_yoc.php?id='.$adsId.'';
				} else if ($adsType == 'admob') {
				$adsUrl = 'ads_admob.php?id='.$adsId.'';
				}
				echo'
				<iframe src="'.$adsUrl.'" id="iframe_ads" scrolling="no" style="width: 322px; overflow:hidden; border: 0px; margin-left:-24px; margin-right:0px; height:60px;" class="center-ads align-center"></iframe>';
				echo'</div>';		
			}
		if ( isset($_GET['level']) && isset($_GET['data']) ) {
			if ( (isset($levelPoint) && $_GET['level'] != $levelPoint) && (isset($dataPoint) && $_GET['data'] != $dataPoint) ) {
				if (file_exists($xmlPath.'bottom_menu.xml')) {
				echo'<div data-role="footer" data-position="fixed" id="footer"> ';
					/**
					* Menu Parser
					*/
					$bot_menu = menu($xmlPath.'bottom_menu.xml');
					$titleMenu = $bot_menu[0];
					$imageMenu = $bot_menu[1];
					$actionMenu = $bot_menu[2];
					$marginMenu = $bot_menu[3];
					$widthMenu = $bot_menu[4];
					$heightMenu = $bot_menu[5];
					$nextLevelMenu = $bot_menu[6];
					$nextDataMenu = $bot_menu[7];
				   	echo '
					<div data-role="navbar">
						<ul>';
					for ($x = 0; $x < count($titleMenu); $x++) {
						if (isset($actionMenu[$x]) && $actionMenu[$x] != "") {
							if ($actionMenu[$x] == 'back') {
								$linkMenu = '<a href="#" data-rel="back">';
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
					</div></div>';

				}
			}
		}
echo"
</div><!-- PAGINA -->
</body>
</html>";

?>
