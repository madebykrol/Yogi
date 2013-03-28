<?php
class ContainerBuilder implements IDependencyContainer {
	
	protected $register;
	protected $parameters;

	public function __construct() {
		$this->register = new HashMap();
		$this->parameters = new HashMap();
	}
	
	
	public function setParameter($ident, $value) {
		
	}
	
	public function setParameterForIdent($ident, $value) {
		
	}
	
	public function register($class, $interface) {
		return $this->registerWithIdent($interface, $class, $interface);
	}
	
	public function registerWithIdent($ident, $class, $interface) {
		$definition = new Definition($class);
		$this->register->add($ident, $definition);
		
		return $definition;
	}
	
	public function get($ident) {
		try {
			$defintion = $this->register->get($ident);
			$reflectClass = new ReflectionClass($defintion->getClass());
			
			
			if($reflectClass->hasMethod("__construct")) {
				
				$args = new ArrayList();
				foreach($defintion->getArguments() as $arg) {
					if($arg instanceof Service) {
						$args->add($this->get($arg->getServiceReference()));
						continue;
					} if($arg instanceof NullArgument) {
						$args->add(null);
					}
						
					$args->add($arg);
				}
				
				
				$reflectConstructMethod = $reflectClass->getMethod("__construct");
				$params = $reflectConstructMethod->getParameters();
				foreach($params as $index =>  $param) {
					$arg = $defintion->getArgument($index);
					if($arg === null) {
						// try to resolve the parameter by it's interface from within the
						// containers register.

						if($param->getClass() != null) {
							$service = $this->get($param->getClass()->name);
							$args->setAt($index, $service);
						} 
					}
				}
				
				
				$service = $reflectClass->newInstanceArgs($args->toArray());
			} else {
				$service = $reflectClass->newInstance();
			}
			
			foreach($defintion->getMethodCalls() as $method => $method) {
				
				if($reflectClass->hasMethod($method)) {
					$reflectMethod = $reflectClass->getMethod($method);
					$reflectMethod->invoke($service);
				}
			}
			
			return $service;
		} catch (IndexNotInHashException $e) {
			
		}
	}
	
}