<?php
namespace yogi\framework\mvc\filter;

use yogi\framework\mvc\interfaces\IController;

use yogi\framework\IApplication;

use yogi\framework\utils\HashMap;

use yogi\framework\mvc\interfaces\IActionResult;

use yogi\framework\mvc\filter\interfaces\IContext;

/**
 * A basic implementation of the IContext interface
 * extending the interface with methods to set Action, Controller and Application
 * @author Kristoffer "mbk" Olsson
 *
 */
class ActionContext implements IContext {
	
	private $action = null;
	private $controller = null;
	private $result = null;
	private $parameters = null;
	private $application = null;
	
	/**
	 * Set the controller of the current action context.
	 * @param IController $controller
	 */
	public function setController(IController $controller) {
		$this->controller = $controller;
	}
	
	/**
	 * Get the controller of the current action context
	 * @return IController
	 */
	public function getController() {
		return $this->controller;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\mvc\filter\interfaces\IContext::setParameters()
	 */
	public function setParameters(HashMap $parameters = null) {
		$this->parameters = $parameters;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\mvc\filter\interfaces\IContext::getParameters()
	 */
	public function getParameters() {
		return $this->parameters;
	}
	
	/**
	 * Set the action of the current action context
	 * @param \ReflectionMethod $method
	 */
	public function setAction(\ReflectionMethod $method) {
		$this->action = $method;
	}
	
	/**
	 * Get the action of the current action context
	 * @return ReflectionMethod
	 */
	public function getAction() {
		return $this->action;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\mvc\filter\interfaces\IContext::setResult()
	 */
	public function setResult(IActionResult $result = null) {
		$this->result = $result;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\mvc\filter\interfaces\IContext::getResult()
	 */
	public function getResult() {
		return $this->result;
	}
	
	public function setApplication(IApplication $application) {
		$this->application = $application;
	}
	
	public function getApplication() {
		return $this->application;
	}
}