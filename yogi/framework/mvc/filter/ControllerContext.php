<?php
namespace yogi\framework\mvc\filter;
use yogi\framework\mvc\interfaces\IActionResult;

use yogi\framework\utils\HashMap;

use yogi\framework\mvc\filter\interfaces\IContext;

class ControllerContext implements IContext {

	/**
	 * @var IActionResult
	 */
	private $result = null;
	/**
	 * @var HashMap
	 */
	private $parameters;
	
	public function setParameters(HashMap $parameters) {
		$this->parameters = $parameters;
	}

	/**
	 * @return HashMap
	 */
	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * @param IActionResult|null $result
	 */
	public function setResult(IActionResult $result = null) {
		$this->result = $result;
	}

	/**
	 * @return IActionResult
	 */
	public function getResult() {
		return $this->result;
	}
}