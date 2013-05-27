<?php
/**
 * 
 * @author Kristoffer "mbk" Olsson
 *
 */
class Action {
	
	private $controller = null;
	private $action = null;
	private $parameters;
	
	public function __construct() {
		$this->parameters = new HashMap();
	}
	
	public function addParameter($param, $value) {
		$this->parameters->add($param, $value);
	}
	
	public function getParameter($param) {
		return $this->parameters->get($param);
	}
	
	public function getController() {
		return $this->controller;
	}
	
	public function setController($controller) {
		$this->controller = $controller;
	}
	
	public function getAction() {
		return $this->action;
	}
	
	public function setAction($action) {
		$this->action = $action;
	}
	
}