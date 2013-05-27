<?php
class Router implements IRouter {
	
	private $controllerFactory = null;
	private $registry = null;
	private $config = null;
	
	public function __construct() {
		
	}
	
	public function setRouterConfig(RouterConfig $config) {
		$this->config = $config;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IRouter::getConfig()
	 */
	public function getRouterConfig() {
		return $this->config;
	}
	
	public function lookup(IRequest $request) {
		$path = $request->getPath();
		$action = new Action();
		
		/**
		 * @Todo This needs so much rewriting
		 */
		foreach($this->config->getRoutes()->getIterator() as $route) {
			$url = explode("/", $route->getUrl());
			
			$defaults = $route->getDefaults();
			
			$controller = $defaults['controller'];
			$actionString = $defaults['action'];
			$parameters = array();
			foreach($defaults as $param => $value) {
				if($param != 'controller' && $param != 'action') {
					$parameters[$param] =  $value;
				}
			}
			
			foreach($url as $index => $part) {
				if($this->isToken($part)) {
						
					if($part == "{controller}") {
						
						$controller = $defaults['controller'];
						if(isset($path[$index]) && $path[$index] != null) {
							
							$controller = $path[$index];
						}
						
					} else if($part == "{action}") {
						$actionString = $defaults['action'];
						if(isset($path[$index]) && $path[$index] != null) {
							$actionString = $path[$index];
						} 

					} else {
						
						$parameter = str_replace(array('{', '}'),'', $url[$index]);
						$value = $defaults[$parameter];
						if(isset($path[$index]) && $path[$index] != null) {
							$value = $path[$index];
						} else if ($request->get($parameter) != null) {
							$value = $request->get($parameter);
						}
						
						$parameters[$parameter] = $value;
						
					}
				} else {
					if($path[$index] != $part) {
						break;
					}
				}
				
				if($request->getRequestMethod() == Request::METHOD_POST) {
					$actionString = "post_".$actionString;
				}
				
				$action->setAction($actionString);
				$action->setController($controller);
				foreach($parameters as $param => $value) {
					$action->addParameter($param, $value);
				}
				
				if($action->getController() != null && $action->getAction() != null && strtolower($route->getName()) != "default") {
					break 2;
				} else {
					
				}
			}
			
		}
		//$action->setController($defaults["controller"]);
		//$action->setName($defaults['action']);
	
		
		return $action;
	}
	
	public function init() {
		
	}
	
	private function isToken($string) {
		$regex = new Regexp('{.+?}');
		
		return $regex->match($string);
	}
}