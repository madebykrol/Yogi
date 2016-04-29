<?php
namespace yogi\framework\mvc\interfaces;
use yogi\framework\mvc\interfaces\IController;
use yogi\framework\utils\HashMap;
interface IModelBinder {
	public function bindModel(
			\ReflectionClass $class, IController &$controller, HashMap $parameters);
}