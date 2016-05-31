<?php
use yogi\framework\unittest\UnitTest;
use yogi\framework\route\Router;
use yogi\framework\route\interfaces\IRouterConfig;
use yogi\framework\route\interfaces\IRoute;
use yogi\framework\utils\HashMap;
use yogi\framework\io\interfaces\IRequest;
use yogi\framework\route\Route;


class RouterTest extends UnitTest {
	
	private $routerConfig;
	private $router;
	
	public function setup() {
		$this->routerConfig = new MockRouterConfig();
		$this->router = new Router();
		$this->router->setRouterConfig($this->routerConfig);
	}
	
	public function testControllerLookup() {
		
		$action = $this->router->lookup(new MockRequest());
		$this->assert(($action->getController() == "Home"));
	}
	
	public function testActionLookup() {
		$action = $this->router->lookup(new MockRequest());
		$this->assert(($action->getAction() == "about"));
	}
	
	public function testParameters() {
		$action = $this->router->lookup(new MockRequest());
		$this->assert((($action->getParameter('id') == "1") && ($action->getParameter('second') == "foo")));
	}
	
	public function testDefaultParameters() {
		$action = $this->router->lookup(new MockDefaultsRequest());
		$this->assert(($action->getParameter('second') == "bar"));
	}
}

class MockRouterConfig implements IRouterConfig {
	public function ignoreRoute($string) {
		
	}
	
	public function getRoutes() {
		$routes = new HashMap();
		$routes->add('Mock route', new MockRoute());
		return $routes;
	}
  
	public function mapRoute(IRoute $route) {
		
	}
}

class MockRoute implements IRoute {
	public function getName() {
		return "Mock Route";
	}
	
	public function getUrl() {
		return "{controller}/{action}/{id}/{second}";
	}
	
	public function getDefaults() {
		return array(
								"controller" => "Home", 
								"action" => "index", 
								"id" => Route::URLPARAMETER_OPTIONAL,
								"second" => "bar");
	}
}

class MockDefaultsRequest implements IRequest {
	public function getPath() {
		return array('Home', 'about', '1');
	}
	public function setPath(array $path) {}
	public function getAccept() {}
	public function getQueryString($var) {}
	public function getPostData() { return array(); }
	public function getGetData() { return array(); }
	public function getApplicationRoot() { }
	public function getRawContent() {return "";}
	public function setRequestMethod($method) {}
	public function getRequestMethod(){return null;}
	public function getCurrentUri() {return "";}
	public function getContentType() {return "";}
}

class MockRequest implements IRequest {
	public function getPath() {
		return array('Home', 'about', '1', 'foo');
	}
	public function setPath(array $path) {}
	public function getAccept() {}
	public function getQueryString($var) {}
	public function getPostData() { return array(); }
	public function getGetData() { return array(); }
	public function getApplicationRoot() { }
	public function getRawContent() {return "";}
	public function setRequestMethod($method) {}
	public function getRequestMethod(){return null;}
	public function getCurrentUri() {return "";}
	public function getContentType() {return "";}
}