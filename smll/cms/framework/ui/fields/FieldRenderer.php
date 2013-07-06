<?php
namespace smll\cms\framework\ui\fields;


use smll\cms\framework\ui\fields\interfaces\IFieldRenderer;

abstract class FieldRenderer implements IFieldRenderer {
	
	private $data;
	private $error;
	private $name;
	
	public function setValidationError($error) {
		$this->error = $error;
	}
	
	public function setData($data) {
		$this->data = $data;
	}
	public function setFieldName($name) {
		$this->name = $name;
	}

	public function getData() {
		return $this->data;
	}
	public function getFieldName() {
		return $this->name;
	}
}
