<?php
namespace yogi\framework\helpers;
class Styles {
	
	public static function bundle($pund) {
		
	}
	
	public static function style($style) {
		global $application;
		
		$applicationRoot = $application->getApplicationRoot();
		$style = str_replace("~", $applicationRoot, $style);
		return '<link href="'.$style.'" rel="stylesheet">';
	}
}