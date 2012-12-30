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
* Cover XML Parser
* @param string $xml Xml file
* @return $total
*/ 

function portada($xml) {
	if(!strrpos("__".$xml, "http")){
		$sx = simplexml_load_file($xml);
	} else {
		$content = file_get_contents($xml);
		$sx = simplexml_load_string($content);
	}
	foreach($sx->buttons as $item)
	{
		foreach($item->button as $item2){
			$buttonTitle[] = $item2->buttonTitle;
			$buttonFileName[] = $item2->buttonFileName;
                        foreach($item2->nextLevel as $item3){
				$nextLevelLevelId[] = $item3->nextLevelLevelId;
				$nextLevelDataId[] = $item3->nextLevelDataId;
			}
		}
	}
if (isset($item->button)) {
	$total[1] = $buttonTitle;
	$total[2] = $buttonFileName;
	$total[3] = $nextLevelLevelId;
	$total[4] = $nextLevelDataId;
}
	$total[0] = $sx->title;
	$total[5] = $sx->backgroundFileName;
	$total[6] = $sx->titleFileName;
	$total[7] = $sx->facebook;
	$total[8] = $sx->twitter;
	$total[9] = $sx->www;
	
	return $total;

}

?>