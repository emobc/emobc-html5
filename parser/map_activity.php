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
* Map XML Parser
*
* @param string $xml Archivo xml
* @param string $data dataId to read
* @return $total
*/

function traer_info($xml, $data){
	if(!strrpos("__".$xml, "http")){
		$sx = simplexml_load_file($xml);
	} else {
		$content = file_get_contents($xml);
		$sx = simplexml_load_string($content);
	}
    foreach($sx->data as $item)
    {
    	if ($item->dataId == $data) {
          foreach($item->positions->position as $item2){
              $positionTitle[] = $item2->positionTitle;
              $positionAddress[] = $item2->positionAdress;
              $positionLat[] = $item2->positionLat;
              $positionLon[] = $item2->positionLon;
	      $icon[] = $item2->iconFile;
	      $nextLevelDataId[] = $item2->nextLevel->nextLevelDataId;
	      $nextLevelId[] = $item2->nextLevel->nextLevelLevelId;
          }
	}
    }
if (isset($item->positions->position)) {
    $total[0] = $positionTitle;
    $total[1] = $positionAddress;
    $total[2] = $positionLat;
    $total[3] = $positionLon;
    $total[4] = $icon;
    $total[5] = $nextLevelDataId;
    $total[6] = $nextLevelId;
    return $total;
}
}
?>
