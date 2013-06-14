<?php
interface IPrincipal {
	/**
	 * @return IIdentity
	 */
	public function getIdentity();
	
	public function setIdentity(IIdentity $identity);
	
	public function isInRole($role);
	
	public function setRoles(ArrayList $roles);
}