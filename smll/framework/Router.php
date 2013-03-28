<?php
class Router implements IRouter {
	
	protected $controllerFactory = null;
	
	public function __construct(IControllerFactory $controllerFactory) {
		$this->controllerFactory = $controllerFactory;
	}
	
	public function lookup(Request $request) {
		$controllerName = $request->getPath(0);
		print_r($this->controllerFactory->createController($request, "derp"));
	}
	
	public function init() {
		print "apa";
	}
	
}