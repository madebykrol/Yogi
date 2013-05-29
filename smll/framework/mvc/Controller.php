<?php
class Controller implements IController {
	
	protected $viewBag = array(
		'title' => '',		
	);
	
	/**
	 * @return ViewResult
	 * @param object $model
	 * @param string $view;
	 */
	public function view($model = null, $view = null) { 
		
		$result = new ViewResult();
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
	 * HttpRedirect($action)
	 * HttpRedirect($action, $controller)
	 * HttpRedirect($action, $controller, $parameters)
	 * 
	 * @param unknown $action
	 * @param string $controller
	 * @param unknown $parameters
	 */
	public function httpRedirect($action, $controller = null, $parameters = array()) {
		
	}
	
	public function onActionError() {
		return "";
	}
	
}