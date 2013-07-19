<?php
namespace smll\cms\framework\content\fieldtype; 

abstract class FieldType {
	const DATATYPE_GUID = 'linkGuid';
	const DATATYPE_LONG_STRING = 'longString';
	const DATATYPE_NUMBER = 'number';
	const DATATYPE_PAGE_REF = 'pageRef';
	const DATATYPE_DATE	= 'date';
	const DATATYPE_STRING = 'string';
	const DATATYPE_BOOLEAN = 'boolean';
}