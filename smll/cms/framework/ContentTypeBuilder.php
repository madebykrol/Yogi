<?php
namespace smll\cms\framework;
use smll\cms\framework\content\interfaces\IContent;

use smll\cms\framework\interfaces\IContentTypeBuilder;

use smll\cms\framework\content\utils\interfaces\IContentTypeRepository;

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
use smll\cms\framework\content\utils\interfaces\IPageDataRepository;
use smll\cms\framework\content\PageData;

class ContentTypeBuilder implements IContentTypeBuilder
{

    private $annotationHandler;
    private $contentTypeRepository;

    public function __construct(IAnnotationHandler $annotationHandler, IContentTypeRepository $pageDataRepository)
    {
        $this->annotationHandler = $annotationHandler;
        $this->contentTypeRepository = $pageDataRepository;
    }

    public function buildPageType(IContent $pageType, $dataType = 'PageData')
    {
        $this->rebuildPageType($pageType, $dataType);
    }
    public function rebuildPageType(IContent $pageType, $dataType = 'Pagedata')
    {
        $rPageType = new \ReflectionClass(get_class($pageType));

        $type = $rPageType->getShortName();

        $file = str_replace(array('\\'), '/', get_class($pageType)).".php";

        $renderer = "";

        $contentTypeAnnotation = $this->annotationHandler->getAnnotation('ContentType', $rPageType);
        $controller = str_replace('Page', '', $type);

        $permissionAnnotations = $this->annotationHandler->getAnnotation('Permissions', $rPageType);

        $permissions = $permissionAnnotations[1];

        $pageTypeId = $this->contentTypeRepository->addContentType($type, $file, $controller, $contentTypeAnnotation[1]['DisplayName'], $permissions, $contentTypeAnnotation[1]['Description'], Guid::parse($contentTypeAnnotation[1]['Guid']), $dataType);

        foreach ($rPageType->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            $field = new PageDataField();

            $annotation = $this->annotationHandler->getAnnotation('ContentField', $prop);

            $annotation = $annotation[1];

            $defTypeId = null;
            $defType  = "Text";
            if (isset($annotation['Type'])) {
                $defType = $annotation['Type'];
            }
            print $defType;
            $defType = $this->contentTypeRepository->getContentDefinitionTypeByName($defType);
            
            $field->setDefinitionTypeId($defType->id);
            $field->setFieldName($prop->getName());

            $pageDef = $this->contentTypeRepository->getContentDefinitionByName($prop->getName(), $pageTypeId);
            if ($pageDef != null) {
                $field->setDefinitionId($pageDef->id);
            }

            if (isset($annotation['WeightOrder'])) {
                $field->setWeightOrder($annotation['WeightOrder']);
            }

            if (isset($annotation['DisplayName'])) {
                $field->setDisplayName($annotation['DisplayName']);
            }

            if (isset($annotation['Required'])) {
                $field->isRequired(Boolean::parseValue($annotation['Required']));
            }

            if (isset($annotation['Searchable'])) {
                $field->isSearchable(Boolean::parseValue($annotation['Searchable']));
            }

            if (isset($annotation['Tab'])) {
                $field->setTab($annotation['Tab']);
            }
             
            $pageDefinitionId = $this->contentTypeRepository->addContentTypeField($pageTypeId, $field);
             
            if ($this->annotationHandler->hasAnnotation('Renderer', $prop)) {
                $renderer = $this->annotationHandler->getAnnotation('Renderer', $prop);
                $renderer = $renderer[1][0];

                $this->contentTypeRepository->setFieldRenderer($pageTypeId, $pageDefinitionId, $renderer);
            }

        }

    }
    
    public function findPageTypes($pageTypes = 'PageData')
    {
        $dir = 'pages';
        $search = "Page";
        
        switch ($pageTypes) {
            case 'PageData' :
                $dir = 'pages';
                $search = "Page";
                break;
                
            case 'BlockData' :
                $dir = 'blocks';
                $search = "Block";
                break;
        
            case 'ContentData' :
                $dir = 'content';
                $search = "Content";
                break;
        }
        
        
        $dir = new Dir('src/content/'.$dir);
        $result = new ArrayList();
        $dir->searchRecursive(new Regexp('.+?'.$search.'\\.php'), $result);
        $pageTypes = new HashMap();
        foreach ($result->getIterator() as $index => $pageType) {
            
            $class = new \ReflectionClass(str_replace(array('/', '.php'), array('\\', ''), $pageType));
            $instance = null;
            if (!$class->isAbstract()) {

                $instance = $class->newInstance();
                
                if ($instance instanceof IContent) {
                     
                    $pageTypes->add($index, (object)array('name' => $class->getShortName()));
                }
            }
        }

        return $pageTypes;
    }

    public function findPageType($type, $pageTypes = 'PageData')
    {
        $dir = 'pages';
        
        print_r($pageTypes);
        switch ($pageTypes) {
            case 'PageData' :
                $dir = 'pages';
                break;
            case 'BlockData' :
                $dir = 'blocks';
                break;
            
            case 'ContentData' :
                $dir = 'content';
                break;
        } 
        $dir = new Dir('src/content/'.$dir);
        
        print_r($dir);
        $result = new ArrayList();
        $dir->searchRecursive(new Regexp($type.'\\.php'), $result);
        
        
        
        $class = str_replace(array('/', '.php'), array('\\', ''), $result->get(0));
        print_r($class);
        return $class;
    }
}