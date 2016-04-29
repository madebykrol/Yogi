<?php
namespace yogi\framework\route;
use yogi\framework\route\interfaces\IRouterConfig;
use yogi\framework\utils\HashMap;
use yogi\framework\route\interfaces\IRoute;

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