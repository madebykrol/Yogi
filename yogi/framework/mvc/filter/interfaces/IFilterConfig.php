<?php
namespace yogi\framework\mvc\filter\interfaces;
use yogi\framework\mvc\filter\interfaces\IActionFilter;
use yogi\framework\mvc\filter\interfaces\IAuthorizationFilter;

/**
 * A filterconfig contains the filters inside of the current executing application.
 * @author Kristoffer "mbk" Olsson
 *
 */
interface IFilterConfig {
	/**
	 * Add an actionfilter to the stack.
	 * @param IActionFilter $filter
	 */
	public function addActionFilter(IActionFilter $filter);
	
	/**
	 * Get the action filter stack
	 */
	public function getActionFilters();
	
	/**
	 * Add an authorization filter to the stack
	 * @param IAuthorizationFilter $filter
	 */
	public function addAuthorizationFilter(IAuthorizationFilter $filter);
	
	/**
	 * Get the authorization filter stack
	 */
	public function getAuthorizationFilters();
	
}