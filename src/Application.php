<?php
namespace src;
use smll\framework\HttpApplication;
use smll\framework\mvc\filter\AuthorizationFilter;
use smll\framework\route\Route;
class Application extends HttpApplication {
	
	protected function applicationStart() {	
		
		$authorizationFilter = new AuthorizationFilter(
				$this->container->get('IAnnotationHandler'));
		$authorizationFilter->setMembership(
				$this->container->get(
						'smll\framework\security\interfaces\IMembershipProvider'));
		
		$this->filterConfig->addAuthorizationFilter($authorizationFilter);
		
		$this->container->register('src\models\ContentRepository', 
				'src\models\IContentRepository')
			->addMethodCall('init');
		
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