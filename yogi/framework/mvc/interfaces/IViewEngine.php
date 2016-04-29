<?php
namespace yogi\framework\mvc\interfaces;
use yogi\framework\mvc\interfaces\IActionResult;
interface IViewEngine {
	
	public function __construct($paths = null);
	public function addPartialViewLocation($locationString);
	public function getPartialViewLocations();
	
	public function renderResult(IActionResult $result, $controller, $action);
}