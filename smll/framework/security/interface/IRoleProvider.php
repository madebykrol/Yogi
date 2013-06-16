<?php
interface IRoleProvider {
	public function getRoles();
	public function findUserInRole($user, $role);
	public function getRolesForUser($user);
}