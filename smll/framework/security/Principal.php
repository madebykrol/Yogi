<?php
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
		return true;
	}
	
	public function setRoles(ArrayList $roles) {
		$this->roles = $roles;
	}
}