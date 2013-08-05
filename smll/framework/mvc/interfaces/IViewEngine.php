<?php
namespace smll\framework\mvc\interfaces;
use smll\framework\mvc\interfaces\IViewResult;
interface IViewEngine {

    public function __construct($paths = null);
    public function addPartialViewLocation($locationString);
    public function getPartialViewLocations();

    public function renderResult(IViewResult $result, $controller, $action);
}