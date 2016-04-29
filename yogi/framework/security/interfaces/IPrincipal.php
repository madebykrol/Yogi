<?php
namespace yogi\framework\security\interfaces;
use yogi\framework\security\interfaces\IIdentity;
use yogi\framework\utils\ArrayList;
interface IPrincipal {
	/**
	 * @return IIdentity
	 */
	public function getIdentity();
	
	public function setIdentity(IIdentity $identity);
	
	public function isInRole($role);
	
	public function setRoles(ArrayList $roles);
	
	public function getRoles();
}