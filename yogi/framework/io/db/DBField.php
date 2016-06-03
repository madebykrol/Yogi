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
		
		if(is_bool($null)) {
			$this->null = $null;
		}
		if(is_numeric($length)) {
			$this->lenght = $length;
		}
		if(is_bool($unique)) {
			$this->unique = $unique;
		}
		if (is_bool($primary)) {
			$this->primary = $primary;
		}
		if (is_bool($this->autoIncrement)) {
			$this->autoIncrement = $autoIncrement;
		}
	}
}