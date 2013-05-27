<?php
class ContainerBuilder implements IDependencyContainer {
	
	protected $register;
	protected $parameters;
	protected $scopes = array(
		'request' 		=> array(),	
	);
	
	public function __construct() {
		$this->register = new HashMap();
		$this->parameters = new HashMap();
	}
	
	public function setParameter($ident, $value) {
		/**
		 * @Todo Implement method body
		 */
	}
	
	public function setParameterForIdent($ident, $value) {
		/**
		 * @Todo Implement method body
		 */
	}
	
	public function register($class, $for) {
		return $this->registerWithIdent($for, $class, $for);
	}
	
	public function registerWithIdent($ident, $class, $for) {
		$definition = new Definition($class);
		$this->register->add($ident, $definition);
		
		return $definition;
	}
	
	public function get($ident) {
		try {
			$definition = $this->register->get($ident);
			
			if($definition->getScope() == Definition::SCOPE_SINGELTON) {
				
				// Find our object in our singelton scope.
				
				// else just continue
				
				return $object;
				
			} else if($definition->getScope() == Definition::SCOPE_REQUEST) {
				if(isset($this->scopes['request'][$ident])) {
					return $this->scopes['request'][$ident];
				}
			}
			
			$reflectClass = new ReflectionClass($definition->getClass());
			
			
			if($reflectClass->hasMethod("__construct")) {
				
				$args = new ArrayList();
				foreach($definition->getArguments() as $arg) {
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
					$arg = $definition->getArgument($index);
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
			
			foreach($definition->getSetInjects() as $var => $val) {
				
				if($reflectClass->hasMethod("set".ucfirst($var))) {
					$method = $reflectClass->getMethod("set".ucfirst($var));
					
					$method->invoke($service, $val);
				}
			}
			
			foreach($definition->getMethodCalls() as $method => $method) {
				
				if($reflectClass->hasMethod($method)) {
					$reflectMethod = $reflectClass->getMethod($method);
					
					$args = array();
					$reflectMethod->invoke($service);
				}
			}
			
			if($definition->getScope() == Definition::SCOPE_REQUEST) {
				$this->scopes['request'][$ident] = $service;
			} else if($definition->getScope() == Definition::SCOPE_SINGELTON) {
				// Store serialized singelton to disc, this should be loaded
				if($service instanceof Serializable) {
					
				} else {
					throw CannotSerializeServiceException();
				}
			}
			
			
			
			return $service;
		} catch (IndexNotInHashException $e) {
			
		}
	}
	
}