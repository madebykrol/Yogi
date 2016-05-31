<?php
use yogi\framework\unittest\UnitTest;
use yogi\framework\IApplication;
use yogi\framework\mvc\ViewResult;
use yogi\framework\mvc\Controller;
use yogi\framework\di\interfaces\IDependencyContainer;
use yogi\framework\mvc\interfaces\IViewEngineRepository;
use yogi\framework\utils\HashMap;

class ControllerTest extends UnitTest {
	
	protected $controller = null;
	
	public function setup() {
		$this->controller = new Controller();
		$this->controller->setApplication(new MockApplication());
	}
	
	public function testView() {
		$this->assert($this->controller->view() instanceof ViewResult);
	}
	
	public function testInternalRedirect() {
		$this->assert($this->controller->redirectToAction('index') instanceof ViewResult);
	}
	
}

class MockApplication implements IApplication {
	public function run() {}
	public function close() {}
	
	/**
	 * @return IController
	 */
	public function &getCurrentExecutingController() {return null;}
	public function getApplicationRoot() {return "/";}
	
	
	/**
	 * @return IDependencyContainer
	 */
	public function getContainer() {return null;}
	
	/**
	 * 
	 * @param IDependencyContainer $container
	 */
	public function setContainer(IDependencyContainer $container) {}
	
	/**
	 * 
	 * @param IViewEngineRepository $repository
	 */
	public function setViewEngines(IViewEngineRepository $repository){}
	
	public function processAction($controller, $actionName, HashMap $parameters = null) {}
	
	public function scheduler() {}
	
	public function install() {}
}