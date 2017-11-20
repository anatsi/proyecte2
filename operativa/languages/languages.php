<?php

	function __($str, $lang = null){

		if ( $lang != null ){

			if ( file_exists('./languages/language_'.$lang.'.php') ){

				include('./languages/language_'.$lang.'.php');
				if ( isset($texts[$str]) ){
					$str = $texts[$str];
				}
			}
		}

		return $str;
	}

?>
