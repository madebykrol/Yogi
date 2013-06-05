<?php
class Router implements IRouter {
	
	private $controllerFactory = null;
	private $registry = null;
	private $config = null;
	
	public function __construct() {
		
	}
	
	public function setRouterConfig(IRouterConfig $config) {
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
		$parameters = array();
		/**
		 * @Todo This needs so much rewriting
		 */
		foreach($this->config->getRoutes()->getIterator() as $route) {
			$url = explode("/", $route->getUrl());
			
			$defaults = $route->getDefaults();
			
			$controller = $defaults['controller'];
			$actionString = $defaults['action'];
			
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
						} else if ($request->getQueryString($parameter) != null) {
							$value = $request->getQueryString($parameter);
						}
						
						$parameters[$parameter] = $value;
						
					}
				} else {
					if($path[$index] != $part) {
						break;
					}
				}
				
				$action->setAction($actionString);
				$action->setController($controller);
				foreach($parameters as $param => $value) {
					$action->addParameter($param, $value);
				}
			}
			
		}
	
		foreach($request->getGetData() as $ident => $val) {
			if($ident != "q") {
				$action->addParameter($ident,$val);
			}
		}
			
		foreach($request->getPostData() as $ident => $val) {
			$action->addParameter($ident,$val);
		}
		return $action;
	}
	
	public function init() {
		
	}
	
	private function isToken($string) {
		$regex = new Regexp('{.+?}');
		
		return $regex->match($string);
	}
}