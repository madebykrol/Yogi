<?php
namespace smll\framework\mvc\interfaces;
use smll\framework\mvc\interfaces\IController;
use smll\framework\utils\HashMap;
interface IModelBinder {
    public function bindModel(
            \ReflectionClass $class, IController &$controller, HashMap $parameters);
}