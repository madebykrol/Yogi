<?php
namespace smll\framework\mvc;
use smll\framework\mvc\interfaces\IModelState;
use smll\framework\utils\HashMap;

class ModelState implements IModelState {

    private $modelState = true;
    private $errorMessages = null;

    public function __construct() {
        $this->errorMessages = new HashMap();
    }

    public function isValid($state = null) {
        if(is_bool($state)) {
            $this->modelState = $state;
        }
        return $this->modelState;
    }

    public function setErrorMessageFor($name, $message) {
        $this->errorMessages->add($name, $message);
    }

    public function getErrorMessageFor($name) {
        return $this->errorMessages->get($name);
    }

    public function getErrorStack() {
        return $this->errorMessages;
    }

}