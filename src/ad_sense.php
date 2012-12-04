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
 * Generates the adSense Snippet.
 * @param unknown $adSenseClient
 * @param unknown $adSenseSlot
 * @return string
 */
function generate_adSense_snippet($adSenseClient,$adSenseSlot) {
	$out = "
	<!-- Begin Google Adsense code  -->
	<script type='text/javascript'>
		google_ad_client = \"" . $adSenseClient ."\";
		google_ad_slot = \"" . $adSenseSlot ."\";
		google_ad_width = 300;
		google_ad_height = 250;
	</script>
	<script type='text/javascript' 
		src='http://pagead2.googlesyndication.com/pagead/show_ads.js'>
	</script>
	<!-- End Google Adsense code -->";
	return $out; 
}

?>


