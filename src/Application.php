<?php
class Application extends HttpApplication {
	
	protected function applicationStart() {	
		
		$this->container->register('Session', 'ISession')
			->addArgument(array());
		
		$this->container->register('AnnotationHandler', 'IAnnotationHandler');
		$this->container->register('DefaultActionFilter', 'DefaultActionFilter');
		
		/**
		 * Default route
		 */
		$this->routerConfig->mapRoute(
				new Route("Default", "{controller}/{action}/{id}", 
						array(
								"controller" => "Home", 
								"action" => "index", 
								"id" => Route::URLPARAMETER_OPTIONAL)));
		
		$this->routerConfig->mapRoute(
				new Route("About", "About",
						array(
								"controller" => "Home",
								"action" => "about",
								"id" => Route::URLPARAMETER_OPTIONAL)));
		
		$this->filterConfig->addFilter($this->container->get('DefaultActionFilter'));
		
		
	}
}