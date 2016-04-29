<?php
namespace yogi\framework\mvc\filter\interfaces;
use yogi\framework\utils\interfaces\IAnnotationHandler;

use yogi\framework\mvc\filter\ActionContext;

use yogi\framework\mvc\interfaces\IController;

use yogi\framework\IApplication;

use yogi\framework\mvc\filter\interfaces\IFilter;

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