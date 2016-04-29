<?php
namespace yogi\framework\mvc\filter;
use yogi\framework\mvc\interfaces\IActionResult;

use yogi\framework\utils\HashMap;

use yogi\framework\mvc\filter\interfaces\IContext;

class ControllerContext implements IContext {
	
	private $result = null;
	
	public function setParameters(HashMap $parameters) {}
	public function getParameters() {}
	
	public function setResult(IActionResult $result = null) {
		$this->result = $result;
	}
	
	public function getResult() {
		return $this->result;
	}
}