<?php
namespace smll\framework\di;
use smll\framework\di\interfaces\IDependencyContainer;
use smll\framework\di\interfaces\IContainerModule;
use smll\framework\utils\HashMap;
use smll\framework\utils\ArrayList;
use smll\framework\utils\AnnotationHandler;
use smll\framework\utils\interfaces\IAnnotationHandler;
use smll\framework\di\Service;
use smll\framework\di\interfaces\IService;
use \ReflectionProperty;
use \ReflectionClass;
use \ReflectionMethod;
/**
 * 
 * @author Kristoffer "mbk" Olsson
 *
 */
class ContainerBuilder implements IDependencyContainer {
	
	protected $register;
	protected $parameters;
	protected $annotationHandler;
	protected $scopes = array(
		'request' 		=> array(),	
	);
	
	public function __construct() {
		$this->register = new HashMap();
		$this->parameters = new HashMap();
		$this->annotationHandler = new AnnotationHandler();
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
	/**
	 * (non-PHPdoc)
	 * @see IDependencyContainer::register()
	 */
	public function register($class, $for) {
		return $this->registerWithIdent($for, $class, $for);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IDependencyContainer::registerWithIdent()
	 */
	public function registerWithIdent($ident, $class, $for) {
		$definition = new Definition($class);
		$this->register->add($ident, $definition);
		
		return $definition;
	}
	
	public function &get($ident) {
		try {
			$definition = $this->register->get($ident);
			$service = null;
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
			
			$properties = $reflectClass->getProperties();
			
			foreach($properties as $property) {
				if($property instanceof ReflectionProperty) {
					$doc = $property->getDocComment();
					
					if($this->annotationHandler->hasAnnotation("Inject", $property)) {
						$name = $property->getName();
						
						$prop = $this->annotationHandler->getAnnotation("Inject", $property);
						
						if($property->isPublic()) {
							$property->setValue($service, $this->get($prop[1][0]));
						} else {
							if($reflectClass->hasMethod('set'.ucfirst($property->getName()))) {
								$m = $reflectClass->getMethod('set'.ucfirst($property->getName()));
								$m->invoke($service, $this->get($prop[1][0]));
							}
						}
						
					} else {
						continue;
					}
				}
			}
			
			foreach($definition->getSetInjects() as $var => $val) {
				
				if($reflectClass->hasMethod("set".ucfirst($var))) {
					$method = $reflectClass->getMethod("set".ucfirst($var));
					if($val instanceof Service) {
						$val = $this->get($val->getServiceReference());
					}
					
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
	
	public function loadModule(IContainerModule $module) {
		foreach($module->getRegister()->getIterator() as $ident => $definition) {			
			$this->register->add($ident, $definition);
		}
	}
	
}