<?

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
* Web XML Parser
*
* @param string $xml XML archive
* @param string $data dataId to read
* @return $total
*/

function traer_info($xml,$data){
	if(!strrpos("__".$xml, "http")){
		$sx = simplexml_load_file($xml);
	} else {
		$content = file_get_contents($xml);
		$sx = simplexml_load_string($content);
	}
	foreach($sx->data as $item)
	{
            if($item->dataId == $data){
                $headerText = $item->headerText;
                $webUrl = $item->webUrl;
            }
	}
	$total[0] = $headerText;
	$total[1] = $webUrl;		
	
	return $total;
}
?>
