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
* Quiz XML Parser
*
* @param string $xml Xml file
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
    $i = 0;
    foreach($sx->data as $item) {
    	if ($item->dataId == $data) {
	    $headerText = $item->headerText;
	    $description = $item->description;
	    $first = $item->first;
	    
	    foreach($item->questions->question as $item2) {
	    	$id[$i] = $item2->id;
		$imageFile[$i] = $item2->imageFile;
		$text[$i] = $item2->text;
		$point[$i] = $item2->weight;
		
		$x = 0;
		foreach($item2->answers->answer as $item3) {
				$answerText[$i][$x] = $item3->answerText;
				$correct[$i][$x] = $item3->correct;
				$next[$i][$x] = $item3->next;
				$x++;
		}
		$i++;
	    }
	}
    }
if ( isset($item2) ) {
	$total[0] = $headerText;
	$total[1] = $description;
	$total[2] = $first;
	$total[3] = $id;
	$total[4] = $imageFile;
	$total[5] = $text;
	$total[6] = $point;
	$total[7] = $answerText;
	$total[8] = $correct;
	$total[9] = $next;
}
return $total;
} 
