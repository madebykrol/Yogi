<?php
namespace yogi\framework\mvc;
use yogi\framework\mvc\interfaces\IController;
use yogi\framework\mvc\ViewReslut;
use yogi\framework\IApplication;
use yogi\framework\security\interfaces\IAuthenticationProvider;
use yogi\framework\security\interfaces\IMembershipProvider;
use yogi\framework\security\interfaces\IPrincipal;
use yogi\framework\mvc\interfaces\IModelState;
use yogi\framework\http\interfaces\IHeaderRepository;
use yogi\framework\utils\HashMap;


abstract class Controller implements IController {
	
	protected $viewBag = array(
		'title' => '',		
	);
	
	protected $application = null;
	
	/**
	 * [Inject(yogi\framework\http\interfaces\IHeaderRepository)]
	 * @var IHeaderRepository;
	 */
	protected $headers = null;
	
	/**
	 * @var ImodelState
	 */
	protected $modelState = null;
	
	/**
	 * [Inject(yogi\framework\security\interfaces\IAuthenticationProvider)]
	 * @var IAuthenticationProvider
	 */
	protected $authentication;
	
	/**
	 * [Inject(yogi\framework\security\interfaces\IMembershipProvider)]
	 * @var IMembershipProvider
	 */
	protected $membership;
	
	/**
	 * @var IPrincipal
	 */
	protected $user;
	
	/**
	 * 
	 * (non-PHPdoc)
	 * @see IController::setApplication()
	 */
	public function setApplication(IApplication $application) {
		$this->application = $application;
	}
	
	public function setPrincipal(IPrincipal $user) {
		$this->user = $user;
	}
	
	/**
	 * @return IPrincipal
	 */
	public function getPrincipal() {
		return $this->user;
	}
	
	public function setHeaders(IHeaderRepository $headers) {
		$this->headers = $headers;
	}
	
	public function setMembership(IMembershipProvider $membership) {
		$this->membership = $membership;
	}
	
	public function setAuthentication(IAuthenticationProvider $authentication) {
		$this->authentication = $authentication;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IController::setModelState()
	 */
	public function setModelState(IModelState $modelState) {
		$this->modelState = $modelState;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IController::getModelState()
	 */
	public function &getModelState() {
		return $this->modelState;
	}
	
	/**
	 * @return ViewResult
	 * @param object $model
	 * @param string $view;
	 */
	public function view($model = null, $view = null) { 
		
		$result = new ViewResult($model);
		
		if($view != null) {
			$result->setViewFile($view);
		}
		
		$result->setViewBag($this->viewBag);
		$result->setHeaders($this->headers->getHeaders());
		
		return $result;
		
	}

	
	/**
	 * Overloaded HttpRedirect
	 * redirectToAction($action)
	 * redirectToAction($action, $controller)
	 * redirectToAction($action, $controller, $parameters)
	 * 
	 * @param unknown $action
	 * @param string $controller
	 * @param unknown $parameters
	 */
	public function redirectToAction($action, $controller = null, HashMap $parameters = null) {
		
		$result = new ViewResult();
		if($controller == null) {
			$controller = str_replace("Controller", "", get_class($this));
		}
		if(strtolower($action) == "index") {
			$action = "";
		} else {
			$action = "/".$action;
		}
		
		$controller = explode('\\', $controller);
		$controller = $controller[count($controller)-1];
		
		$controller = "/".$controller;
		
		$params = "";
		if(isset($parameters) && $parameters->getLength() > 0) {
			$params .= "?";
			$i = 0;
			foreach($parameters->getIterator() as $var => $extra) {
				$params.=$var."=".$extra;
				$i++;
				if($parameters->getLength() > $i) {
					$params.="&";
				}
			}
		}
		
		$this->headers->add("Location", $this->application->getApplicationRoot().$controller.$action.$params);
		
		$result->setHeaders($this->headers->getHeaders());
		return $result;
	}
	
	public function redirectToUri($uri) {
		$result = new ViewResult();
		$result->init();
		
		$this->headers->add("Location", $uri);
		
		$result->setHeaders($this->headers->getHeaders());
		return $result;
	}
	
	public function onActionError() {
		return "";
	}
	
	public function __toString() {
		return str_replace("Controller", "", get_class($this));
	}
	
}