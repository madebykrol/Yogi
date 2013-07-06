<?php
namespace smll\cms\framework\content\fieldtype;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

class TextField implements IFieldType {
	
	private $name;
	private $dataType = "string";
	private $enabled;
	
	public function setName($name) {
		$this->name = $name;
	}
	public function renderField($data, $parameters = null) {
		return '<input name="'.$this->name.'" type="text" value="'.$data.'" id="input-'.$this->name.'"/>';
	}
	public function validateField($data, $parameters = null) {
		return true;
	}
	
	public function getPropertyDataType() {
		return $this->dataType;
	}
	public function setPropertyDataType($datatype) {}

	public function renderFieldJson($data) {}
	
	public function setData($data) {}
	
	public function processData($data) {
		return $data;
	}
	
	public function setFieldSettings(IFieldSettings $settings) {}
	
	public function getErrorMessage() {}
	
}