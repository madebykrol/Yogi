<?php
abstract class HttpApplication Implements IApplication {
	
	private $request;
	private $router;
	private $settings;
	
	/**
	 * 
	 * @var IModelBinder
	 */
	private $modelBinder;
	
	protected $bundleConfig;
	protected $filterConfig;
	protected $routerConfig;
	
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
	
	public function __construct(
			IRequest $request, 
			IRouter $router, 
			ISettings $settings,
			IActionFilterConfig $actionFilters) {
		
		$this->request 	= $request;
		$this->router 	= $router;
		$this->settings = $settings;
		$this->actionFilters = $actionFilters;

		$this->routerConfig = $router->getRouterConfig();
		$this->filterConfig = $actionFilters;
	}
	
	public function setModelBinder(IModelBinder $binder) {
		if($this->modelBinder == null) {
			$this->modelBinder = $binder;
		} else {
			throw new Exception("Cannot change modelbinder after initialization");
		}
	}
	
	/**
	 * @return IModelBinder
	 */
	public function getModelBinder() {
		return $this->modelBinder;
	}
	
	public function run($request = null) {
		
		if($request == null) {
			$request = $this->request;
		}
		$this->applicationStart();
		// Verify request
		$this->verifyRequest($request);
		
		$action = $this->router->lookup($request);
		
		$controller = $action->getController()."Controller";
		$actionName = $action->getAction();
		
		$output = $this->processAction($controller, $actionName, $action->getParameters());
		// Route to action
		
		print $output;
		
		$this->applicationFinish();
	}
	
	/**
	 * @return IDependencyContainer
	 */
	public function getContainer() {
		return $this->container;
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
	
	public function init() {
		
		$this->container = new ContainerBuilder();
		
		$this->container->register('ModelState', 'IModelState');
		$handle = opendir('src/controllers/');
		
		while (false !== ($entry = readdir($handle))) {
			if(strpos($entry, "Controller") !== FALSE) {
				$entry = explode(".", $entry);
				$controllerName = $entry[0];
				$this->container->register($controllerName, $controllerName)
					->set('application', $this)
					->set('modelState', new Service('IModelState'))
					->inRequestScope();
			}
		}
	}
	
	/**
	 * @return boolean;
	 */
	private function checkViewFile($file) {
		if(is_file($file)) {
			return true;
		} 
		return false;
	}
	
	private function verifyRequest($request) {
		// Perform some verification and first hint of tinkered requests.
	}
	
	public function processAction($controller, $actionName, HashMap $parameters = null) {
	
		$controller = $this->container->get($controller);
		
		if($controller instanceof IController) {
			$class = get_class($controller);
			
			$this->currentExecutingController = $controller;
			
			$class = new ReflectionClass($class);
			
			$passed = false;
			$output = "";
			$result = "";
			
			if($this->request->getRequestMethod() == Request::METHOD_POST 
					&& $class->hasMethod("post_".$actionName)) {
				$method = $class->getMethod("post_".$actionName);
			} else {
				$method = $class->getMethod($actionName);
			}
			
			try {
				
				$passed = $this->filter($method);
				if($passed) {
					$result = $this->callAction($method, $controller, $parameters);
				} else {
					$result = $controller->onActionError();
				}
				
			} catch(Exception $e) {
				$result = $controller->onActionError();
			}
			
			
			
			if($result instanceof IViewResult) {
				$viewFileExists = false;
				$triedViewFiles = new ArrayList();
				
				
				foreach($result->getHeaders()->getIterator() as $field => $value) {
					header($field.": ".$value);
				}
				
				if($result->getViewFile() != null) {
					if(is_file($result->getViewFile())) {
						$viewFileExists = true;
		
					} else {
						$triedViewFiles->add($result->getViewFile());
					}
				} else {
					// Loop through view file conventions
		
					$className = str_replace("Controller", "", get_class($controller));
					$possibleViewFiles = new ArrayList(
							array(
									$className."/_default.phtml",
									$className."/".$actionName.".phtml",
									"Share/".$actionName.".phtml"
							)
					);
		
					foreach($possibleViewFiles->getIterator() as $file) {
						
						if(is_file("src/views/".$file)) {
							$viewFileExists = true;
							$result->setViewFile("src/views/".$file);
							break;
						} else {
							$triedViewFiles->add("src/views/".$file);
						}
					}
				}
		
				if($viewFileExists) {
					
					$output = $result->render();
					
					
				} else {
					foreach($triedViewFiles->getIterator() as $file) {
						print $file."\n";
					}
				}
			} else if(is_string($result)) {
				$output = $result;
			} else {
				throw new Exception();
			}
			
				// Output Action.
		} else {
			throw new Exception();
		}
		
		return $output;
	}
	
	private function filter(ReflectionMethod $method) {
		$passed = true;
		$filters = $this->filterConfig->getFilters();
		
		foreach($filters->getIterator() as $filter) {
			$passed = $filter->pass($method);
			$message = $filter->getMessage();
		}
		
		return $passed;
	}
	
	private function callAction(ReflectionMethod $method, IController &$controller, HashMap $parameters = null) {
		$args = array();
		if(isset($parameters)) {
			foreach($method->getParameters() as $parameter) {
				$name = $parameter->getName();
				$class = $parameter->getClass();
				
				if($class != null && $class instanceof ReflectionClass) {
					$args[] = $this->modelBinder->bindModel($class, $controller, $parameters);
				} else {
					$args[] = $parameters->get($name);
				}
				
			}
		}
		return $method->invokeArgs($controller, $args);
	}

	abstract protected function applicationStart();
	
	protected function applicationFinish() {}
	/**
	 * (non-PHPdoc)
	 * @see IApplication::onActionError()
	 */
	protected function onActionError() {}
	
	/**
	 * 
	 * @param ApplicationState $appstate
	 */
	protected function applicationResume(ApplicationState $appstate) {}
}