<?php
namespace smll\framework\io\db;
class DBField {
	protected $name;
	protected $type;
	protected $lenght;
	protected $unique;
	protected $primary;
	protected $autoIncrement;
	
	public function __construct($name, $type, $lenght = null, $unique = false, $primary = false, $autoIncrement = false) {
		$this->name = $name;
		$this->type = $type;
		
		if(is_numeric($lenght)) {
			$this->lenght = $lenght;
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
}