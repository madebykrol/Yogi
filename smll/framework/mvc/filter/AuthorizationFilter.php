<?php
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
				if($user->getIdentity()->isAuthenticated()) {
					return;
				} else {
					$this->redirect($context, $context->getApplication()->getApplicationRoot()."/Account/login");
					return;
				}
		  }
		  if(isset($this->annotations['InRole'])) {
		  	if($user->getIdentity()->isAuthenticated()) {
			  	if($user->isInRole($this->annotations['InRole'][1]['Role'])) {
			  		return;
			  	} else {
			  		$this->redirect($context, $context->getApplication()->getApplicationRoot()."/Home");
			  		return;
			  	}
		  	}
		  }
		}
	}
	
	public function redirect(AuthorizationContext $context, $location) {
		$result = new ViewResult();
		 
		$headers = new HashMap();
		$headers->add("Location", $location);
		 
		$result->setHeaders($headers);
		 
		$context->setResult($result);
	}
}