<?php
class MembershipUser {
	protected $providerIdent;
	protected $providerName;
	
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
}