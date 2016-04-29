<?php
namespace yogi\framework\route\interfaces;
use yogi\framework\route\interfaces\IRoute;

interface IRouterConfig {
	public function ignoreRoute($string);
	
	/**
	 * @return HashMap
	 */
	public function getRoutes();
	
	/**
	 * @param Route $route
	 */
	public function mapRoute(IRoute $route);
}