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
 * App XML Parser
 *
 * @param string $xml XML file
 * @return $total
 *
 */

function app($xml){
	$app = simplexml_load_file($xml);
	foreach($app->levels->level as $item)
	{
			$levelId[] = $item->levelId;
			$levelTitle[] = $item->levelTitle;
			$levelFile[] = $item->levelFile;
			$levelType[] = $item->levelType;
			$levelTransition[] = $item->levelTransition;
			$levelFormatBg[] = $item->levelFormat->backgroundFileName;
			$levelFormatComp[] = $item->levelFormat->components;
	}
	$total[0] = $levelId;
	$total[1] = $levelTitle;
	$total[2] = $levelFile;
	$total[3] = $levelType;
	$total[4] = $app->entryPoint->pointLevelId;
	$total[5] = $app->entryPoint->pointDataId;
	$total[6] = $app->menu->topMenu;
	$total[7] = $app->menu->bottomMenu;
	$total[8] = $app->banner->type;
	$total[9] = $app->banner->position;
	$total[10] = $app->banner->position;
	$total[11] = $app->banner->idSense;
	$total[12] = $levelTransition;
	$total[13] = $levelFormatBg;
	$total[14] = $levelFormatComp;
	$total[15] =  $app->profileFileName;
	
	return $total;
}
?>
