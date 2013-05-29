<?php
class DefaultActionFilter implements IActionFilter {
	/**
	 * 
	 * @var ISession
	 */
	private $session = null;
	private $annotationHandler = null;
	
	public function __construct(ISession $session, IAnnotationHandler $annotationHandler) {
		$this->session 						= $session;
		$this->annotationHandler 	= $annotationHandler;
	}
	
	public function pass(ReflectionMethod $method) {
		
		//print_r($this->session);
		$passed = false;
		$annotations = $this->annotationHandler->getAnnotations($method);
		if(count($annotations)> 0) {
			foreach($annotations as $annotation) {
				if($annotation == "AuthenticationRequired" && $this->session->get('authenticated') == true) {
					$passed = true;
				} 
				
				if($annotation == "AllowAnonymous") {
					$passed = true;
				}
			}
		} else {
			return true;
		}
		
		return $passed;
	}
	
	public function getMessage() {
		
	}
}