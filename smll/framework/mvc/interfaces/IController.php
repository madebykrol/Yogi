<?php
namespace smll\framework\mvc\interfaces;
use smll\framework\IApplication;
use smll\framework\mvc\interfaces\IModelState;
use smll\framework\security\interfaces\IPrincipal;

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