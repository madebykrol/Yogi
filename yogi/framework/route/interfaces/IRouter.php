<?php
namespace yogi\framework\route\interfaces;
use yogi\framework\route\interfaces\IRouterConfig;
use yogi\framework\io\interfaces\IRequest;
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