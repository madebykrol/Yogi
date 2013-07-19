<?php
namespace smll\cms\framework\content\interfaces;

interface IPageProperty {
	public function setValue($value);
	public function setPageDefinitionId($id);
	public function setIndex($index);
	
	public function getValue();
	public function getPageDefinitionId();
	public function getIndex();
	
	public function ignoreIfNull($boolean = null);
	
	public function setDataType($datatype);
	public function getDataType();
}