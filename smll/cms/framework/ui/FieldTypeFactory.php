<?php
namespace smll\cms\framework\ui;

use smll\cms\framework\ui\interfaces\IFieldInjector;

use smll\cms\framework\ui\interfaces\IFieldInjecter;

use smll\framework\di\interfaces\IDependencyContainer;

use smll\cms\framework\content\files\interfaces\IFileRepository;

use smll\framework\utils\HashMap;

use smll\cms\framework\content\taxonomy\interfaces\ITaxonomyRepository;

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
	
	/**
	 * 
	 * @var IFileRepository
	 */
	private $fileRepository;
	
	/**
	 * @var IDe
	 */
	private $container;

	/**
	 * 
	 * @var ArrayList
	 */
	private $fieldInjecters;
	
	
	
	public function __construct(
			IAnnotationHandler $annotationHandler, 
			IFileRepository $fileRepository,
			IFileUploadManager $manager,
			ITaxonomyRepository $taxonomyRepository) {
		$this->annotationHandler = $annotationHandler;
		$this->fileRepository = $fileRepository;
		$this->uploadManager = $manager;
		$this->taxonomyRepository = $taxonomyRepository;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\cms\framework\ui\interfaces\IFieldTypeFactory::buildFieldSettings()
	 */
	public function buildFieldSettings(HashMap $settings) {
		/**
		 * Currently a VERY hacky implementation of this functionallity
		 * @todo Revisit this to make it a bit better.
		 * 
		 */

		$tmpSettings =  new HashMap();
		foreach($settings->getIterator() as $setting => $value) {
			// Ignore primary and foreign keys
			if(
					$setting != "id" 
					&& $setting != "fkPageTypeId" 
					&& $setting != "fkPageTypeDefinitionId"
					&& $setting != "fkPageDefinitionTypeRenderer"
					&& $setting != "name") {
				
				$tmpSettings->add($setting, $value);
			}
		}
		
		return $tmpSettings;
	}
	
	public function buildFieldType($type, HashMap $settings = null) {
		$rClass = new ReflectionClass($type);
		$instance = $rClass->newInstance();
		// prototype;
		$instance->setFieldSettings(new HashMap());
		
		$rendererClass =  $this->annotationHandler->getAnnotation('DefaultRenderer', $rClass);
		$renderer = null;
		if(isset($rendererClass)) {
			$renderer = new ReflectionClass($rendererClass[1][0]);
			$renderer = $renderer->newInstance();
		}
		
		// If it's a "file input field" we need to provide a IFileUploadManager
		// To the field
		if($rClass->implementsInterface(
				'smll\cms\framework\content\fieldtype\interfaces\IFileFieldType')) {
			$rMethod = $rClass->getMethod('setFileUploadManager');
			$rMethod->invoke($instance, $this->uploadManager);
			
			$rMethod = $rClass->getMethod('setFileRepository');
			$rMethod->invoke($instance, $this->fileRepository);
			
		}
		
		// If it's a "Taxonomy input field" we need to provide a ITaxonomyRepository
		
		if($rClass->implementsInterface(
				'smll\cms\framework\content\fieldtype\interfaces\ITaxonomyFieldType')) {
			$rMethod = $rClass->getMethod('setTaxonomyRepository');
				
			$rMethod->invoke($instance, $this->taxonomyRepository);
		}
		
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
	
	public function attachFieldInjector(IFieldInjector $injector) {}
	
}