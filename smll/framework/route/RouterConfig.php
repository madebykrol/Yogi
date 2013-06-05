<?php
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