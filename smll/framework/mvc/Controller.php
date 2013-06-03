<?php
class Controller implements IController {
	
	protected $viewBag = array(
		'title' => '',		
	);
	
	private $application = null;
	protected $modelState = null;
	
	/**
	 * (non-PHPdoc)
	 * @see IController::setApplication()
	 */
	public function setApplication(IApplication $application) {
		$this->application = $application;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IController::setModelState()
	 */
	public function setModelState(IModelState $modelState) {
		$this->modelState = $modelState;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IController::getModelState()
	 */
	public function &getModelState() {
		return $this->modelState;
	}
	
	/**
	 * @return ViewResult
	 * @param object $model
	 * @param string $view;
	 */
	public function view($model = null, $view = null) { 
		
		$result = new ViewResult();
		$result->init();
		if($model != null) {
			$result->setModel($model);
		}
		
		if($view != null) {
			$result->setView($view);
		}
		
		$result->setViewBag($this->viewBag);
		
		return $result;
		
	}

	
	/**
	 * 
	 * Overloaded HttpRedirect
	 * internalRedirect($action)
	 * internalRedirect($action, $controller)
	 * internalRedirect($action, $controller, $parameters)
	 * 
	 * @param unknown $action
	 * @param string $controller
	 * @param unknown $parameters
	 */
	public function internalRedirect($action, $controller = null, $parameters = array()) {
		
		$result = new ViewResult();
		$result->init();
		if($controller == null) {
			$controller = str_replace("Controller", "", get_class($this));
		}
		if(strtolower($action) == "index") {
			$action = "";
		} else {
			$action = "/".$action;
		}
		$controller = "/".$controller;
		
		$result->setHeader("Location", $this->application->getApplicationRoot().$controller.$action);
		
		return $result;
	}
	
	public function onActionError() {
		return "";
	}
	
	public function __toString() {
		return str_replace("Controller", "", get_class($this));
	}
	
}