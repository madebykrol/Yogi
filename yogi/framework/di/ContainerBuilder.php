<?php
namespace yogi\framework\di;

use yogi\framework\di\interfaces\IDependencyContainer;
use yogi\framework\di\interfaces\IContainerModule;
use yogi\framework\utils\HashMap;
use yogi\framework\utils\ArrayList;
use yogi\framework\utils\interfaces\IAnnotationHandler;
use yogi\framework\di\Service;
use yogi\framework\di\interfaces\IService;
use \ReflectionProperty;
use \ReflectionClass;
use \ReflectionMethod;
/**
 * Yogi's default implementation of the IContainerModule
 * 
 * The default ContainerBuilder utilizes a Annotation handler so classes can
 * request injection of properties. 
 * A property can be annoted with for example [Inject(yogi\framework\route\interfaces\IRouter)]
 * and the container will automatically inject the property either directly or through
 * a setter method. 
 * @author Kristoffer "mbk" Olsson
 *
 */
class ContainerBuilder implements IDependencyContainer {
	
	/**
	 * Registered services
	 * @var HashMap
	 */
	protected $register;
	
	/**
	 * Registered parameters
	 * @var HashMap
	 */
	protected $parameters;
	
	/**
	 * A Annotation handler, this annotationhandler is used to scan 
	 * classes for requested injections.
	 * 
	 * @var IAnnotationHandler
	 */
	protected $annotationHandler;

	/**
	 * Scopes
	 * @var array
	 */
	protected $scopes = array(
		'request' 		=> array(),	
	);

	/**
	 * ContainerBuilder constructor.
	 * @param IAnnotationHandler $annotationHandler
	 */
	public function __construct(IAnnotationHandler $annotationHandler) {
		$this->register = new HashMap();
		$this->parameters = new HashMap();
		$this->annotationHandler = $annotationHandler;
	}

	/**
	 * @param string $ident
	 * @param mixed $value
	 */
	public function setParameter($ident, $value) {
		$this->parameters->add($ident, $value);
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
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\di\interfaces\IDependencyContainer::get()
	 */
	public function &get($ident) {
		try {
			$definition = $this->register->get($ident);
			$service = null;
			if($definition == null) {
				throw new \Exception("Could not load service for ".$ident);
			}
			if($definition->getScope() == Definition::SCOPE_SINGELTON) {
				// Find our object in our singelton scopes
				
				// #### NOT YET IMPLEMENTED #### //
				// else just continue
				return $object;
			} else if($definition->getScope() == Definition::SCOPE_REQUEST) {
				// If this service is registered in the request scope, it's life time 
				// is set to be through out the complete request.
				if(isset($this->scopes['request'][$ident])) {
					// Return instance of service from request scope.
					return $this->scopes['request'][$ident]; 
				}
			}
			
			// Create a new reflection class for this service 
			$reflectClass = new ReflectionClass($definition->getClass());
			// See if the class has a constructor and begin constructor injection			
			if($reflectClass->hasMethod("__construct")) {
				
				// Get all registered constructor injection parameters
				$args = new ArrayList();
				foreach($definition->getArguments() as $arg) {
					// 
					if($arg instanceof Service) {
						$args->add($this->get($arg->getServiceReference()));
						continue;
					} if($arg instanceof NullArgument) {
						$args->add(null);
					}
						
					$args->add($arg);
				}
				
				// Get constructor method
				$reflectConstructMethod = $reflectClass->getMethod("__construct");
				$params = $reflectConstructMethod->getParameters();
				foreach($params as $index => $param) {
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
				
				// Instantiate from reflection class and inject parameters
				$service = $reflectClass->newInstanceArgs($args->toArray());
			} else {
				// Otherwise just return a instance of the service without any constructor
				// Injections
				$service = $reflectClass->newInstance();
			}
			
			// Go through each property for setter injection.
			$properties = $reflectClass->getProperties();
			
			foreach($properties as $property) {
				if($property instanceof ReflectionProperty) {

					if($this->annotationHandler->hasAnnotation("Inject", $property)) {

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
			
			// Go through each requested injection.
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
					$reflectMethod->invoke($service);
				}
			}
			
			if($definition->getScope() == Definition::SCOPE_REQUEST) {
				$this->scopes['request'][$ident] = $service;
			} else if($definition->getScope() == Definition::SCOPE_SINGELTON) {
				
				if($service instanceof Serializable) {
					
				} else {
					throw CannotSerializeServiceException();
				}
			}
			
			return $service;
		} catch (IndexNotInHashException $e) {
			
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\di\interfaces\IDependencyContainer::loadModule()
	 */
	public function loadModule(IContainerModule $module) {
		foreach($module->getRegister()->getIterator() as $ident => $definition) {			
			$this->register->add($ident, $definition);
		}
	}
	
}