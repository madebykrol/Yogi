<?php
namespace yogi\framework\utils;
use yogi\framework\utils\interfaces\IList;
class ArrayList implements IList {
	
	protected $t = "IData";
	protected $listData = array();
	
	public function __construct($data = array()) {
		$this->listData = $data;
	}
	
	public function add($data) {
		$this->listData[] = $data;
	}
	
	public function setAt($n, $data) {
		if(is_int($n)) {
			$this->listData[$n] = $data;
		} else {
			throw new \InvalidArgumentException("first argument must be an integer value", "", "");
		}
	}
	
	public function get($n) {
		if(isset($this->listData[$n])) {
			return $this->listData[$n];
		}
		return null;
	}
	
	public function getIterator() {
		$iterator = new \ArrayIterator($this->listData);
		return $iterator;
	}
	
	public function toArray() {
		return $this->listData;
	}
	
	public function has($val) {
		return in_array($val, $this->listData);
	}
	
	public function clear() {
		$this->listData = array();
	}
	
	public function getLength() {
		return count($this->listData);
	}
	
	public function find($value, $func) {
		foreach($this->listData as $data) {
			if($func != null) {
				if($func($value, $data)) {
					return $data;
				}
			}
		}
		return null;
	}
	
}