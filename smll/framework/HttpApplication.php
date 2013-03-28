<?php
abstract class HttpApplication Implements IApplication {
	
	protected $request;
	protected $router;
	protected $settings;
	
	public function __construct(
			IRequest $request, 
			IRouter $router, 
			ISettings $settings) {
		$this->request 	= $request;
		$this->router 	= $router;
		$this->settings = $settings;
	}
	
	public function run() {
		
		$this->applicationStart();
		
		$controller = $this->router->lookup($this->request);
		
		$this->applicationFinish();
		
	}
	
	abstract protected function applicationStart();
	protected function applicationFinish() {}
	protected function applicationResume(ApplicationState $appstate) {}
}