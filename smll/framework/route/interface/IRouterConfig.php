<?php
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