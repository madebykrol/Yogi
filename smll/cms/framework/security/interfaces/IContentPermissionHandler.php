<?php
namespace smll\cms\framework\security\interfaces;

interface IContentPermissionHandler {
	public function hasPermission($user, $permission);
	public function getRolesForPageType($id, $event = "View");
	
}