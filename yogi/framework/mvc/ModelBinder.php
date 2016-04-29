<?php
namespace yogi\framework\mvc;
use yogi\framework\mvc\interfaces\IModelBinder;
use yogi\framework\mvc\interfaces\IController;
use yogi\framework\utils\HashMap;
use yogi\framework\utils\interfaces\IAnnotationHandler;
use yogi\framework\mvc\DataAnnotations;
use yogi\framework\utils\Regexp;
use yogi\framework\utils\handlers\interfaces\IFormFieldHandler;

use \ReflectionProperty;
use \ReflectionClass;

class ModelBinder implements IModelBinder {
	
	private $annotationHandler;
	
	/**
	 * [Inject(yogi\framework\utils\handlers\interfaces\IFormFieldHandler)]
	 * @var IFormFieldHandler
	 */
	private $formFieldHandler;
	
	private $currentFields;
	
	public function __construct(IAnnotationHandler $annotationHandler) {
		$this->annotationHandler = $annotationHandler;
	}
	
	public function setFormFieldHandler(IFormFieldHandler $handler) {
		$this->formFieldHandler = $handler;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see IModelBinder::bindModel()
	 */
	public function bindModel(ReflectionClass $class, IController &$controller, HashMap $parameters) {

		$obj = $class->newInstance();
		$modelState = &$controller->getModelState();

		$this->currentFields = $parameters->getIterator();
		foreach($this->currentFields as $name => $value) {
			if($class->hasProperty($name)) {

				$prop = $class->getProperty($name);

				// Validate property through Annotation
				if(!$this->validateProperty($prop, $value, $errorMsg)) {
					$modelState->isValid(false);
					$modelState->setErrorMessageFor($name, $errorMsg);
					$modelState->setIsInValid($name);
				}

				if($prop->isPublic()) {
					$prop->setValue($obj, $value);
				} else {
					if($class->hasMethod("set".ucfirst($name))) {
						$setter = $class->getMethod("set".ucfirst($name));
						$setter->invokeArgs($obj, array($value));
					}
				}
			}
		}

		return $obj;
	}
	
	private function validateProperty(ReflectionProperty $prop, $value, &$errorMsg) {
		$annotations = $this->annotationHandler->getAnnotations($prop);

		if($this->annotationHandler->hasAnnotation('FormField', $prop)) {

		}

		$passed = true;
		$errorMsg = "";
		foreach($annotations as $annotation) {
			$annotation = $this->annotationHandler->parseAnnotation($annotation);

			if($annotation[0] == DataAnnotations::ErrorMessage) {
				$errorMsg = $annotation[1];
			}

			if($annotation[0] == DataAnnotations::Required && empty($value)) {
				$passed = false;

			} else if($annotation[0] == DataAnnotations::ValidationPattern) {
				$pattern = "";
				if(isset($annotation[1])) {
					$pattern = $annotation[1];
				}
				$regexp = new Regexp($pattern);
				if(!$regexp->match($value) ) {
					$passed = false;
				}

			} else if($annotation[0] == DataAnnotations::StringLength) {
				$maxLength = 0;
				if(isset($annotation[1]['MaxLength'])) {
					$maxLength = $annotation[1]['MaxLength'];
				}
				$minLength = 0;
				if(isset($annotation[1]['MinLength'])) {
					$minLength = $annotation[1]['MinLength'];
				}
				if($maxLength > 0) {
					if((strlen($value) < $minLength || strlen($value) > $maxLength)) {
						$passed = false;
					}
				} else {
					if(strlen($value) < $minLength) {
						$passed = false;
					}
				}
			} else if($annotation[0] == DataAnnotations::MatchField) {
				if($value != $this->currentFields[$annotation[1][0]]) {
					$passed = false;
				}
			}
		}

		return $passed;
	}
}