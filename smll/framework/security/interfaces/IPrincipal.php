<?php
namespace smll\framework\security\interfaces;
use smll\framework\security\interfaces\IIdentity;
use smll\framework\utils\ArrayList;
interface IPrincipal {
	/**
	 * @return IIdentity
	 */
	public function getIdentity();
	
	public function setIdentity(IIdentity $identity);
	
	public function isInRole($role);
	
	public function setRoles(ArrayList $roles);
}