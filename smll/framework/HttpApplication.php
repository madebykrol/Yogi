<?php
abstract class HttpApplication Implements IApplication {
	
	private $request;
	private $router;
	private $settings;
	private $controllerFactory;
	
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
			IControllerFactory $factory) {
		$this->request 	= $request;
		$this->router 	= $router;
		$this->settings = $settings;
		$this->controllerFactory = $factory;
		
		$this->routerConfig = $router->getRouterConfig();
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
		
		$output = $this->processAction($controller, $actionName);
		// Route to action
		
		print $output;
		
		$this->applicationFinish();
	}
	
	public function getCurrentExecutingController() {
		return $this->currentExecutingController;
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
	
	private function processAction($controller, $actionName) {
	
		$controller = $this->container->get($controller);
		
		$class = get_class($controller);
		$class = new ReflectionClass($class);
		$method = $class->getMethod($actionName);
		$result = $method->invoke($controller);
		
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
		
		return $output;
	}
	
	abstract protected function applicationStart();
	protected function applicationFinish() {}
	protected function applicationResume(ApplicationState $appstate) {}
}