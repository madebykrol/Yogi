<?php
namespace smll\cms\framework\content;

use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

use smll\cms\framework\content\interfaces\IPageDataField;

class PageDataField implements IPageDataField {
	
	private $definitionTypeId = null;
	private $definitionId = null;
	private $displayName = null;
	private $longStringSettings = null;
	private $weightOrder = null;
	private $required = false;
	private $fieldType = null;
	private $searchable = false;
	private $fieldName = null;
	private $tab = null;
	
	public function setFieldName($name) {
		 $this->fieldName = $name;
	}
	
	public function getFieldName() {
		return $this->fieldName;
	}
	
	public function setTab($string) {
		$this->tab = $string;
	}
	
	public function getTab() {
		return $this->tab;
	}
	
	public function setFieldType(IFieldType $fieldType) {
		$this->fieldType = $fieldType;
	}
	public function getFieldType() {
		return $this->fieldType;
	}
	
	public function setDefinitionTypeId($id) {
		$this->definitionTypeId = $id;
	}
	public function getDefinitionTypeId() {
		return $this->definitionTypeId;
	}
	
	public function setDefinitionId($id) {
		$this->definitionId = $id;
	}
	public function getDefinitionId() {
		return $this->definitionId;
	}
	
	public function setDisplayName($name) {
		$this->displayName = $name;
	}
	public function getDisplayName() {
		return $this->displayName;
	}
	
	public function setLongStringSettings($length) {
		$this->longStringSettings = $length;
	}
	public function getLongStringSettings() {
		return $this->longStringSettings;
	}
	
	public function setWeightOrder($order) {
		$this->weightOrder = $order;
	}
	public function getWeightOrder() {
		return $this->weightOrder;
	}
	
	public function isRequired($boolean = null) {
		if(isset($boolean) && is_bool($boolean)) {
			$this->required = $boolean;
		}
		
		return $this->required;
	}
	
	public function getDataType() {
		return $this->dataType;
	}
	public function getInputType() {
		return $this->inputType;
	}
	
	
	public function setDataType($type) {
		$this->dataType = $type;
	}
	public function setInputType($type) {
		$this->inputType = $type;
	}
	
	
	public function isSearchable($boolean = null) {
		if(isset($boolean) && is_bool($boolean)) {
			$this->searchable = $boolean;
		}
		
		return $this->searchable;
	}
}
