<?php
class Request implements IRequest {
	
	protected $requestArr = array();
	
	public function __construct($requestArr) {
		$this->requestArr = $requestArr;
	}
	
	public function getPath($index = null) {
		print_r($this->requestArr);
	}
	
	public function getAccept() {
		return "/";
	}
}