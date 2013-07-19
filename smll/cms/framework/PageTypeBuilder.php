<?php
namespace smll\cms\framework;
use smll\framework\utils\Regexp;

use smll\framework\utils\ArrayList;

use smll\framework\io\file\Dir;

use smll\cms\framework\interfaces\IPageTypeBuilder;
use smll\framework\utils\Boolean;

use smll\cms\framework\content\PageDataField;

use smll\framework\utils\Guid;

use smll\framework\utils\interfaces\IAnnotationHandler;

use smll\cms\framework\content\interfaces\IPageData;
use smll\framework\utils\HashMap;
use smll\cms\framework\content\utils\interfaces\IContentRepository;
use smll\cms\framework\content\PageData;

class PageTypeBuilder implements IPageTypeBuilder {
	
	private $annotationHandler;
	private $contentRepository;
	
	public function __construct(IAnnotationHandler $annotationHandler, IContentRepository $contentRepository) {
		$this->annotationHandler = $annotationHandler;
		$this->contentRepository = $contentRepository;
	}
	
	public function buildPageType(IPageData $pageType){
		$this->rebuildPageType($pageType);
	}
	public function rebuildPageType(IPageData $pageType){
		
		
		$rPageType = new \ReflectionClass(get_class($pageType));
		
		$type = $rPageType->getShortName();
		
		$file = str_replace(array('\\'), '/', get_class($pageType)).".php";
		
		$renderer = "";
		
		$contentTypeAnnotation = $this->annotationHandler->getAnnotation('ContentType', $rPageType);
		$controller = str_replace('Page', '', $type);
		
		$permissionAnnotations = $this->annotationHandler->getAnnotation('Permissions', $rPageType);
		
		$permissions = $permissionAnnotations[1];
		
		$pageTypeId = $this->contentRepository->addPageType($type, $file, $controller, $contentTypeAnnotation[1]['DisplayName'], $permissions, $contentTypeAnnotation[1]['Description'], Guid::parse($contentTypeAnnotation[1]['Guid']));
		
		foreach($rPageType->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
			$field = new PageDataField();
				
			$annotation = $this->annotationHandler->getAnnotation('ContentField', $prop);
		
			$annotation = $annotation[1];
				
			$defTypeId = null;
			$defType  = "Text";
			if(isset($annotation['Type'])) {
				$defType = $annotation['Type'];
			}
				
			$defType = $this->contentRepository->getPageDefinitionTypeByName($defType);
				
			$field->setDefinitionTypeId($defType->id);
			$field->setFieldName($prop->getName());
				
			$pageDef = $this->contentRepository->getPageDefinitionByName($prop->getName(), $pageTypeId);
			if($pageDef != null) {
				$field->setDefinitionId($pageDef->id);
			}
				
			if(isset($annotation['WeightOrder'])) {
				$field->setWeightOrder($annotation['WeightOrder']);
			}
				
			if(isset($annotation['DisplayName'])) {
				$field->setDisplayName($annotation['DisplayName']);
			}
				
			if(isset($annotation['Required'])) {
				$field->isRequired(Boolean::parseValue($annotation['Required']));
			}
				
			if(isset($annotation['Searchable'])) {
				$field->isSearchable(Boolean::parseValue($annotation['Searchable']));
			}
				
			if(isset($annotation['Tab'])) {
				$field->setTab($annotation['Tab']);
			}
			
			$pageDefinitionId = $this->contentRepository->addPageTypeField($pageTypeId, $field);
			
			if($this->annotationHandler->hasAnnotation('Renderer', $prop)) {
				$renderer = $this->annotationHandler->getAnnotation('Renderer', $prop);
				$renderer = $renderer[1][0];
				
				$this->contentRepository->setFieldRenderer($pageTypeId, $pageDefinitionId, $renderer);
			}
		
		}
		
	}
	public function findPageTypes() {
		$dir = new Dir('src/content');
		$result = new ArrayList();
		$dir->searchRecursive(new Regexp('.+?Page\\.php'), $result);
		$pageTypes = new HashMap();
		foreach($result->getIterator() as $index => $pageType) {
			$class = new \ReflectionClass(str_replace(array('/', '.php'), array('\\', ''), $pageType));
			$instance = null;
			if(!$class->isAbstract()) {
				
				$instance = $class->newInstance();
				if($instance instanceof PageData) {
					
					$pageTypes->add($index, (object)array('name' => $class->getShortName()));
				}
			}
		}
		
		return $pageTypes;
	}
	
	public function findPageType($type) {
		$dir = new Dir('src/content');
		$result = new ArrayList();
		$dir->searchRecursive(new Regexp($type.'\\.php'), $result);

		$class = str_replace(array('/', '.php'), array('\\', ''), $result->get(0));
		return $class;
	}
}