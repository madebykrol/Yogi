<?php
namespace smll\framework\mvc\filter\interfaces;
use smll\framework\mvc\interfaces\IViewResult;

use smll\framework\utils\HashMap;
/**
 * Contexts are used by filters to make decisions based on the current 
 * executing environment
 * When a filter decides to interfere with the process of the application
 * a Result is set. 
 * 
 * This allows for creation on Authorizisation requirement, and output caching.
 * @author ksdkrol
 *
 */
interface IContext {
	/**
	 * Set parameters passed by the request
	 * @param HashMap $parameters
	 */
	public function setParameters(HashMap $parameters);
	/**
	 * Get parameters
	 */
	public function getParameters();
	
	/**
	 * Set the result of this context. If any!
	 * @param IViewResult $result
	 */
	public function setResult(IViewResult $result);
	
	/**
	 * Get the result currently in the context.
	 */
	public function getResult();
}