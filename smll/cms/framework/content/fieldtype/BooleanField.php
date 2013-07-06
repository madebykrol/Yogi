<?php
namespace smll\cms\framework\content\fieldtype;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

class BooleanField implements IFieldType {
	
	private $name;
	private $dataType = "boolean";
	private $multifield = false;
	
	public function setName($name) {
		$this->name = $name;
	}
	public function renderField($data, $parameters = null) {
		
		$checked = "";
		if($data == 1) {
			 $checked = 'Checked="checked"';
		}
		return '<input name="'.$this->name.'" '.$checked.' type="checkbox" value="1"/>';
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
	
	public function setFieldSettings(IFieldSettings $settings) {}
	
	public function setData($data) {}
	
	public function processData($data) {
		return $data;
	}
}