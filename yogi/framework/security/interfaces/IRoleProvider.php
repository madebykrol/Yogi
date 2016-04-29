<?php
namespace yogi\framework\security\interfaces;
interface IRoleProvider {
	public function getRoles();
	public function findUserInRole($user, $role);
	public function getRolesForUser($user);
	
	public function addUserInRole($role, $user);
	public function removeUserFromRole($role, $user);
}