<?php
class Headers implements IHeaderRepository {
	private $headers = null;
	public function __construct() {
		$this->headers = new HashMap();
	}
	
	public function add($field, $value) {
		$this->headers->add($field, $value);
	}
	
	public function getHeaders() {
		return $this->headers;
	}
	
	public function getCookie($name) {
		
		if(isset($_COOKIE[$name])) {
			return $_COOKIE[$name];
		}
		
		return null;
	}
	
	public function setCookie($name, $data, $expire, $path, $domain) {
		
		setcookie($name, $data, $expire, $path, null);
	}
} 