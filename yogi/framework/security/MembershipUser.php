<?php
namespace yogi\framework\security;
class MembershipUser {
	protected $providerIdent;
	protected $providerName;
	protected $roles = null;
	
	public function getProviderIdent() {
		return $this->providerIdent;
	}
	
	public function setProviderIdent($ident) {
		$this->providerIdent = $ident;
	}
	
	public function getProviderName() {
		return $this->providerName;
	}
	
	public function setProviderName($name) {
		$this->providerName = $name;
	}
	
	public function setRoles(array $roles) {
		$this->roles = $roles;
	}
	
	public function addRole($role) {
		if(!isset($this->roles)) {
			$this->roles = array();
		}
		$this->roles[] = $role;
	}
	
	public function getRoles() {
		return $this->roles;
	}	

}