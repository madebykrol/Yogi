<?php
namespace smll\framework\di;
class Definition {
	private $class;
	private $file;
	private $factoryMethod = null;
	private $arguments = array();
	private $methodCalls = array();
	private $setInjects = array();
	private $scope = self::SCOPE_DEFAULT;
	
	public function __construct($class) {
		$this->class = $class;
	}

	public function getClass() {
		return $this->class;
	}
	
	public function inSingeltonScope() {
		$this->scope = self::SCOPE_SINGELTON;
	}
	
	public function inRequestScope() {
		$this->scope = self::SCOPE_REQUEST;
	}
	
	public function inDefaultScope() {
		$this->scope = self::SCOPE_DEFAULT;
	}
	
	public function addArgument($value) {
		$this->arguments[] =  $value;
		return $this;
	}
	
	public function getArguments() {
		
		return $this->arguments;
	}
	
	public function getArgument($n) {
		if(isset($this->arguments[$n])) {
			return $this->arguments[$n];
		} 
		return null;
	}
	
	public function addMethodCall($method) {
		$this->methodCalls[$method] = $method;
		return $this;
	}
	
	public function set($var, $val) {
		$this->setInjects[$var] = $val;
		return $this;
	}
	
	public function getSetInjects() {
		return $this->setInjects;
	}
	
	public function getMethodCalls() {
		return $this->methodCalls;
	}
	
	public function getScope() {
		return $this->scope;
	}
	
	const SCOPE_DEFAULT 		= 0;
	const SCOPE_SINGELTON 	= 1;
	const SCOPE_REQUEST 		= 2;
	
}