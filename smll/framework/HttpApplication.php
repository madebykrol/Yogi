<?php
namespace smll\framework;
use smll\framework\mvc\filter\ActionContext;

use smll\framework\mvc\CachedViewResult;

use smll\framework\exceptions\AccessDeniedException;

use smll\framework\mvc\ViewResult;

use smll\framework\di\interfaces\IDependencyContainer;

use smll\framework\IApplication;
use smll\framework\io\Request;
use smll\framework\io\interfaces\IRequest;
use smll\framework\route\interfaces\IRouter;
use smll\framework\mvc\filter\interfaces\IFilterConfig;
use smll\framework\di\ContainerBuilder;
use smll\modules\DefaultContainerModule;
use smll\framework\di\Service;
use smll\framework\route\Route;
use smll\framework\utils\HashMap;
use smll\framework\utils\ArrayList;
use smll\framework\mvc\interfaces\IController;
use smll\framework\security\interfaces\IAuthenticationProvider;
use smll\framework\security\Principal;
use smll\framework\security\Identity;
use smll\framework\security\interfaces\IIdentity;
use smll\framework\mvc\filter\AuthorizationContext;
use smll\framework\exceptions\EmptyResultException;
use smll\framework\mvc\interfaces\IActionResult;
use smll\framework\mvc\interfaces\IModelBinder;
use smll\framework\mvc\interfaces\IViewEngineRepository;
use smll\framework\mvc\SmllViewEngine;
use smll\framework\utils\interfaces\IAnnotationHandler;

use \ReflectionMethod;
use \ReflectionClass;
use \ReflectionProperty;
use \Exception;

abstract class HttpApplication Implements IApplication {
	
	private $request;
	private $router;
	
	private $modelBinders;
	
	protected $bundleConfig;
	protected $filterConfig;
	protected $routerConfig;
	
	protected $controllerPaths;
	protected $viewPaths;
	
	/**
	 * [Inject(smll\framework\mvc\interfaces\IViewEngineRepository)]
	 * @var IViewEngineRepository
	 */
	protected $viewEngines;
	
	/**
	 * 
	 * @var IController
	 */
	protected $currentExecutingController;
	
	/**
	 * 
	 * @var IDependencyContainer
	 */
	protected $container;
	
	
	protected $annotationHandler = null;
	
	public function __construct(
			IRequest $request, 
			IRouter $router, 
			IFilterConfig $actionFilters,
			IModelBinder $defaultModelBinder) {
		
		$this->request 	= $request;
		$this->router 	= $router;
		$this->actionFilters = $actionFilters;

		$this->routerConfig = $router->getRouterConfig();
		$this->filterConfig = $actionFilters;
		$this->controllerPaths = new ArrayList();
		$this->modelBinders = new HashMap();
		$this->modelBinders->add("default", $defaultModelBinder);
		set_error_handler(array($this, "onError"));
	}
	
	public function addModelBinder($class, IModelBinder $binder) {
		$this->modelBinders->add($class, $binder);
	}
	
	public function setViewEngines(IViewEngineRepository $engines) {
		$this->viewEngines = $engines;
	}
	
	/**
	 * @return IModelBinder
	 */
	public function getModelBinder($class = "default") {
		return $this->modelBinders->get($class);
	}
	
	public function install() {
		$this->applicationInstall();
	}
	
	public function close() {
		
	}
	
	public function run($request = null) {
		
		if($request == null) {
			$request = $this->request;
		}
		
		$this->preStart();
		
		$this->applicationStart();
		// Verify request
		if($this->verifyRequest($request)) {
			
			// Lookup 
			$action = $this->lookup($request);
			
			$controller = $action->getController()."Controller";
			$actionName = $action->getAction();
			
			$output = $this->processAction($controller, $actionName, $action->getParameters());
			
			print  $output;
		} else {throw new Exception(); }
		
		$this->applicationFinish();
	}
	
	/**
	 * @return IDependencyContainer
	 */
	public function getContainer() {
		return $this->container;
	}
	
	public function setContainer(IDependencyContainer $container) {
		$this->container = $container;
	}
	
	
	public function setAnnotationHandler(IAnnotationHandler $handler) {
		$this->annotationHandler = $handler;
	}
	
	public function getAnnotationHandler() {
		return $this->annotationHandler;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IApplication::getCurrentExecutingController()
	 */
	public function &getCurrentExecutingController() {
		return $this->currentExecutingController;
	}
	
	public function getApplicationRoot() {
		return $this->request->getApplicationRoot();
	}
	
	public function scheduler() {
		
	}
	
	public function onError($errno, $errstr, $errfile = "", $errline = 0, $errcontext = array() ) {
		
	}
	
	public function init() {
		
		
		$this->configControllerPaths();
		
		foreach($this->controllerPaths->getIterator() as $path) {
			$handle = opendir($path);
			while (false !== ($entry = readdir($handle))) {
				if(strpos($entry, "Controller") !== FALSE) {
					$entry = explode(".", $entry);
					$controllerName = $entry[0];
					
					$namespace = str_replace('/', '\\', $path);
					
					$this->container->register($namespace.$controllerName, 'controllers-'.$controllerName)
						->set('application', $this)
						->set('modelState', new Service('smll\framework\mvc\interfaces\IModelState'))
						->inRequestScope();
				}
			}
		}
		
		$this->setAnnotationHandler($this->container->get('smll\framework\utils\interfaces\IAnnotationHandler'));
		
		$this->viewEngines->addEngine(new SmllViewEngine());
	}
	
	/**
	 * @return boolean;
	 */
	protected function isFirstRequest() {
		
		$time = time();
		$fp = fopen("./Manifest.xml", "r");
		$manifestTime = fstat($fp);
		
		return ($time-$manifestTime['mtime'])<20;
	}
	
	/**
	 * @return boolean;
	 */
	protected function checkViewFile($file) {
		if(is_file($file)) {
			return true;
		} 
		return false;
	}
	
	protected function verifyRequest($request) {
		// Perform some verification and first hint of tinkered requests.
		
		return true;
	}
	
	public function processAction($controller, $actionName, HashMap $parameters = null, IRequest $request = null) {
	
		$controller = $this->container->get('controllers-'.$controller);
		
		if(!isset($request)) {
			$request = $this->request;
		}
		
		if($controller instanceof IController) {
			$class = get_class($controller);
			
			
			
			$this->attachPrincipal($controller);
			
			$this->currentExecutingController = $controller;
			
			$class = new ReflectionClass($class);
			
			$actionName = str_replace(array('-', ' ', '+'),'_', $actionName);
			
			$passed = false;
			$output = "";
			$result = null;
			
			$requestMethod = $request->getRequestMethod();
			
			// POST
			if($requestMethod == Request::METHOD_POST 
					&& $class->hasMethod("post_".$actionName)) {
				$method = $class->getMethod("post_".$actionName);
			// PUT	
			} else if ($requestMethod == Request::METHOD_GET
					&& $class->hasMethod($actionName) || $requestMethod == "") {
						$method = $class->getMethod($actionName);
						
			} else if ($requestMethod == Request::METHOD_PUT
					&& $class->hasMethod("put_".$actionName)) {
						$method = $class->getMethod("put_".$actionName);
						
			// DELETE			
			} else if ($requestMethod == Request::METHOD_DELETE
					&& $class->hasMethod("delete_".$actionName)) {
						$method = $class->getMethod("delete_".$actionName);
			// TRACE			
			} else if ($requestMethod == Request::METHOD_TRACE
					&& $class->hasMethod("trace_".$actionName)) {
						$method = $class->getMethod("trace_".$actionName);
			// PATCH			
			} else if ($requestMethod == Request::METHOD_PATCH
					&& $class->hasMethod("patch_".$actionName)) {
						$method = $class->getMethod("patch_".$actionName);
						
			} else {
				$method = $class->getMethod($requestMethod."_".$actionName);
			}
			
			try {
				$result = $this->processAuthorizationFilters($method, $class, $controller,$parameters);
			} catch (AccessDeniedException $e) {
				$result = new ViewResult();
				return "Access denied!";
			}
			if($result == null) {
				// Run Action filters
				try {
					$result = $this->processActionFilters($method, $controller, $parameters);
				} catch (Exception $e) {
					
				}
				
				// If it's still null.. "May need some rethinking"..
				if($result == null) {
					$result = $this->callAction($method, $controller, $parameters);
				}
			}
		
			
			$output = $this->renderResult($result, $controller, $actionName);
				// Output Action.
		} else {
			throw new Exception("");
		}
		
		return str_replace("~/src/", $this->getApplicationRoot()."/src/", $output);
	}
	
	public function renderResult($result, IController $controller, $actionName) {
		$engines = $this->viewEngines->getEngines();
		
		if($result instanceof IActionResult) {
			$controllerName = str_replace("Controller", "", get_class($controller));
			$controllerName = explode('\\', $controllerName);
			$controllerName = $controllerName[count($controllerName)-1];
		
			$render = "";
			foreach($engines->getIterator() as $engine) {
				$render = $engine->renderResult($result, $controllerName, $actionName);
			}
			return $render;
		} else if(is_string($result)) {
			return $result;
		} else {
			throw new EmptyResultException('Action '. $actionName . ' on '. get_class($controller). ' did not yeild any result');
		}
			
	}
	
	protected function processActionFilters(ReflectionMethod $method, IController $controller, HashMap $parameters = null) {
		
		$result = null;
		$annotationHandler = $this->getAnnotationHandler();
		foreach($this->filterConfig->getActionFilters()->getIterator() as $filter) {
			$filter->setAnnotationHandler($annotationHandler);
			$actionContext = new ActionContext();
			$actionContext->setController($controller);
			$actionContext->setAction($method);
			$actionContext->setApplication($this);
			$actionContext->setParameters($parameters);
			$actionContext->setResult($result);
			
			$filter->onActionCall($actionContext);
			
			$result = $actionContext->getResult();
		}
		 
		return $result;
	}
	
	protected function processAuthorizationFilters(ReflectionMethod $method, ReflectionClass $class, IController $controller, HashMap $parameters = null) {
		
		
		
		$result = null;
		// Get AuthorizationFilters
		$annotationHandler = $this->getAnnotationHandler();
		
		
		foreach($this->filterConfig->getAuthorizationFilters()->getIterator() as $filter) {
			$authorizationContext = new AuthorizationContext();
			$authorizationContext->setController($controller);
			$authorizationContext->setApplication($this);
			$authorizationContext->setAction($method);
			$authorizationContext->setParameters($parameters);
			$authorizationContext->setResult($result);
		
			$annotations = array();
		
			if($annotationHandler->hasAnnotation('Authorize', $class)) {
				$annotations['Authorize'] = $annotationHandler->getAnnotation('Authorize', $class);
			} else {
				if($annotationHandler->hasAnnotation('Authorize', $method)) {
					$annotations['Authorize'] = $annotationHandler->getAnnotation('Authorize', $method);
				}
			}
			
			if($annotationHandler->hasAnnotation('AllowAnonymous', $method)) {
				$annotations['AllowAnonymous'] = $annotationHandler->getAnnotation('AllowAnonymous', $method);
			}
		
			if($annotationHandler->hasAnnotation('InRole', $method)) {
				$annotations['InRole'] = $annotationHandler->getAnnotation('InRole', $method);
			}
		
			$filter->setAnnotations($annotations);
			$filter->onAuthorization($authorizationContext);
			$tmpResult = $authorizationContext->getResult();
			$result = $authorizationContext->getResult();
		}
		
		
		return $result;
		
	}
	
	protected function filter(ReflectionMethod $method) {
		$passed = true;
		$filters = $this->filterConfig->getFilters();
		
		foreach($filters->getIterator() as $filter) {
			$passed = $filter->pass($method);
			$message = $filter->getMessage();
		}
		
		return $passed;
	}
	
	
	protected function attachPrincipal(IController $controller) {
		$authenticationHandler = $this->container->get('smll\framework\security\interfaces\IAuthenticationProvider');
		$principal = new Principal();
		$principal->setIdentity(new Identity(null, false, null));
		
		if($authenticationHandler instanceof IAuthenticationProvider) {
			$p = $authenticationHandler->getPrincipal();
			if($p != null) {
				$principal = $p;
			}
		}
			
		$controller->setPrincipal($principal);
	}
	
	protected function lookup(IRequest $request) {
		$action = $this->router->lookup($request);
		
		return $action;
	}
	
	protected function callAction(ReflectionMethod $method, IController &$controller, HashMap $parameters = null) {
		$args = array();
	
		
		if(isset($parameters)) {
			foreach($method->getParameters() as $parameter) {
				$name = $parameter->getName();
				$class = $parameter->getClass();
				$className = "";
				if($class != null) {
					$className = $class->getName();
				}
				if((is_object($parameters->get($name)) && $class != null) && $parameters->get($name) instanceof $className) {
					$args[] = $parameters->get($name);
				} else if($class != null && $class instanceof ReflectionClass) {
					$params = $parameters;
					$rawContent = $this->request->getRawContent();

					if(!empty($rawContent)) {
						$params = new HashMap((array)$rawContent);
					}
					
					$modelBinder = $this->getModelBinder("default");
					if(($binder = $this->getModelBinder($className)) != null) {
						$modelBinder = $binder;
					}
					
					$args[] = $this->getModelBinder()->bindModel($class, $controller, $params);
				} else {
					$args[] = $parameters->get($name);
				}
			}
		}
		return $method->invokeArgs($controller, $args);
	}

	abstract protected function applicationStart();
	
	protected function applicationFinish() {
	    
	}
	
	public function applicationInstall() {}
	
	protected function preStart() {}
	protected function configControllerPaths() {
		$this->controllerPaths->add('src/controllers/');
	}
	
	/**
	 * 
	 * @param ApplicationState $appstate
	 */
	protected function applicationResume(ApplicationState $appstate) {}
}