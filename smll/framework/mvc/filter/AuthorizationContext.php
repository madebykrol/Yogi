<?php
namespace smll\framework\mvc\filter;
use smll\framework\mvc\filter\ControllerContext;
use smll\framework\mvc\interfaces\IController;
use smll\framework\IApplication;
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