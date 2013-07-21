<?php
namespace smll\cms\framework\route;

use smll\framework\utils\Guid;

use smll\framework\mvc\Action;

use smll\framework\io\db\DB;

use smll\framework\settings\interfaces\ISettingsRepository;

use smll\framework\io\interfaces\IRequest;

class Router extends \smll\framework\route\Router
{

    /**
     * [Inject(smll\framework\settings\interfaces\ISettingsRepository)]
     * @var unknown
     */
    private $settings;

    public function setSettings(ISettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * (non-PHPdoc)
     * @see \smll\framework\route\interfaces\IRouter::lookup()
     */
    public function lookup(IRequest $request)
    {

        $path = $request->getPath();

        if ($path[0] == "") {
            // Front page
            $webSettings = $this->settings->get('web');
            $path = explode("/", "page/".$webSettings['frontPage']['pageId']);
        }

        if ($this->pathIsInRouteTable($path)) {
            $path = $this->getPath($path);
        }



        if (strtolower($path[0]) == "page") {
             
            $ident = $path[1];
            $action = new Action();
            if ($ident !== '0') {

                $connectionStrings = $this->settings->get('connectionStrings');
                $db = new DB($connectionStrings['Default']['connectionString']);
                if (($guid = Guid::parse($ident)) != null) {
                     
                    $pageType = $db->query('
                            SELECT pt.controller, p.ident FROM page_type AS pt
                            LEFT JOIN page AS p ON (pt.id = p.fkPageTypeId) WHERE p.ident = ?
                            ', $guid);
                } else if (is_numeric($ident)) {
                    $pageType = $db->query('
                            SELECT pt.controller, p.ident FROM page_type AS pt
                            LEFT JOIN page AS p ON (pt.id = p.fkPageTypeId) WHERE p.id = ?
                            ', $ident);
                }
                $controller = $pageType[0]->controller;

                $action->setController($controller);
                $action->setAction('index');
                $action->addParameter('ident', $pageType[0]->ident);

            } else {
                $action->setController('RootPage');
                $action->setAction('index');
            }
            return $action;
        }



        $request->setPath($path);

        return parent::lookup($request);
    }

    private function pathIsInRouteTable(array $path)
    {
        $connectionStrings = $this->settings->get('connectionStrings');
        $db = new DB($connectionStrings['Default']['connectionString']);

        $db->where(array('url', '=', join('/', $path)));
        if ($db->get('url_route')) {
            $result = $db->getResult();
            if (count($result) == 1) {
                return true;
            }
        }

        $db->clearCache();
        $db->flushResult();

        $db->where(array('externalUrl', '=', join('/', $path)));
        if ($db->get('page')){
            $result = $db->getResult();
            if (count($result) == 1) {
                return true;
            }
        }

        return false;
    }

    private function getPath(array $path)
    {
        $connectionStrings = $this->settings->get('connectionStrings');
        $db = new DB($connectionStrings['Default']['connectionString']);

        $db->where(array('url', '=', join('/', $path)));
        if ($db->get('url_route')) {
            $result = $db->getResult();
            return explode('/', $result[0]->internal_path);
        }

        $db->clearCache();
        $db->flushResult();

        $db->where(array('externalUrl', '=', join('/', $path)));
        if ($db->get('page')){
            $result = $db->getResult();
            if (count($result) > 0) {
                return array('page', $result[0]->ident);
            }
        }
        return false;
    }

}