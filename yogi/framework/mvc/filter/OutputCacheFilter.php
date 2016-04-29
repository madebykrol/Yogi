<?php
namespace yogi\framework\mvc\filter;

use yogi\framework\utils\interfaces\IAnnotationHandler;

use yogi\framework\mvc\filter\interfaces\IActionFilter;
class OutputCacheFilter implements IActionFilter {
	
	/**
	 * 
	 * @var IAnnotationHandler
	 */
	private $annotationHandler;
	
	public function setAnnotationHandler(IAnnotationHandler $annotationHandler) {
		$this->annotationHandler = $annotationHandler;
	}
	
	public function onActionCall(ActionContext $context) {
		
		$application = $context->getApplication();
		
		$action = $context->getAction();
		if($this->annotationHandler->hasAnnotation('OutputCache', $action)) {
			// perform output caching
			
		}
		
		
	}
}