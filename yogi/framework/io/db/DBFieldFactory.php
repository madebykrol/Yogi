<?php
namespace yogi\framework\io\db;

use yogi\framework\io\db\interfaces\IDBFieldFactory;

class DBFieldFactory implements IDBFieldFactory {

	public function mergeFields(DBField $field1, DBField $field2) {
		
		return new DBField();
	}
	
	public function fromObjProperty(\ReflectionProperty $property) {
	
	
		return new DBField();
	}
	
	public function fromDbColumn(\stdClass $dbColumn) {
	
		$primary 		= false;
		$null 			= false;
		$autoIncrement 	= false;
		$unique 		= false;
		$name 		= "";
		$type 		= "";
		$lenght		= "";
	
		if(isset($dbColumn->Key)) {
			$primary = $dbColumn->Key == "PRI";
			$unique = true;
		}
		if(isset($dbColumn->Null)) {
			$null = $dbColumn->Null == "NO";
		}
		if(isset($dbColumn->Extra)) {
			$autoIncrement = $dbColumn->Extra == "auto_increment";
		}
		if(isset($dbColumn->Field)) {
			$name = $dbColumn->Field;
		}
		if(isset($dbColumn->Type)) {
				
		}
	
		return new DBField($name, $type, $default, $null, $length, $unique, $primary, $autoIncrement);
	}
	
}