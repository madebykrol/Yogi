<?php
namespace yogi\framework\mvc\filter;
use yogi\framework\utils\HashMap;

use yogi\framework\mvc\filter\ControllerContext;
use yogi\framework\mvc\interfaces\IController;
use yogi\framework\IApplication;
use yogi\framework\mvc\interfaces\IActionResult;

/**
 * @author Kristoffer "mbk" Olsson
 *
 */
class AuthorizationContext extends ControllerContext {
	
	private $controller = null;
	private $application = null;
	private $parameters = null;
	
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
	
	public function setAction(\ReflectionMethod $action) {
		$this->action = $action;
	}
	
	public function getAction() {
		return $this->action;
	}
	
	public function setParameters(HashMap $parameters = null) {
		$this->parameters = $parameters;
	}
	
	public function getParameters() {
		return $this->parameters;
	}
}