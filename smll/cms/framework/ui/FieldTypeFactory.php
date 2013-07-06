<?php
namespace smll\cms\framework\ui;

use smll\framework\io\file\interfaces\IFileUploadManager;

use smll\cms\framework\content\fieldtype\FieldSettings;

use smll\cms\framework\content\utils\interfaces\IContentRepository;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

use smll\cms\framework\ui\fields\PageReferenceRenderer;

use smll\cms\framework\ui\interfaces\IFieldTypeFactory;
use \ReflectionClass;
use \ReflectionParameter;
use \ReflectionProperty;
use \ReflectionMethod;
use smll\framework\utils\interfaces\IAnnotationHandler;

class FieldTypeFactory implements IFieldTypeFactory {
	
	/**
	 * @var IAnnotationHandler
	 */
	private $annotationHandler; 
	
	/**
	 * @var IContentRepository
	 */
	private $contentRepository;
	
	/**
	 * @var IFileUploadManager
	 */
	private $uploadManager;
	
	public function __construct(IAnnotationHandler $annotationHandler, IContentRepository $repository, IFileUploadManager $manager) {
		$this->annotationHandler = $annotationHandler;
		$this->contentRepository = $repository;
		$this->uploadManager = $manager;
	}
	
	public function buildFieldType($type, IFieldSettings $settings = null) {
		$rClass = new ReflectionClass($type);
		$instance = $rClass->newInstance();
		// prototype;
		$instance->setFieldSettings(new FieldSettings());
		
		$rendererClass =  $this->annotationHandler->getAnnotation('DefaultRenderer', $rClass);
		
		
		// If it's a "file input field" we need to provide a IFileUploadManager
		// To the field
		
		if($rClass->implementsInterface('smll\cms\framework\content\fieldtype\interfaces\IFileFieldType')) {
			$rMethod = $rClass->getMethod('setFileUploadManager');
			
			$rMethod->invoke($instance, $this->uploadManager);
		}
		
		$renderer = new PageReferenceRenderer();
		if($rClass->hasProperty('renderer')) {
			
			$prop = $rClass->getProperty('renderer');
			if(!$prop->isPublic()) {
				if($rClass->hasMethod('setRenderer')) {
					$method = $rClass->getMethod('setRenderer');
					$method->invoke($instance, $renderer);
				}
			} else {
				$prop->setValue($instance, $renderer);
			}
		}
		
		// Generate default settings from pagetype annotation.
		if($settings != null) {
			$instance->setFieldSettings($settings);
		}
		return $instance;
	}
	
}