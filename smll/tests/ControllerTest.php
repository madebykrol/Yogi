<?php
namespace smll\tests;
use smll\framework\http\interfaces\IHeaderRepository;

use smll\framework\utils\HashMap;

use smll\framework\mvc\interfaces\IController;

use smll\framework\mvc\interfaces\IViewEngineRepository;

use smll\framework\di\interfaces\IDependencyContainer;

use smll\framework\IApplication;
use smll\framework\mvc\ViewResult;
use smll\framework\mvc\Controller;
use smll\framework\unittest\UnitTest;
class ControllerTest extends UnitTest {

    protected $controller = null;

    public function setup() {
        $this->controller = new Controller();
        $this->controller->setHeaders(new MockHeaderRepository());
        $this->controller->setApplication(new MockApplication());
    }

    public function testView() {
        $this->assert($this->controller->view() instanceof ViewResult);
    }

    public function testInternalRedirect() {

        $this->assert($this->controller->redirectToAction('index') instanceof ViewResult);
    }

}

class MockHeaderRepository implements IHeaderRepository {
    public function getCookie($name) {
    }
    public function getHeaders() {
        return new HashMap();
    }
    public function setCookie($name, $data, $expire, $path, $domain){
    }
    public function add($field, $value) {
    }
}

class MockApplication implements IApplication {
    public function run() {

    }

    public function install() {
    }

    public function setContainer(IDependencyContainer $container) {
    }

    public function setViewEngines(IViewEngineRepository $repository) {
    }

    public function processAction($controller, $actionName, HashMap $parameters = null) {
    }

    /**
     * @return IController
     */
    public function &getCurrentExecutingController() {
        return null;
    }
    public function getApplicationRoot(){
        return "";
    }

    /**
     * @return IDependencyContainer
     */
    public function getContainer() {
        return null;
    }
}