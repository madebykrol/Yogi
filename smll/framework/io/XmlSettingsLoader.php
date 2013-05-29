<?php
class XmlSettingsLoader implements ISettingsLoader {
	
	public function __construct($file) {
		$this->file = $file;
	}
	
	public function getSettings() {
		
		return new ArrayList(array("application-root" => '/Smll'));
	}
}