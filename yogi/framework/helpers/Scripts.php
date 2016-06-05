<?php
namespace yogi\framework\helpers;
class Scripts {
	
	public static function bundle($pund) {
		
	}
	
	public static function script($script) {
		global $application;
		
		$applicationRoot = $application->getApplicationRoot();
		$script = str_replace("~", $applicationRoot, $script);
		
		return '<script src="'.$script.'" defer></script>';
	}
}