<?php
namespace src;
use yogi\framework\HttpApplication;
use yogi\framework\mvc\filter\AuthorizationFilter;
use yogi\framework\route\Route;
use yogi\framework\security\interfaces\IRoleProvider;
use yogi\framework\security\interfaces\IMembershipProvider;

class Application extends HttpApplication {

	/**
	 * [Inject(yogi\framework\security\interfaces\IMembershipProvider)]
	 * @var IMembershipProvider
	 */
	public $membershipProvider;

	/**
	 * [Inject(yogi\framework\security\interfaces\IMembershipProvider)]
	 * @var IRoleProvider
	 */
	public $roleProvider;

	public function applicationInstall() {
		$this->membershipProvider->createUser("superadmin", "password", true);
	}

	protected function applicationStart() {
		
		$authorizationFilter = new AuthorizationFilter(
				$this->container->get('yogi\framework\utils\interfaces\IAnnotationHandler'));
		$authorizationFilter->setMembership(
				$this->container->get(
						'yogi\framework\security\interfaces\IMembershipProvider'));

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