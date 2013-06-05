<?php
interface IRouter {
	
	public function __construct();
	
	/**
	 * @return RouterConfig
	 */
	public function getRouterConfig();
	
	/**
	 * @param RouterConfig $cofig
	 */
	public function setRouterConfig(IRouterConfig $config);
	
	/**
	 * 
	 * Returns controller name
	 * @return Action
	 * @param IRequest $path
	 * 
	 */
	public function lookup(IRequest $request);
}