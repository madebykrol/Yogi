<?php
class AuthenticationTicket implements ITicket {
	
	private $username = "";
	private $valid = "";
	private $issued = "";
	private $data = "";
	private $cookiePath = "";
	private $expiryDate;
	
	public function __construct($username, $valid, $issued, $data, $cookiePath, $expiryDate ) {
		$this->username = $username;
		$this->valid = $valid;
		$this->issued = $issued;
		$this->data = $data;
		$this->cookiePath = $cookiePath;
		$this->expiryDate = $expiryDate;
	}
	
	public function setExpiryDate($datetime) {
		$this->expiryDate = $datetime;
	}
	
	public function getExpiryDate() {
		return $this->expiryDate;
	}
	
	public function setUsername($string) {
		$this->username = $string;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function setValidity($boolean) {
		$this->valid = $boolean;
	}
	
	public function getValidity() {
		return $this->valid;
	}
	
	public function setIssuedDate($datetime) {
		$this->issued = $datetime;
	}
	
	public function getIssuedDate() {
		return $this->issued;
	}
	
	public function setData($string) {
		$this->data = $string;
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function setCookiePath($path) {
		$this->cookiePath = $path;
	}
	
	public function getCookiePath() {
		return $this->cookiePath;
	}
	
	public function __toString() {
		return $this->username
		.";".$this->valid
		.";".$this->issued
		.";".$this->data
		.";".$this->cookiePath
		.";".$this->expiryDate;
	}
}