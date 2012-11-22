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
* Style format XML Parser
* @param string $xml Xml file
* @return $total
*/

function format($xml){
	if(!strrpos("__".$xml, "http")){
		$sx = simplexml_load_file($xml);
	} else {
		$content = file_get_contents($xml);
		$sx = simplexml_load_string($content);
	}
	foreach($sx->formats->format as $item) {
		$name[] = $item->name;
		$color[] = $item->textColor;
		$size[] = $item->textSize;
		$style[] = $item->textStyle;
		$face[] = $item->typeFace;
		$listBg[] = $item->backgroundSelectionFileName;
		
	}
$total[0] = $name;
$total[1] = $color;
$total[2] = $size;
$total[3] = $style;
$total[4] = $face;
$total[5] = $listBg;

return $total;
}
?>