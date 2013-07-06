<?php
namespace smll\cms\framework\content\fieldtype;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

class HiddenField implements IFieldType {
	
	private $name;
	private $dataType = "string";
	private $multifield = false;
	
	public function setName($name) {
		$this->name = $name;
	}
	public function renderField($data, $parameters = null) {
		return '<input name="'.$this->name.'" type="hidden" value="'.$data.'"/>';
	}
	public function validateField($data, $parameters = null) {
		return true;
	}
	
	public function getPropertyDataType() {
		return $this->dataType;
	}
	public function setPropertyDataType($datatype) {
		$this->dataType = $dataType;
	}

	public function renderFieldJson($data) {}
	
	public function setData($data) {}
	
	public function processData($data) {
		return $data;
	}
	
	public function getErrorMessage() {}
	
	public function setFieldSettings(IFieldSettings $settings) {}
}