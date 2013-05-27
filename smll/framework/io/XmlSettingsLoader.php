<?php
class XmlSettingsLoader implements ISettingsLoader {
	
	public function __construct($file) {
		$this->file = $file;
	}
	
	public function getSettings() {
		return array();
	}
}