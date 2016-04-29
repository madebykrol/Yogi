<?php
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
	public function getAccept() {}
	public function getQueryString($var) {}
	public function getPostData() { return array(); }
	public function getGetData() { return array(); }
	public function getApplicationRoot() { }
}

class MockRequest implements IRequest {
	public function getPath() {
		return array('Home', 'about', '1', 'foo');
	}
	public function getAccept() {}
	public function getQueryString($var) {}
	public function getPostData() { return array(); }
	public function getGetData() { return array(); }
	public function getApplicationRoot() { }
}