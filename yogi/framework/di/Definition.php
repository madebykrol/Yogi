<?php
namespace yogi\framework\di;
/**
 * 
 * When working with the Dependency container you register services to either
 * identification names or interfaces.
 * 
 * When a registration is made a definition is created!
 * A Definitions manipulation methods all return an instance of the defintion 
 * instance that you are currently working on, making chaining possible.
 * 
 * @author Kristoffer "mbk" Olsson
 *
 */
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
	
	/**
	 * Get the class for the service
	 */
	public function getClass() {
		return $this->class;
	}
	
	/**
	 * Set the scope of the service to singelton
	 * @return \yogi\framework\di\Definition
	 */
	public function inSingeltonScope() {
		$this->scope = self::SCOPE_SINGELTON;
		return $this;
	}
	
	/**
	 * Set the scope of this service to request
	 * @return \yogi\framework\di\Definition
	 */
	public function inRequestScope() {
		$this->scope = self::SCOPE_REQUEST;
		return $this;
	}
	
	/**
	 * Set the scope of this service to the default scope
	 * @return \yogi\framework\di\Definition
	 */
	public function inDefaultScope() {
		$this->scope = self::SCOPE_DEFAULT;
		return $this;
	}
	
	/**
	 * Add argument's to be injected into the new instance
	 * @param unknown $value
	 * @return \yogi\framework\di\Definition
	 */
	public function addArgument($value) {
		$this->arguments[] =  $value;
		return $this;
	}
	
	/**
	 * Get all arguments for this service
	 * @return multitype:
	 */
	public function getArguments() {
		
		return $this->arguments;
	}
	
	/**
	 * Get specific argument
	 * @param number $n
	 * @return multitype:|NULL
	 */
	public function getArgument($n) {
		if(isset($this->arguments[$n])) {
			return $this->arguments[$n];
		} 
		return null;
	}
	
	/**
	 * Adding a method to be called once an instance have been created from this
	 * service.
	 * @param unknown $method
	 * @return \yogi\framework\di\Definition
	 */
	public function addMethodCall($method) {
		$this->methodCalls[$method] = $method;
		return $this;
	}
	
	/**
	 * Set a property injection value
	 * @param unknown $var
	 * @param unknown $val
	 * @return \yogi\framework\di\Definition
	 */
	public function set($property, $val) {
		$this->setInjects[$property] = $val;
		return $this;
	}
	
	/**
	 * Get all injections
	 * @return multitype:
	 */
	public function getSetInjects() {
		return $this->setInjects;
	}
	
	/**
	 * Get all method calls
	 * @return multitype:
	 */
	public function getMethodCalls() {
		return $this->methodCalls;
	}
	
	/**
	 * Get all scopes
	 * @return string
	 */
	public function getScope() {
		return $this->scope;
	}
	
	const SCOPE_DEFAULT 		= 0;
	const SCOPE_SINGELTON 		= 1;
	const SCOPE_REQUEST 		= 2;
	
}