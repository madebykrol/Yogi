<?php
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

	public function run() {
		
	}
	
	/**
	 * @return IController
	*/
	public function &getCurrentExecutingController() {
		return null;
	}
	public function getApplicationRoot(){
		return "";
	}
	
	/**
	 * @return IDependencyContainer
	*/
	public function getContainer() {
		return null;
	}
}