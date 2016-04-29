<?php
namespace yogi\framework\utils;
class Url {
	private $url = null;
	public function __construct($url) {
		$this->url = $url;
	}
	
	public static function navigationUrl($url) {
		global $application;
		
		$applicationRoot = $application->getApplicationRoot();
		
		return new Url($applicationRoot."/".$url);
	}
	
	
	
	public function __toString() {
		return $this->url;
	}
}