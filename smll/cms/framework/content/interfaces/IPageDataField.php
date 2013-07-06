<?php
namespace smll\cms\framework\content\interfaces;
use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

/**
 * A datafield represents a single field of data that's 
 * associated with a IPageData object.
 * 
 * @author Kristoffer "mbk" Olsson
 *
 */
interface IPageDataField {
	
	public function setFieldName($name);
	public function getFieldName();
	
	public function setFieldType(IFieldType $fieldType);
	public function getFieldType();
	
	public function setDefinitionTypeId($id);
	public function getDefinitionTypeId();
	
	public function setDefinitionId($id);
	public function getDefinitionId();
	
	public function setDisplayName($name);
	public function getDisplayName();

	
	public function setLongStringSettings($length);
	public function getLongStringSettings();
	
	public function setWeightOrder($order);
	public function getWeightOrder();
	
	public function isRequired($boolean = null);
	
	public function getDataType();
	public function setDataType($type);
	
	public function isSearchable($boolean = null);
	
	public function setTab($string);
	public function getTab();
}