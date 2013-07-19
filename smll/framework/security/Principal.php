<?php
namespace smll\framework\security;
use smll\framework\security\interfaces\IPrincipal;
use smll\framework\security\interfaces\IIdentity;
use smll\framework\utils\ArrayList;
class Principal implements IPrincipal {
	
	private $identity;
	private $roles;
	
	public function getIdentity() {
		return $this->identity;
	}
	
	public function setIdentity(IIdentity $identity) {
		$this->identity = $identity;
	}
	
	public function isInRole($role) {
		if(isset($this->roles)) {
			return $this->roles->has($role);
		} 
		return false;
	}
	
	public function setRoles(ArrayList $roles) {
		$this->roles = $roles;
	}
	
	public function getRoles() {
		return $this->roles;
	}
}