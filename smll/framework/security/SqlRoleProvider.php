<?php
class SqlRoleProvider implements IRoleProvider {
	
	/**
	 * [Inject(ISettingsRepository)]
	 * @var unknown
	 */
	private $settings;
	
	public function setSettings(ISettingsRepository $settings) {
		$this->settings = $settings;
	}
	
	public function getRoles() {
		/**
		 * @todo METHOD BODY
		 */
	}
	public function findUserInRole($user, $role) {
		/**
		 * @todo METHOD BODY
		 */
	}
	public function getRolesForUser($user) {
		$connectionStrings = $this->settings->get('connectionStrings');
		$dbContext = new DB($connectionStrings['Default']['connectionString']);
		
		$roles = array();
		foreach($dbContext->query('SELECT role_name FROM roles AS r 
				LEFT JOIN users_in_roles AS uir ON (r.role_id = uir.role_id) 
				LEFT JOIN memberships AS u ON (uir.user_ident = u.ident) WHERE u.username = ?', $user) as $role) {
				$roles[] = $role->role_name;
		}
		
		return $roles;
	}
}