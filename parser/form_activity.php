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
* Form XML Parser
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
	foreach($sx->data as $item)
	{
		if ($data == $item->dataId) {
			foreach($item->form as $item2)
			{
				$actionUrl = $item2->actionUrl;
				foreach($item2->field as $item3)
				{
					$fieldType[] = $item3->fieldType;
					$fieldLabel[] = $item3->fieldLabel;
					$fieldName[] = $item3->fieldName;
					$fieldParam[] = $item3->fieldParam;					
				}
			}
			$nextLevelLevelId = $item->nextLevel->nextLevelLevelId;
			$nextLevelDataId = $item->nextLevel->nextLevelDataId;

		        $total[0] = $nextLevelLevelId;
		        $total[1] = $nextLevelDataId;
			$total[2] = $actionUrl;
			$total[3] = $fieldType;
			$total[4] = $fieldLabel;
			$total[5] = $fieldName;
			$total[6] = $fieldParam;
		}
	}		
	return $total;
}
?>
