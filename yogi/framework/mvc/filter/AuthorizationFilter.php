<?php
namespace yogi\framework\mvc\filter;
use yogi\framework\mvc\filter\FilterAttribute;
use yogi\framework\mvc\filter\interfaces\IAuthorizationFilter;
use yogi\framework\security\interfaces\IMembershipProvider;
use yogi\framework\mvc\filter\AuthorizationContext;
use yogi\framework\mvc\ViewResult;
use yogi\framework\mvc\interfaces\IActionResult;
use yogi\framework\utils\HashMap;
/**
 * Default Authorization filter!
 * 
 * Denies access to controllers and or actions based on if current user is
 * authorized and or in a specific role or roles
 * 
 * By annoting Controllers or Actions with the
 * [Authorize] Annotation you force authorization to access it's function
 * 
 * By annoting with [InRole(Role=Editor)]
 * You force the user to be inside the "Editor" Role to access the functionallity
 * of your controller and or action.
 * 
 * @author Kristoffer "mbk" Olsson
 *
 */
class AuthorizationFilter extends FilterAttribute implements IAuthorizationFilter {
	
	/**
	 * Request injection of a MembershipProvider
	 * [Inject(IMembershipProvider)]
	 * @var IMembershipProvider
	 */
	private $membership;
	
	/**
	 * Setter method for membership.
	 * @param IMembershipProvider $membership
	 */
	public function setMembership(IMembershipProvider $membership) {
		$this->membership = $membership;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\mvc\filter\interfaces\IAuthorizationFilter::onAuthorization()
	 */
	public function onAuthorization(AuthorizationContext $context) {
		// If action or controller is annoted with AllowAnonymous
		if(isset($this->annotations['AllowAnonymous'])) {
			return;
		} else {
			// Else we start to check for authorization.
			$user = $context->getController()->getPrincipal();
			if(isset($this->annotations['Authorize'])) {
				if(!$user->getIdentity()->isAuthenticated()) {
					// If user is not authenticated and authorization is required.. 
					// Redirect to login with return url "Action url"
					$this->redirect($context, 
							$context->getApplication()->getApplicationRoot().
							"/Account/login");
					return;
				}
		  }
		  // If annotations for InRole is set.
		  if(isset($this->annotations['InRole'])) {
		  	
		  	// First Check for autnetication.
		  	if($user->getIdentity()->isAuthenticated()) {
		  		
		  		if(stripos($this->annotations['InRole'][1]['Role'], '|') !== FALSE) {
		  			$roles = explode('|', $this->annotations['InRole'][1]['Role']);
		  			$inRole = false;
		  			foreach($roles as $role) {
			  			if($user->isInRole($role)) {
			  				$inRole = true;
			  				break;
			  			}
		  			}
		  			if(!$inRole) {
		  				$this->redirect($context, $context->getApplication()->getApplicationRoot()."/Home");
		  				return;
		  			}
		  		} else {
		  			if(!$user->isInRole($this->annotations['InRole'][1]['Role'])) {
		  				$this->redirect($context, $context->getApplication()->getApplicationRoot()."/Home");
		  				return;
		  			}
		  		}
			  	
		  	}
		  }
		}
		
		return;
	}
	
	private function checkOrRedirect() {
		
	}
	
	public function redirect(AuthorizationContext $context, $location) {
		$result = new ViewResult();
		 
		$headers = new HashMap();
		$headers->add("Location", $location);
		$result->setHeaders($headers);
		
		$context->setResult($result);
	}
}