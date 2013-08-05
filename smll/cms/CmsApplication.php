<?php
namespace smll\cms;

use smll\cms\controllers\BlocksController;

use smll\cms\controllers\ContentController;

use smll\cms\framework\mvc\filters\ContentAuthorizationFilter;

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
use smll\cms\controllers\PagesController;
use smll\cms\framework\content\utils\interfaces\IPageDataRepository;
use smll\framework\utils\Guid;
use \ReflectionMethod;
use \ReflectionClass;
use \ReflectionParameter;

abstract class CmsApplication extends HttpApplication
{

    private $settings;

    public function init()
    {
        parent::init();
        $this->settings = $this->container->get('smll\framework\settings\interfaces\ISettingsRepository');
    }

    protected function callAction(ReflectionMethod $method, IController &$controller, HashMap $parameters = null)
    {

        $args = array();
        $methodParameters = $method->getParameters();
        
        $annotationHandler = $this->getAnnotationHandler();
        if ($controller instanceof PageController) {
                 
            $reflectClass = new ReflectionClass(get_class($controller));
             
            $annotation = $annotationHandler->getAnnotation('PageType', $reflectClass);
            $contentType = $annotation[1][0];
            foreach ($methodParameters as $index => $parameter) {

                $name = $parameter->getName();
                $class = $parameter->getClass();
                if ($class != null) {
                    $className = $class->getName();
                }
                if ((is_object($parameters->get($name)) && $class != null) && $parameters->get($name) instanceof $className) {
                    $args[] = $parameters->get($name);
                    unset($methodParameters[$index]);
                     
                } else if ($parameter->getClass()->getShortName() == 'PageReference') {
                    // Bind model Get Data from DB
                     
                    $args[] = $this->bindPageData($contentType, $class, $controller, $parameters);
                    // Unset parameter from list
                    unset($methodParameters[$index]);
                    break;
                }
            }
        }

        if ($controller instanceof BlockController) {
            
            $reflectClass = new ReflectionClass(get_class($controller));

            $annotation = $annotationHandler->getAnnotation('BlockType', $reflectClass);
            $blockType = $annotation[1][0];
            foreach ($methodParameters as $index => $parameter) {
                 
                 
                $class = $parameter->getClass();
                 
                if ($parameter->getClass()->getShortName() == 'BlockReference') {
                    // Bind model get Data from DB

                    $args[] = $this->bindPageData($blockType, $class, $controller, $parameters);
                    // Unset parameter from list
                    unset($methodParameters[$index]);
                    break;
                }
            }
             
        }
        
        if ($controller instanceof PagesController 
                || $controller instanceof ContentController
                || $controller instanceof BlocksController) {
            
            foreach ($methodParameters as $index => $parameter) {

                if ($parameter instanceof ReflectionParameter) {
                     
                    if ($parameter->getClass() != "" && $parameter->getClass()->isInterface()) {
                        $pageDataRepository = $this->container->get('smll\cms\framework\content\utils\interfaces\IContentTypeRepository');
                        $pagetType;
                        $class;
                        if ($parameter->getClass()->getShortName() == "IPageData" 
                                || $parameter->getClass()->getShortName() == "IContent") {
                            
                            $pageType = $parameters->get($parameter->getName());
                            
                            $class = new ReflectionClass($pageDataRepository->getContentTypeNamespaceClass($pageType));
                        }
                        $args[] = $this->getModelBinder()->bindModel($class, $controller, $parameters);
                        unset($methodParameters[$index]);
                        
                    }
                }
            }
        }
        
        if (isset($parameters)) {
            
            foreach ($methodParameters as $index => $parameter) {
                
                $name = $parameter->getName();
                $class = $parameter->getClass();
                
                if ($class != null) {
                    $className = $class->getName();
                    
                }
                if ((is_object($parameters->get($name)) && $class != null) && $parameters->get($name) instanceof $className) {
                    $args[] = $parameters->get($name);
                } else if ($class != null && $class instanceof ReflectionClass) {
                    $args[] = $this->getModelBinder()->bindModel($class, $controller, $parameters);
                } else {
                    $args[] = $parameters->get($name);
                }

            }
        }
        return $method->invokeArgs($controller, $args);
    }

    protected function bindPageData($contentType, ReflectionClass $class, IController $controller, HashMap $parameters)
    {
        $guid = Guid::parse($parameters->get('ident'));

        $pageDataRepository = $this->container->get('smll\cms\framework\content\utils\interfaces\IPageDataRepository');
        $content = null;
        if ($pageDataRepository instanceof IPageDataRepository) {
            return $pageDataRepository->getPageReference($guid);
        }

    }

    protected function configControllerPaths()
    {
        parent::configControllerPaths();
        $this->controllerPaths->add('smll/cms/controllers/');
    }

    protected function preStart()
    {

        $authorizationFilter = new AuthorizationFilter($this->container->get('smll\framework\utils\interfaces\IAnnotationHandler'));
        $authorizationFilter->setMembership(
                $this->container->get(
                        'smll\framework\security\interfaces\IMembershipProvider'));

        $contentAuthorizationFilter = new ContentAuthorizationFilter($this->container->get('smll\framework\utils\interfaces\IAnnotationHandler'));
        $contentAuthorizationFilter->setMembership(
                $this->container->get(
                        'smll\framework\security\interfaces\IMembershipProvider'));

        $contentAuthorizationFilter->setPageDataRepository(
                $this->container->get(
                        'smll\cms\framework\content\utils\interfaces\IPageDataRepository'));

        $contentAuthorizationFilter->setContentPermissionHandler(
                $this->container->get('smll\cms\framework\security\interfaces\IContentPermissionHandler'));
        $contentAuthorizationFilter->setContentTypeRepository($this->container->get('smll\cms\framework\content\utils\interfaces\IContentTypeRepository'));
        $this->filterConfig->addAuthorizationFilter($authorizationFilter);
        $this->filterConfig->addAuthorizationFilter($contentAuthorizationFilter);

        $this->viewEngines->clearEngines();

        $engine = new SmllViewEngine(null, $this->container->get('smll\framework\io\interfaces\IBrowserContext'));
        
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
                new Route("Content route", "Pages/new/{type}",
                        array(
                                "controller" => "Pages",
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