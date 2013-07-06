<?php
namespace smll\cms\framework\route;

use smll\framework\mvc\Action;

use smll\framework\io\db\DB;

use smll\framework\settings\interfaces\ISettingsRepository;

use smll\framework\io\interfaces\IRequest;

class Router extends \smll\framework\route\Router {
	
	/**
	 * [Inject(smll\framework\settings\interfaces\ISettingsRepository)]
	 * @var unknown
	 */
	private $settings;
	
	public function setSettings(ISettingsRepository $settings) {
		$this->settings = $settings;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\route\interfaces\IRouter::lookup()
	 */
	public function lookup(IRequest $request) {
		
		$path = $request->getPath();
		
		if($path[0] == "") {
			// Front page
			$webSettings = $this->settings->get('web');
			$path = explode("/", $webSettings['frontPage']['path']);
		}
		
		if($this->pathIsInRouteTable($path)) {
			$path = $this->getPath($path);
		}
		
		
		
		if(strtolower($path[0]) == "page") {
			
			$ident = $path[1];
			
			$connectionStrings = $this->settings->get('connectionStrings');
			$db = new DB($connectionStrings['Default']['connectionString']);
			$pageType = $db->query('
					SELECT pt.controller FROM page_type AS pt 
					LEFT JOIN page AS p ON (pt.id = p.fkPageTypeId) WHERE p.ident = ?
					', $ident);
		
			$controller = $pageType[0]->controller;
			$action = new Action();
			$action->setController($controller);
			$action->setAction('index');
			$action->addParameter('id', $ident);
			
			return $action;
		}
		
		
		
		$request->setPath($path);
		
		return parent::lookup($request);
	}
	
	private function pathIsInRouteTable(array $path) {
		$connectionStrings = $this->settings->get('connectionStrings');
		$db = new DB($connectionStrings['Default']['connectionString']);
		
		$db->where(array('url', '=', join('/', $path)));
		if($db->get('url_route')) {
			$result = $db->getResult();
			if(count($result) == 1) {
				return true;
			}
		}
		
		$db->clearCache();
		$db->flushResult();
		
		$db->where(array('externalUrl', '=', join('/', $path)));
		if($db->get('page')){
			$result = $db->getResult();
			if(count($result) == 1) {
				return true;
			}
		}
		
		return false;
	}
	
	private function getPath(array $path) {
		$connectionStrings = $this->settings->get('connectionStrings');
		$db = new DB($connectionStrings['Default']['connectionString']);
	
		$db->where(array('url', '=', join('/', $path)));
		if($db->get('url_route')) {
			$result = $db->getResult();
			return explode('/', $result[0]->internal_path);
		}
		
		$db->clearCache();
		$db->flushResult();
		
		$db->where(array('externalUrl', '=', join('/', $path)));
		if($db->get('page')){
			$result = $db->getResult();
			if(count($result) > 0) {
				return array('page', $result[0]->ident);
			}
		}
		return false;
	}
	
}