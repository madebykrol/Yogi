<?php
class AuthorizationContext extends ControllerContext {
	
	private $result = null;
	private $controller = null;
	private $application = null;
	
	public function setController(IController $controller) {
		$this->controller = $controller;
	}
	
	public function setApplication(IApplication $application) {
		$this->application = $application;
	}
	
	public function getApplication() {
		return $this->application;
	}
	
	public function getController() {
		return $this->controller;
	}
	
	public function getResult() {
		return $this->result;
	}
	
	public function setResult(IViewResult $result) {
		$this->result = $result;
	}
}