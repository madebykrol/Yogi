<?php
namespace smll\cms\framework\content\fieldtype\interfaces;

interface IFieldType {
	public function setName($string);
	public function renderField($data, $parameters = null);
	public function validateField($data, $parameters = null);
	
	public function getPropertyDataType();
	public function setPropertyDataType($datatype);
	
	public function renderFieldJson($data);
	
	public function setData($data);
	
	public function setFieldSettings(IFieldSettings $settings);
	
	public function processData($data);
	
	public function getErrorMessage();
}