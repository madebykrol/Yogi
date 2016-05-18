<?php
namespace src;
use yogi\framework\HttpApplication;
use yogi\framework\mvc\filter\AuthorizationFilter;
use yogi\framework\route\Route;
use yogi\framework\security\interfaces\IRoleProvider;
use yogi\framework\security\interfaces\IMembershipProvider;
use yogi\framework\utils\interfaces\IAnnotationHandler;

class Application extends HttpApplication {

	/**
	 * [Inject(yogi\framework\security\interfaces\IMembershipProvider)]
	 * @var IMembershipProvider
	 */
	public $membershipProvider;

	/**
	 * [Inject(yogi\framework\security\interfaces\IRoleProvider)]
	 * @var IRoleProvider
	 */
	public $roleProvider;
	
	/**
	 * [Inject(yogi\framework\utils\interfaces\IAnnotationHandler)]
	 * @var IAnnotationHandler
	 */
	public $annotationHandler;

	public function applicationInstall() {
		$this->membershipProvider->createUser("superadmin", "password", true);
	}

	protected function applicationStart() {
		
		$authorizationFilter = new AuthorizationFilter(
				$this->annotationHandler);
		$authorizationFilter->setMembership(
				$this->membershipProvider);

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