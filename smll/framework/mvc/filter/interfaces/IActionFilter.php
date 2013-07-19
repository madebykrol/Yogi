<?php
namespace smll\framework\mvc\filter\interfaces;
use smll\framework\utils\interfaces\IAnnotationHandler;

use smll\framework\mvc\filter\ActionContext;

use smll\framework\mvc\interfaces\IController;

use smll\framework\IApplication;

use smll\framework\mvc\filter\interfaces\IFilter;

/**
 * 
 * Before a Action is performing it's routine, action filters are processed, if 
 * a action filter is returning a Result, the action won't be processed.
 * 
 * This is usefull for creating output caches.
 * 
 * @author Kristoffer "mbk" Olsson
 *
 */
interface IActionFilter {
	/**
	 * Called before the action
	 * @param ActionContext $context
	 */
	public function onActionCall(ActionContext $context);
	
	/**
	 * Setting the annotationhandler!
	 * @param IAnnotationHandler $annotationHandler
	 */
	public function setAnnotationHandler(IAnnotationHandler $annotationHandler);
}