<?php
namespace yogi\framework\mvc\interfaces;
use yogi\framework\IApplication;
use yogi\framework\mvc\interfaces\IModelState;
use yogi\framework\security\interfaces\IPrincipal;

interface IController {
	public function setApplication(IApplication $application);
	public function setModelState(IModelState $modelState);
	
	/**
	 * @return ModelState
	 */
	public function &getModelState();
	public function onActionError();
	
	public function __toString();
	
	public function setPrincipal(IPrincipal $user);
}