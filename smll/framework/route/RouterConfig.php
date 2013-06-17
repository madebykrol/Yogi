<?php
namespace smll\framework\route;
use smll\framework\route\interfaces\IRouterConfig;
use smll\framework\utils\HashMap;
use smll\framework\route\interfaces\IRoute;

class RouterConfig implements IRouterConfig {
	private $routes;
	
	public function __construct() {
		$this->routes = new HashMap();
	}
	
	public function ignoreRoute($string) {
		
	}
	
	public function getRoutes() {
		return $this->routes;
	}
	
	public function mapRoute(IRoute $route) {
		$this->routes->add($route->getName(), $route);
	}
}