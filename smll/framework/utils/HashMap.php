<?php
namespace smll\framework\utils;
use smll\framework\utils\interfaces\IList;

class HashMap implements IList {
	
	protected $dataList = array();
	
	public function __construct($data = array()) {
		$this->dataList = $data;
	}
	
	public function add($ident, $data) {
		$this->dataList[$ident] = $data;
	}
	
	public function getLength() {
		return count($this->dataList);
	}
	
	public function getIterator() {
		return new \ArrayIterator($this->dataList);
	}
	
	
	public function get($ident) {
		if(isset($this->dataList[$ident])) {
			return $this->dataList[$ident];
		} else {
			return null;
		}
	}
	
	public function remove($ident) {
		unset($this->dataList[$ident]);
	}
}