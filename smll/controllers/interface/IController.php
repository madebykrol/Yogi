<?php
interface IController {
	public function setRequest(IRequest $request);
	
	/**
	 * @return IAction
	 * @param unknown $action
	 */
	public function call($action);
}