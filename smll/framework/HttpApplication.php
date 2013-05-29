<?php
abstract class HttpApplication Implements IApplication {
	
	private $request;
	private $router;
	private $settings;
	
	protected $bundleConfig;
	protected $filterConfig;
	protected $routerConfig;
	
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
	
	public function run($request = null) {
		
		if($request == null) {
			$request = $this->request;
		}
		$this->init();
		$this->applicationStart();
		// Verify request
		$this->verifyRequest($request);
		
		$action = $this->router->lookup($request);
		
		$controller = $action->getController()."Controller";
		$this->currentExecutingController = $action->getController();
		$actionName = $action->getAction();
		
		$output = $this->processAction($controller, $actionName, $action->getParameters());
		// Route to action
		
		print $output;
		
		$this->applicationFinish();
	}
	
	public function getCurrentExecutingController() {
		return $this->currentExecutingController;
	}
	
	public function getApplicationRoot() {
		return $this->settings->getAppSetting('application-root');
	}
	
	public function getContainer() {
		return $this->container;
	}
	
	private function init() {
		
		$this->container = new ContainerBuilder();
		
		$handle = opendir('src/controllers/');
		while (false !== ($entry = readdir($handle))) {
			if(strpos($entry, "Controller") !== FALSE) {
				$entry = explode(".", $entry);
				$controllerName = $entry[0];
				$this->container->register($controllerName, $controllerName);
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
	
	private function processAction($controller, $actionName, HashMap $parameters) {
	
		$controller = $this->container->get($controller);
		
		if($controller instanceof IController) {
			$class = get_class($controller);
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
	
	
	abstract protected function applicationStart();
	
	private function callAction(ReflectionMethod $method, IController $controller, HashMap $parameters) {
		$args = array();
		foreach($method->getParameters() as $parameter) {
			$name = $parameter->getName();
			$class = $parameter->getClass();
			
			if($class != null && $class instanceof ReflectionClass) {
				$obj = $class->newInstance();
				
				foreach($parameters->getIterator() as $name => $value) {
					if($class->hasProperty($name)) {
					 $prop = $class->getProperty($name);
					 if($prop->isPublic()) {
					 
					 	$prop->setValue($obj, $value);
					 } else {
					 	if($class->hasMethod("set".ucfirst($name))) {
					 		$setter = $class->getMethod("set".ucfirst($name));
					 		$setter->invokeArgs($obj, array($value));
					 	}
					 }
					}
				}
				
				$args[] = $obj;
				
			} else {
				$args[] = $parameters->get($name);
			}
		}
		
		return $method->invokeArgs($controller, $args);
	}
	
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