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

$sx = simplexml_load_file($xmlPath.'profile.xml');
foreach ($sx->field as $field) {
$fieldType[] = $field->fieldType;
$fieldName[] = $field->fieldName;
$fieldLabel[] = $field->fieldLabel;
$fieldParam[] = $field->fieldParam;
}
if (isset($_COOKIE['profile'])) {
	foreach ($_COOKIE as $name => $val) {
		$label[] = trim($name);
		$value[] = trim($val);
	}
}
		if (!isset($_GET['editmode'])) {
		echo'<script>
		$(document).ready(function(){
			$(":checkbox[readonly=readonly]").click(function(){
			return false;
			});
		});
		</script>
		';
		}
		echo'<div class="ui-grid-solo align-center">
		<div class="ui-block-a">
		<form method="POST" action="profile.php" data-ajax="false">
		';
		for ($x = 0; $x < count($fieldType); $x++) {
			echo'<input type="hidden" name="datos'.$x.'" value="'.$fieldName[$x].'">';
			if ( ($fieldType[$x] != 'INPUT_IMAGE') && ($fieldType[$x] != 'INPUT_CHECK') ) {
				echo'<label for="'.$fieldName[$x].'"><font class="basic_text">'.$fieldLabel[$x].'</font>';
			}
			
			$valueData = '';
			if (isset($_COOKIE['profile'])) {
				$ss = array_search(trim($fieldName[$x]),$label);
				if ($ss !== FALSE) {
					$valueData = $value[$ss];
				}
			}
			
			if (isset($_GET['editmode'])) {
				$edit = '';
				$disablelist = '';
				$disablecheck = '';
			} else {
				$edit = 'readonly="readonly"';
				$disablelist = 'disabled="disabled"';
			}
			
			switch ($fieldType[$x]) {
				case 'INPUT_PICKER':
				echo'<br /><select name="'.$fieldName[$x].'" '.$disablelist.'>';
				for ($i = 0; $i < count($fieldParam[$x]); $i++) {
					if ($valueData == trim($fieldParam[$x][$i]))
						echo'<option name="'.$fieldParam[$x][$i].'" selected="selected">'.$fieldParam[$x][$i].'</option>';
					else
						echo'<option name="'.$fieldParam[$x][$i].'">'.$fieldParam[$x][$i].'</option>
					';
				}
				echo'</select>';
				break;
				
				case 'INPUT_TEXT':
				echo'<input type="text" name="'.$fieldName[$x].'" '.$edit.' value="'.$valueData.'">';
				break;
				
				case 'INPUT_TEXTVIEW':
				echo'<textarea name="'.$fieldName[$x].'" '.$edit.'>'.$valueData.'</textarea>';
				break;
				
				case 'INPUT_NUMBER':
				echo'<input type="number" name="'.$fieldName[$x].'" '.$edit.'  value="'.$valueData.'">';
				break;
				
				case 'INPUT_EMAIL':
				echo'<input type="email" name="'.$fieldName[$x].'"  '.$edit.' value="'.$valueData.'">';
				break;
				
				case 'INPUT_PHONE':
				echo'<input type="tel" name="'.$fieldName[$x].'" '.$edit.' value="'.$valueData.'">';
				break;
				
				case 'INPUT_PASSWORD':
				echo'<input type="password" name="'.$fieldName[$x].'" '.$edit.'  value="'.$valueData.'">';
				break;
				
				case 'INPUT_CHECK':
				echo'<fieldset data-role="controlgroup">
				<legend>'.$fieldLabel[$x].'</legend>';
				for ($i = 0; $i < count($fieldParam[$x]); $i++) {
					if ($valueData == trim($fieldParam[$x][$i]))
						echo'<input type="radio" checked name="'.$fieldName[$x].'" '.$edit.' value="'.$fieldParam[$x][$i].'" id="'.$x.'x'.$i.'">
						<label for="'.$x.'x'.$i.'"><font class="basic_text">'.$fieldParam[$x][$i].'</font></label>';
					else
						echo'<input type="radio" name="'.$fieldName[$x].'" '.$edit.' value="'.$fieldParam[$x][$i].'" id="'.$x.'x'.$i.'">
						<label for="'.$x.'x'.$i.'"><font class="basic_text">'.$fieldParam[$x][$i].'</font></label>';
				}
				echo'</fieldset>';
				break;								
			}
		echo'</label>';
		}
		if (isset($_GET['editmode'])) {
		echo'<input type="hidden" name="total_campos" value="'.$x.'">
		<br /><input type="submit" name="send_profile" value="Enviar">';
		}
		echo'
		</form>
		</div>
		</div>';
?>