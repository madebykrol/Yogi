<?php
namespace smll\framework\route;
use smll\framework\route\interfaces\IRouter;
use smll\framework\route\interfaces\IRoute;
use smll\framework\route\interfaces\IRouterConfig;
use smll\framework\io\interfaces\IRequest;
use smll\framework\mvc\Action;
use smll\framework\utils\Regexp;
use smll\framework\utils\HashMap;
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
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\route\interfaces\IRouter::lookup()
	 */
	public function lookup(IRequest $request) {
		$rPath = $request->getPath();
		$action = new Action();
		$parameters = array();
	
		$routes = clone $this->config->getRoutes();
		$defaultRoute = $routes->get('Default');
		
		
		if($rPath[0] == "") {
			
			foreach($defaultRoute->getDefaults() as $var => $value) {
				if($var == "action") {
					$action->setAction($value);
				} else  if($var == "controller") {
					$action->setController($value);
				} else {
					$action->addParameter($var, $value);
				}
			}
			
			return $action;
		}
		
		// For each step of route.. Find matches.
		foreach($rPath as $index => $step) {
			foreach($routes->getIterator() as $name =>  $routeMap) {
				if($routeMap instanceof IRoute) {
					if(!isset($parameters[$name]) || !is_array($parameters[$name])) {
						$parameters[$name] = array();
					}
					$mUrl = explode("/", $routeMap->getUrl());
					if(isset($mUrl[$index]) && $mUrl[$index] != $step && !$this->isToken($mUrl[$index])) {
						// Discard all other routes that does not match current step.
						$routes->remove($name);
					} else {
						if(isset($mUrl[$index])) {
							if($this->isToken($mUrl[$index])) {
								$token = str_replace(array('{', '}'), '', $mUrl[$index]);
								
								if($token == 'controller' || $token == 'action') {
									$parameters[$name][$token] = $step;
								} else {
									$parameters[$name][
										$token
									] = $step;
								}
							}
						}
					}
				}
			}
		}	
		
		$routes->remove('Default');

		if($routes->getLength() <= 0) {
			// Now we need to find one singel path.
			$routes->add('Default', $defaultRoute);
		}
		// First cascade is finished.
		$controller = "";
		$actionString = "";
		$name = "";
		if($routes->getLength() > 0) {
			foreach($routes->getIterator() as $name => $route) {
				
				$defaults = $route->getDefaults();
				
				$controller = $defaults['controller'];
				unset($defaults['controller']);
				if(isset($parameters[$name]['controller'])  && $parameters[$name]['controller'] != "") {
					$controller = $parameters[$name]['controller'];
					unset($parameters[$name]['controller']);
				}
				$actionString = $defaults['action'];
				unset($defaults['action']);
				if(isset($parameters[$name]['action']) && $parameters[$name]['action'] != "") {
					$actionString = $parameters[$name]['action'];
					unset($parameters[$name]['action']);
				}
				
				foreach($defaults as $var => $val) {
					if($val == Route::URLPARAMETER_REQUIRED && !isset($parameters[$name][$var])) {
						throw new \Exception();
					} else {
						
					}
				}
			}
		}
		
		
		
		$action->setAction($actionString);
		$action->setController($controller);
		
		foreach($parameters[$name] as $param => $value) {
			$action->addParameter($param, $value);
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