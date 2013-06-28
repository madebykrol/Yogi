<?php
namespace smll\framework\mvc\filter;
use smll\framework\mvc\filter\FilterAttribute;
use smll\framework\mvc\filter\interfaces\IAuthorizationFilter;
use smll\framework\security\interfaces\IMembershipProvider;
use smll\framework\mvc\filter\AuthorizationContext;
use smll\framework\mvc\ViewResult;
use smll\framework\utils\HashMap;
class AuthorizationFilter extends FilterAttribute implements IAuthorizationFilter {
	
	/**
	 * [Inject(IMembershipProvider)]
	 * @var IMembershipProvider
	 */
	private $membership;
	
	public function setMembership(IMembershipProvider $membership) {
		$this->membership = $membership;
	}
	
	public function onAuthorization(AuthorizationContext $context) {
		
		if(isset($this->annotations['AllowAnonymous'])) {
			return;
		} else {
			$user = $context->getController()->getPrincipal();
			if(isset($this->annotations['Authorize'])) {
				if(!$user->getIdentity()->isAuthenticated()) {
					$this->redirect($context, $context->getApplication()->getApplicationRoot()."/Account/login");
					return;
				}
		  }
		  if(isset($this->annotations['InRole'])) {
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
	
	public function redirect(AuthorizationContext $context, $location) {
		$result = new ViewResult();
		 
		$headers = new HashMap();
		$headers->add("Location", $location);
		 
		$result->setHeaders($headers);
		 
		$context->setResult($result);
	}
}