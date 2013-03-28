<?php
class Definition {
	protected $class;
	protected $file;
	protected $factoryMethod = null;
	protected $arguments = array();
	protected $methodCalls = array();
	
	public function __construct($type) {
		$this->class = $type;
	}

	public function getClass() {
		return $this->class;
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
	
	public function getMethodCalls() {
		return $this->methodCalls;
	}
	
}