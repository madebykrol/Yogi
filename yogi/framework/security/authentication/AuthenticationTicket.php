<?php
namespace yogi\framework\security\authentication;
use yogi\framework\security\interfaces\ITicket;
class AuthenticationTicket implements ITicket {
	
	private $username = "";
	private $valid = "";
	private $issued = "";
	private $roles = "";
	private $cookiePath = "";
	private $expiryDate;
	
	public function __construct($username, $valid, $issued, array $roles, $cookiePath, $expiryDate ) {
		$this->username = $username;
		$this->valid = $valid;
		$this->issued = $issued;
		$this->roles = $roles;
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
	
	/**
	 * @param string $string
	 */
	public function setRoles($string) {
		$this->roles = $string;
	}
	
	public function getRoles() {
		return $this->roles;
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
		.";".join(',', $this->roles)
		.";".$this->cookiePath
		.";".$this->expiryDate;
	}
}