<?php
namespace smll\cms;
use smll\cms\framework\BlockController;

use smll\framework\di\interfaces\IContainerModule;

use smll\framework\io\db\DB;

use smll\framework\mvc\SmllViewEngine;

use smll\framework\HttpApplication;
use smll\framework\mvc\filter\AuthorizationFilter;
use smll\framework\route\Route;
use smll\framework\utils\HashMap;
use smll\framework\mvc\interfaces\IController;
use smll\cms\framework\PageController;
use smll\cms\controllers\ContentController;
use smll\cms\framework\content\utils\interfaces\IContentRepository;
use smll\framework\utils\Guid;
use \ReflectionMethod;
use \ReflectionClass;
use \ReflectionParameter;


abstract class CmsApplication extends HttpApplication {
	
	private $settings;
	
	public function init() {
		parent::init();
		$this->settings = $this->container->get('smll\framework\settings\interfaces\ISettingsRepository');
	}
	
	protected function callAction(ReflectionMethod $method, IController &$controller, HashMap $parameters = null) {
		
		$args = array();
		$methodParameters = $method->getParameters();
		
		$annotationHandler = $this->getAnnotationHandler();
		if($controller instanceof PageController) {
			
			$reflectClass = new ReflectionClass(get_class($controller));
			
			$annotation = $annotationHandler->getAnnotation('ContentType', $reflectClass);
			$contentType = $annotation[1][0];
			foreach($methodParameters as $index => $parameter) {
				
				
				$class = $parameter->getClass();

				if($parameter->getClass()->getShortName() == 'PageReference') {
					// Bind model Get Data from DB
					
					$args[] = $this->bindPageData($contentType, $class, $controller, $parameters);
					// Unset parameter from list
					unset($methodParameters[$index]);
					break;
				}
			}
		}
		
		if($controller instanceof BlockController) {
			$reflectClass = new ReflectionClass(get_class($controller));
				
			$annotation = $annotationHandler->getAnnotation('BlockType', $reflectClass);
			$blockType = $annotation[1][0];
			foreach($methodParameters as $index => $parameter) {
			
			
				$class = $parameter->getClass();
			
				if($parameter->getClass()->getShortName() == 'BlockReference') {
					// Bind model Get Data from DB
						
					$args[] = $this->bindPageData($blockType, $class, $controller, $parameters);
					// Unset parameter from list
					unset($methodParameters[$index]);
					break;
				}
			}
			
		}
		
		if($controller instanceof ContentController) {
			foreach($methodParameters as $index => $parameter) {
				
				if($parameter instanceof ReflectionParameter) {
					
					if($parameter->getClass() != "" && $parameter->getClass()->isInterface()) {
						if($parameter->getClass()->getShortName() == "IPageData") {
							$contentRepository = $this->container->get('smll\cms\framework\content\utils\interfaces\IContentRepository');
							
							$pageType = $parameters->get($parameter->getName());
							
							$class = new ReflectionClass($contentRepository->getPageTypeNamespaceClass($pageType));
														
							
							$args[] = $this->getModelBinder()->bindModel($class, $controller, $parameters);
							unset($methodParameters[$index]);
						}
					}
				}
			}
		}
		
		if(isset($parameters)) {
			foreach($methodParameters as $index => $parameter) {
				$name = $parameter->getName();
				$class = $parameter->getClass();
				
				if($class != null && $class instanceof ReflectionClass) {
					$args[] = $this->getModelBinder()->bindModel($class, $controller, $parameters);
				} else {
					$args[] = $parameters->get($name);
				}
	
			}
		}
		return $method->invokeArgs($controller, $args);
	}
	
	protected function bindPageData($contentType, ReflectionClass $class, IController $controller, HashMap $parameters) {
		$guid = Guid::parse($parameters->get('id'));
		
		$contentRepository = $this->container->get('smll\cms\framework\content\utils\interfaces\IContentRepository');
		$content = null;
		if($contentRepository instanceof IContentRepository) {
			return $contentRepository->getPageReference($guid);
		}
		
	}
	
	protected function configControllerPaths() {
		parent::configControllerPaths();
		$this->controllerPaths->add('smll/cms/controllers/');
	}
	
	protected function preStart() {
		
		$authorizationFilter = new AuthorizationFilter($this->container->get('IAnnotationHandler'));
		$authorizationFilter->setMembership(
				$this->container->get(
						'smll\framework\security\interfaces\IMembershipProvider'));
		
		$this->filterConfig->addAuthorizationFilter($authorizationFilter);
		
		$this->viewEngines->clearEngines();
		
		$engine = new SmllViewEngine();
		$engine->addPartialViewLocation('smll/cms/views/{0}/{1}.phtml');
		$engine->addPartialViewLocation('smll/cms/views/Share/{1}.phtml');
		
		
		$this->viewEngines->addEngine($engine);
		
		
		
		
		/**
		 * Default route
		*/
		$this->routerConfig->mapRoute(
				new Route("Default", "{controller}/{action}/{id}",
						array(
								"controller" => "Home",
								"action" => "index",
								"id" => Route::URLPARAMETER_OPTIONAL)));
		
		
		$this->routerConfig->mapRoute(
				new Route("Content route", "Content/new/{type}",
						array(
								"controller" => "Content",
								"action" => "create",
								"type" => Route::URLPARAMETER_REQUIRED)));
		
		$this->routerConfig->mapRoute(
				new Route("Games", "Game/{id}",
						array(
								"controller" => "Game",
								'action' => 'index',
								'id' => Route::URLPARAMETER_REQUIRED
								)));
		
		
	}
	
}