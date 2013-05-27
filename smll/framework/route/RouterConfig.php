<?php
class RouterConfig {
	private $routes;
	
	public function __construct() {
		$this->routes = new HashMap();
	}
	
	public function ignoreRoute($string) {
		
	}
	
	public function getRoutes() {
		return $this->routes;
	}
	
	public function mapRoute(Route $route) {
		$this->routes->add($route->getName(), $route);
	}
}