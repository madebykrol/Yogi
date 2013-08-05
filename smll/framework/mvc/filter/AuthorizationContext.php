<?php
namespace smll\framework\mvc\filter;
use smll\framework\utils\HashMap;

use smll\framework\mvc\filter\ControllerContext;
use smll\framework\mvc\interfaces\IController;
use smll\framework\IApplication;
use smll\framework\mvc\interfaces\IViewResult;

/**
 * @author Kristoffer "mbk" Olsson
 *
 */
class AuthorizationContext extends ControllerContext {

    private $controller = null;
    private $application = null;
    private $parameters = null;

    public function setController(IController $controller) {
        $this->controller = $controller;
    }

    public function setApplication(IApplication $application) {
        $this->application = $application;
    }

    public function getApplication() {
        return $this->application;
    }

    public function getController() {
        return $this->controller;
    }

    public function setAction(\ReflectionMethod $action) {
        $this->action = $action;
    }

    public function getAction() {
        return $this->action;
    }

    public function setParameters(HashMap $parameters = null) {
        $this->parameters = $parameters;
    }

    public function getParameters() {
        return $this->parameters;
    }
}