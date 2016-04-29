<?php
namespace yogi\framework\mvc\filter\interfaces;
use yogi\framework\security\interfaces\IMembershipProvider;

use yogi\framework\mvc\filter\AuthorizationContext;

/**
 * Authorization filters are called before actions and Action filters.
 * if A authorization filter is returning a Result. Actions and Action filters 
 * won't be processed!
 * 
 * They are used to create authorization protected parts of your application.
 * @author Kristoffer "mbk" Olsson
 *
 */
interface IAuthorizationFilter {
	
	/**
	 * Called when a user is trying to access a action on your controller.
	 * @param AuthorizationContext $context
	 */
	public function onAuthorization(AuthorizationContext $context);
}