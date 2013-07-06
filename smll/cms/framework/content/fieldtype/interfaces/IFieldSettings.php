<?php
namespace smll\cms\framework\content\fieldtype\interfaces;

interface IFieldSettings {
	/** 
	 * Get the maximum amount of inputs for this field
	 * Range from 1 - 10 and where 0 is unlimited 
	 */
	public function getMaxInputValues();
	
	/**
	 * Set the maximum of inputs for this field.
	 * Range from 1 - 10 and where 0 is unlimited
	 * @param int $max
	 */
	public function setMaxInputValues($max);
	
	public function isEnabled($boolean = null);
	
	public function isRequired($boolean = null);
	
	public function getLongStringSettings();
	public function setLongStringSettings($length);
	
}