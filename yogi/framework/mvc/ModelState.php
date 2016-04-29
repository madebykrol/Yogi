<?php
namespace yogi\framework\mvc;
use yogi\framework\mvc\interfaces\IModelState;
use yogi\framework\utils\HashMap;

class ModelState implements IModelState {
	
	private $modelState = true;
	private $errorMessages = null;
	private $invalidFields = null;
	
	public function __construct() {
		$this->errorMessages = new HashMap();
		$this->invalidFields = array();
	}
	
	public function isValid($state = null) {
		if(is_bool($state)) {
			$this->modelState = $state;
		}
		return $this->modelState;
	}
	
	public function setIsInValid($name) {
		$this->invalidFields[] = $name;
	}
	
	public function getIsInValid($name) {
		return in_array($name, $this->invalidFields);
	}
	
	public function setErrorMessageFor($name, $message) {
		$this->errorMessages->add($name, $message);
	}
	
	public function getErrorMessageFor($name) {
		return $this->errorMessages->get($name);
	}
	
	public function getErrorStack() {
		return $this->errorMessages;
	}
	
}