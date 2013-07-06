<?php
namespace smll\cms\framework\content\fieldtype;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

class FieldSettings implements IFieldSettings {

	private $enabled;
	private $required;
	private $maxInputValues = 1;
	private $longStringSetting = 1024;
	
	public function getMaxInputValues() {
		return $this->maxInputValues;
	}
	public function setMaxInputValues($max) {
		$this->maxInputValues = $max;
	}
	
	public function isEnabled($boolean = null) {}
	public function isRequired($boolean = null) {}
	
	public function getLongStringSettings() {}
	public function setLongStringSettings($length) {}
}