<?php
namespace yogi\framework\io\db\interfaces;

use yogi\framework\io\db\DBField;

interface IDBFieldFactory {
	
	/**
	 * 
	 * Merge two DBFields 
	 * 
	 * @return DBField
	 * @param DBField $field1
	 * @param DBField $field2
	 */
	public function mergeFields(DBField $field1, DBField $field2);
	
	/**
	 * 
	 * @param \ReflectionProperty $property
	 */
	public function fromObjProperty(\ReflectionProperty $property);
	public function fromDbColumn(\stdClass $column);
}