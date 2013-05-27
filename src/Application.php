<?php
class Application extends HttpApplication {
	
	protected function applicationStart() {	
		
		
		$this->routerConfig->mapRoute(
				new Route("Default", "{controller}/{action}/{id}", 
						array("controller" => "Home", "action" => "index", "id" => Route::URLPARAMETER_OPTIONAL)));
		
		$this->routerConfig->mapRoute(
				new Route("Test", "Test/{controller}/{action}/{id}",
						array("controller" => "Derp", "action" => "index", "id" => Route::URLPARAMETER_OPTIONAL)));
		
		
		$this->container->register('ContentRepository', 'IContentRepository');
		
	}
	
}