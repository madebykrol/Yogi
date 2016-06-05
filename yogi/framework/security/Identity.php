<?php
namespace yogi\framework\security;
use yogi\framework\security\interfaces\IIdentity;
class Identity implements IIdentity {
	private $authenticated = false;
	private $name = null;
	private $authenticationType = null;
	
	public function __construct($name, $authenticated, $type) {
		$this->authenticated = $authenticated;
		$this->name = $name;
		$this->type = $type;
	}
	
	public function isAuthenticated() {
		return $this->authenticated;
	}
	public function getName() {
		return $this->name;
	}
	public function getAuthenticationType() {
		return $this->authenticationType;
	}
}