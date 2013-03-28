<?php
class HashMap implements IList {
	
	protected $dataList = array();
	
	public function add($ident, $data) {
		$this->dataList[$ident] = $data;
	}
	
	public function getIterator() {
		return new ArrayIterator($this->dataList);
	}
	
	
	public function get($ident) {
		if(isset($this->dataList[$ident])) {
			return $this->dataList[$ident];
		} else {
			throw new IndexNotInMapException("Could not find index \"".$ident."\" in map");
		}
	}
}