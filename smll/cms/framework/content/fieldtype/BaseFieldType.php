<?php
namespace smll\cms\framework\content\fieldtype;

use smll\framework\utils\HashMap;

use smll\cms\framework\ui\fields\interfaces\IFieldRenderer;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

abstract class BaseFieldType implements IFieldType {
	protected $name;
	protected $dataType = "string";
	protected $multifield = false;
	protected $renderer = null;
	protected $error = "";
	
	/**
	 *
	 * @var IFieldSettings
	 */
	private $settings = null;
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function validateField($data, $parameters = null) {
		return true;
	}
	
	public function getPropertyDataType() {
		return $this->dataType;
	}
	public function setPropertyDataType($datatype) {}
	
	public function renderFieldJson($data) {}
	
	public function onDataStore($data, $parameters = null) {}
	public function setData($data) {
		$this->data = $data;
	}
	
	public function processData($data, $index = 0) {
		return $data;
	}
	
	public function getErrorMessage() {
		return $this->error;
	}
	
	public function setRenderer(IFieldRenderer $renderer = null) {
		$this->renderer = $renderer;
	}
	
	public function setFieldSettings(HashMap $settings) {
		$this->settings = $settings;
	}
	
	public function getFieldSettings() {
		return $this->settings;
	}
	
	public function isMultifield($boolean = null) {
		if(is_bool($boolean)) {
			$this->multifield = $boolean;
		}
		
		return $this->multifield;
	}
} 