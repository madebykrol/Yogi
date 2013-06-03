<?php
interface IModelBinder {
	public function bindModel(
			ReflectionClass $class, IController &$controller, HashMap $parameters);
}