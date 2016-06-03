<?php
namespace yogi\framework\io\db;

class DBField {
	protected $name;
	protected $type;
	protected $length;
	protected $unique;
	protected $primary;
	protected $autoIncrement;
	protected $default;
	protected $null;
	
	public function __construct($name, $type, $default, $null = false, $length = null, $unique = false, $primary = false, $autoIncrement = false) {
		$this->name = $name;
		$this->type = $type;
		$this->default = $default;
		
		if(is_boolean($null)) {
			$this->null = $null;
		}
		if(is_numeric($length)) {
			$this->lenght = $length;
		}
		if(is_boolean($unique)) {
			$this->unique = $unique;
		}
		if (is_boolean($primary)) {
			$this->primary = $primary;
		}
		if (is_boolean($this->autoIncrement)) {
			$this->autoIncrement = $autoIncrement;
		}
	}
	
	public static function fromObjProperty(\ReflectionProperty $property) {
		
		
		return new DBField();
	}
	
	public static function fromDbColumn($dbColumn) {
		
		$primary 		= false;
		$null 			= false;
		$autoIncrement 	= false;
		$unique 		= false;
		$name 		= "";
		$type 		= "";
		$lenght		= "";
		
		if(isset($dbColumn->Key)) {
			$primary = $dbColumn->Key == "PRI";
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