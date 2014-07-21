<?php
namespace src;
use smll\framework\HttpApplication;
use smll\framework\mvc\filter\AuthorizationFilter;
use smll\framework\route\Route;
use smll\framework\security\interfaces\IRoleProvider;
use smll\framework\security\interfaces\IMembershipProvider;

class Application extends HttpApplication {
	
	/**
	 * [Inject(smll\framework\security\interfaces\IMembershipProvider)]
	 * @var IMembershipProvider
	 */
	public $membershipProvider;
	
	/**
	 * [Inject(smll\framework\security\interfaces\IMembershipProvider)]
	 * @var IRoleProvider
	 */
	public $roleProvider;
	
	public function applicationInstall() {
		print "derp";
		print_r($this->membershipProvider);
		
		$this->membershipProvider->createUser("superadmin", "HerpDerp123", true);
		print_r($this->roleProvider);
	}
	
	protected function applicationStart() {	
		
		$authorizationFilter = new AuthorizationFilter(
				$this->container->get('smll\framework\utils\interfaces\IAnnotationHandler'));
		$authorizationFilter->setMembership(
				$this->container->get(
						'smll\framework\security\interfaces\IMembershipProvider'));
		
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
		
		$this->routerConfig->mapRoute(
				new Route("Api", "Api/{action}/{id}",
						array(
								"controller" 	=> "Api",
								"action" 		=> "index",
								"id" 			=> Route::URLPARAMETER_OPTIONAL)));
	}
}