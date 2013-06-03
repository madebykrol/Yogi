<?php
class ModelBinder implements IModelBinder {
	
	private $annotationHandler;
	
	public function __construct(IAnnotationHandler $annotationHandler) {
		$this->annotationHandler = $annotationHandler;
	}
	/**
	 * (non-PHPdoc)
	 * @see IModelBinder::bindModel()
	 */
	public function bindModel(ReflectionClass $class, IController &$controller, HashMap $parameters) {
		
		$obj = $class->newInstance();
		$modelState = &$controller->getModelState();
		
		foreach($parameters->getIterator() as $name => $value) {
			if($class->hasProperty($name)) {
				$prop = $class->getProperty($name);
		
				// Validate property through Annotation
				if(!$this->validateProperty($prop, $value, $errorMsg)) {
					$modelState->isValid(false);
					$modelState->setErrorMessageFor($name, $errorMsg);
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
		
		foreach($annotations as $annotation) {
			$annotation = $this->annotationHandler->parseAnnotation($annotation);
			$errorMsg = "";
			
			if(isset($annotation[1]['ErrorMessage'])) {
				$errorMsg = $annotation[1]['ErrorMessage'];
			} 
			
			if($annotation[0] == DataAnnotations::Required && empty($value)) {
				return false;
			} else if($annotation[0] == DataAnnotations::ValidationPattern) {
				$pattern = "";
				if(isset($annotation[1]['Pattern'])) {
					$pattern = $annotation[1]['Pattern'];
				}
				
				$regexp = new Regexp($pattern);
				if(!$regexp->match($value) ) {
					return false;
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
						return false;
					}
				} else {
					if(strlen($value) < $minLength) {
						return false;
					}
				}
			}
		}
		$errorMsg = "";
		return true;
	}
}