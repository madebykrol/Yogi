<?php
namespace smll\cms\framework\content\fieldtype;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

class XmlStringField implements IFieldType {
	
	private $name;
	private $dataType = "longString";
	private $multifield = false;
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function renderField($data, $parameters = null) {
		return '<textarea name="'.$this->name.'" class="xml-field" id="xml-field-'.strtolower($this->name).'"/>'.$data.'</textarea>';
	}
	
	public function validateField($data, $parameters = null) {
		return true;
	}
	
	public function getErrorMessage() {}
	
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
	
	public function setFieldSettings(IFieldSettings $settings) {}
}