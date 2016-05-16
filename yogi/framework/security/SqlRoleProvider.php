<?php
namespace yogi\framework\security;
use yogi\framework\security\interfaces\IRoleProvider;
use yogi\framework\settings\interfaces\ISettingsRepository;
use yogi\framework\io\db\PDODal;
class SqlRoleProvider implements IRoleProvider {
	
	/**
	 * [Inject(yogi\framework\settings\interfaces\ISettingsRepository)]
	 * @var unknown
	 */
	private $settings;
	
	public function setSettings(ISettingsRepository $settings) {
		$this->settings = $settings;
	}
	
	public function getRoles() {
		$connectionStrings = $this->settings->get('connectionStrings');
		$dbContext = new PDODal($connectionStrings['Default']['connectionString']);
		
		$roles = array();
		foreach($dbContext->query('SELECT role_id, role_name FROM roles AS r') as $role) {
				$roles[$role->role_id] = $role->role_name;
		}
		return $roles;
	}
	public function findUserInRole($user, $role) {
		$connectionStrings = $this->settings->get('connectionStrings');
		$dbContext = new PDODal($connectionStrings['Default']['connectionString']);
		$user = $dbContext->query('SELECT role_id FROM users_in_roles WHERE user_ident = ? AND role_id = ?', $user, $role);
		if(is_array($user) && count($user) > 0) {
			return true;
		}
		return false;
	}
	public function getRolesForUser($user) {
		$connectionStrings = $this->settings->get('connectionStrings');
		$dbContext = new PDODal($connectionStrings['Default']['connectionString']);
		
		$roles = array();
		
		$userRoles = $dbContext->query('SELECT role_name FROM roles AS r 
				LEFT JOIN users_in_roles AS uir ON (r.role_id = uir.role_id) 
				LEFT JOIN memberships AS u ON (uir.user_ident = u.ident) WHERE u.username = ?', $user);
		if(is_array($userRoles)) {
			foreach($userRoles as $role) {
				$roles[] = $role->role_name;
			}
		}
		return $roles;
	}
	
	public function addUserInRole($role, $user) {
		$connectionStrings = $this->settings->get('connectionStrings');
		$dbContext = new PDODal($connectionStrings['Default']['connectionString']);
		
		if(!$this->findUserInRole($user, $role)) {
			$dbContext->insert('users_in_roles', array('user_ident' => $user, 'role_id' => $role));
		}
	}
	
	public function removeUserFromRole($role, $user) {
		$connectionStrings = $this->settings->get('connectionStrings');
		$dbContext = new PDODal($connectionStrings['Default']['connectionString']);
		if($this->findUserInRole($user, $role)) {
			$dbContext->where(array('user_ident', '=', $user));
			$dbContext->where(array('role_id', '=', $role));
			$dbContext->delete('users_in_roles');
		}
	}
}