<?php
class Application extends HttpApplication {
	
	protected function applicationStart() {	
		
		$authorizationFilter = new AuthorizationFilter($this->container->get('IAnnotationHandler'));
		$authorizationFilter->setMembership($this->container->get('IMembershipProvider'));
		
		$this->filterConfig->addAuthorizationFilter($authorizationFilter);
		
		/**
		 * Default route
		 */
		$this->routerConfig->mapRoute(
				new Route("Default", "{controller}/{action}/{id}", 
						array(
								"controller" => "Home", 
								"action" => "index", 
								"id" => Route::URLPARAMETER_OPTIONAL)));
		
			
	}
}