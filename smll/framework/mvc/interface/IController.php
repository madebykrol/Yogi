<?php
interface IController {
	public function setApplication(IApplication $application);
	public function setModelState(IModelState $modelState);
	
	/**
	 * @return ModelState
	 */
	public function &getModelState();
	public function onActionError();
	
	public function __toString();
}