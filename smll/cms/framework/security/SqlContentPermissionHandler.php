<?php
namespace smll\cms\framework\security;

use smll\framework\io\db\DB;

use smll\framework\settings\interfaces\ISettingsRepository;

use smll\cms\framework\security\interfaces\IContentPermissionHandler;

class SqlContentPermissionHandler implements IContentPermissionHandler
{

    private $settings;

    /**
     * @var DB
     */
    private $db;

    public function __construct(ISettingsRepository $settings)
    {
        $this->settings = $settings;

        $connectionStrings = $this->settings->get('connectionStrings');
        $this->connectionString = $connectionStrings['Default']['connectionString'];
        $this->db = new DB($this->connectionString);
    }

    public function hasPermission($user, $permission)
    {
    }
    public function getRolesForPageType($id, $event = "View")
    {
        $db = $this->db;

        $db->where(array('fkPageTypeId', '=', $id));
        $db->where(array('event', '=', $event));
        $permissions = $db->get('page_type_permission');

        $db->flushResult();
        $db->clearCache();

        return $permissions;
    }
}