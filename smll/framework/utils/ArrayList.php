<?php
class ArrayList implements IList {
	
	protected $t = "IData";
	protected $listData = array();
	
	public function construct() {
		
	}
	
	public function add($data) {
		$this->listData[] = $data;
	}
	
	public function setAt($n, $data) {
		if(is_int($n)) {
			$this->listData[$n] = $data;
		} else {
			throw new InvalidArgumentException("first argument must be an integer value", "", "");
		}
	}
	
	public function get($n) {
		if(isset($this->listData[$n])) {
			return $this->listData[$n];
		}
		return null;
	}
	
	public function getIterator() {
		$iterator = new ArrayIterator($listData);
		return $iterator;
	}
	
	public function toArray() {
		return $this->listData;
	}
	
}