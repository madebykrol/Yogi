<?php
namespace smll\cms\framework\mvc;
use smll\cms\framework\content\files\interfaces\IFileRepository;

use smll\framework\io\file\FileReference;

use smll\framework\utils\Guid;

use smll\cms\framework\content\fieldtype\interfaces\IFileFieldType;

use smll\cms\framework\ui\interfaces\IFieldTypeFactory;

use smll\cms\framework\content\utils\interfaces\IContentRepository;

use smll\framework\mvc\interfaces\IModelBinder;
use smll\framework\mvc\interfaces\IController;
use smll\framework\utils\HashMap;
use smll\framework\utils\interfaces\IAnnotationHandler;
use smll\framework\mvc\DataAnnotations;
use smll\framework\utils\Regexp;
use smll\framework\utils\handlers\interfaces\IFormFieldHandler;
use \ReflectionClass;
use \ReflectionProperty;
use \ReflectionMethod;

class CmsModelBinder implements IModelBinder {
	private $annotationHandler;
	
	/**
	 * [Inject(smll\framework\utils\handlers\interfaces\IFormFieldHandler)]
	 * @var IFormFieldHandler
	 */
	private $formFieldHandler;
	
	private $currentFields;
	
	/**
	 * [Inject(smll\cms\framework\content\utils\interfaces\IContentRepository)]
	 * @var IContentRepository
	 */
	private $contentRepository;
	
	/**
	 * [Inject(smll\cms\framework\content\files\interfaces\IFileRepository)]
	 * @var IFileRepository
	 */
	private $fileRepository;
	
	/**
	 * [Inject(smll\cms\framework\ui\interfaces\IFieldTypeFactory)]
	 * @var IFieldTypeFactory
	 */
	private $fieldTypeFactory;
	
	public function __construct(IAnnotationHandler $annotationHandler, 
		IFieldTypeFactory $fieldTypeFactory) {
		$this->annotationHandler = $annotationHandler;
		$this->fieldTypeFactory = $fieldTypeFactory;
	}
	
	public function setFormFieldHandler(IFormFieldHandler $handler) {
		$this->formFieldHandler = $handler;
	}
	
	public function setContentRepository(IContentRepository $repo) {
		$this->contentRepository = $repo;
	}
	
	public function setFileRepository(IFileRepository $repo) {
		$this->fileRepository = $repo;
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
				
				if($this->annotationHandler->hasAnnotation('ContentField', $prop)) {
					$annotation = $this->annotationHandler->getAnnotation('ContentField', $prop);
					$contentField = $this->contentRepository->getPageDefinitionTypeByName($annotation[1]['Type']);
					
					$settings = $this->fieldTypeFactory->buildFieldSettings(new HashMap());
					
					$field = $this->fieldTypeFactory->buildFieldType($contentField->assembler, $settings);
					$field->setName($name);
					
					if(!$field->validateField($value)) {
						$modelState->isValid(false);
						$errorMsg = $field->getErrorMessage();
						$modelState->setErrorMessageFor($name, $errorMsg);
						$prop->setValue($obj, $value);
					} else {
						if($field instanceof IFileFieldType) {
							
							foreach($value as $index => $val) {
								if($val != null) {
									// Don't process guids... They are already set.
									if(($fileGuid = Guid::parse($val)) == null) {
										
										$value[$index] = $this->processFileReference($val, $field, $index);
									}
								}
							}
							
						} else {
							
							$value = $field->processData($value);
						}
						
						$prop->setValue($obj, $value);
					}
					
				} else {

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
		}
		
		return $obj;
	
	}
	
	private function processFileReference($value, IFileFieldType $field, $index) {
		
		if(($guid = Guid::parse($value[$index])) != null) {
			// get FileReference
			$value = $this->fileRepository->getFileReference($guid);
		} else {
			$value = $field->processData($value, $index);
		}
		
		return $value;
	}
	
	private function validateProperty(ReflectionProperty $prop, $value, &$errorMsg) {
		$annotations = $this->annotationHandler->getAnnotations($prop);
		
		if($this->annotationHandler->hasAnnotation('FormField', $prop)) {
				
				/**
				 * @todo fix validation
				 */
				
		}
		$passed = true;
		foreach($annotations as $annotation) {
			$annotation = $this->annotationHandler->parseAnnotation($annotation);
			$errorMsg = "";
				
			if($annotation[0] == DataAnnotations::ErrorMessage) {
				$errorMsg = $annotation[1];
			}
				
			if($annotation[0] == DataAnnotations::Required && empty($value)) {
				$errorMsg = "*";
				$passed = false;
	
			} else if($annotation[0] == DataAnnotations::ValidationPattern) {
				$pattern = "";
	
				if(isset($annotation[1]['Pattern'])) {
					$pattern = $annotation[1]['Pattern'];
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
					$errorMsg = "*";
					$passed = false;
				}
			}
		}
		return $passed;
	}
}