<?php
namespace smll\cms\framework\content\utils;

use smll\cms\framework\content\utils\interfaces\IContentTypeRepository;
use smll\framework\utils\Boolean;
use smll\cms\framework\ui\interfaces\IFieldTypeFactory;
use smll\cms\framework\ui\FieldTypeFactory;
use smll\cms\framework\content\ContentProperty;
use smll\cms\framework\content\interfaces\IContentProperty;
use smll\cms\framework\content\fieldtype\interfaces\IFileFieldType;
use smll\cms\framework\content\interfaces\IPropertyCriteriaCollection;
use smll\cms\framework\content\interfaces\IPageReference;
use smll\framework\io\file\FileReference;
use smll\framework\io\file\interfaces\IFileReference;
use smll\framework\utils\ArrayList;
use smll\cms\framework\content\fieldtype\interfaces\IFieldType;
use smll\cms\framework\content\PageDataField;
use smll\framework\utils\HashMap;
use smll\cms\framework\content\interfaces\IPageDataField;
use smll\cms\framework\content\utils\interfaces\IPageDataRepository;
use smll\cms\framework\content\utils\interfaces\ICrudListener;
use smll\framework\settings\interfaces\ISettingsRepository;
use smll\cms\framework\content\interfaces\IPageData;
use smll\cms\framework\content\PageReference;
use smll\framework\utils\Guid;
use smll\framework\io\db\DB;
use \ReflectionClass;


class SqlPageDataRepository implements IPageDataRepository
{

    private $settings;
    private $connectionString;
    private $db;
    private $fieldFactory;
    private $contentTypeRepository;
    
    public function __construct(ISettingsRepository $settings,
            IFieldTypeFactory $fieldFactory,
            IContentTypeRepository $contentTypeRepository)
    {
        $this->settings = $settings;

        $connectionStrings = $this->settings->get('connectionStrings');
        $this->connectionString = $connectionStrings['Default']['connectionString'];
        $this->db = new DB($this->connectionString);
        $this->contentTypeRepository = $contentTypeRepository;
        $this->fieldFactory = $fieldFactory;
    }


    public function addPage(IPageData $page)
    {
        $db = $this->db;

        $reflectionPage = new ReflectionClass($page);
        $typeId = $this->contentTypeRepository->getContentTypeId($reflectionPage->getShortName());
        // Get Datatype..
        $properties = $this->contentTypeRepository->getContentDataFields($typeId);

        $values = array();
        foreach ($reflectionPage->getProperties(\ReflectionProperty::IS_PUBLIC) 
                as $prop) {
             
            $value = $prop->getValue($page);
             
            $values[$prop->getName()] = $value;
        }

        $pageVals = array();
        $pageVals['type'] = 'PageData';

         
        $pageVals['published'] = (bool)$values['published'];
        unset($values['published']);

        $pageVals['visibleInMenu'] = (bool)$values['visibleInMenu'];
        unset($values['visibleInMenu']);
         
        $pageVals['id'] = $values['id'];
        unset($values['id']);

        $pageVals['ident'] = $values['ident'];
        unset($values['ident']);

        $pageVals['authorName'] = $values['authorName'];
        unset($values['authorName']);


        $pageVals['title'] = $values['title'];
        unset($values['title']);


        $pageVals['parentId'] = $values['parentId'];
        unset($values['parentId']);

        $pageVals['externalUrl'] = $values['externalUrl'];
        unset($values['externalUrl']);

        if ($pageVals['externalUrl'] == "") {
            $pageVals['externalUrl'] = 'pages/'.$pageVals['ident'];
        }

        $pageVals['peerOrderWeight'] = $values['peerOrderWeight'];
        unset($values['peerOrderWeight']);

        $pageVals['editDate'] = date('Y-m-d H:i:s');

        $pageVals['fkContentTypeId'] = $typeId;

        // See if page already exists or if it's a completly new page
        if ((!isset($page->id) || $page->id == "") && (!isset($page->ident) || $page->ident == "")) {
            // Assume it's a new page.

            $pageVals['id'] = null;
            $pageVals['ident'] = Guid::createNew();
            $pageVals['creationDate'] = date('Y-m-d H:i:s');
             
             
            // Validate data for it's field.
             
            $db->insert('content', $pageVals);
            $pageId = $db->getLastInsertId();
             
            // Store the data
             
            foreach ($values as $field => $val) {

                $defId = $properties[$field]->getDefinitionId();
                $fieldType = $properties[$field]->getFieldType();
                
                $fileField = false;
                if ($fieldType instanceof IFieldType) {
                    $datatype = $fieldType->getPropertyDataType();
                } else {
                    $datatype = "string";
                }
                
                if ($fieldType instanceof IFileFieldType) {
                    $fileField = true;
                }
                
                if (is_array($val)) {
                    foreach ($val as $index => $part) {

                        $prop = new ContentProperty();
                        if (!empty($part)) {
                            $prop->setValue($part);
                        }
                        $prop->setIndex($index);
                        $prop->setPageDefinitionId($defId);
                        $prop->setDataType($datatype);

                        if ($fileField) {
                            $prop->ignoreIfNull(true);
                        }

                        $this->setPropertyForPage($pageId, $prop);
                    }
                } else {
                    $prop = new ContentProperty();
                    $val = trim($val);
                    if (!empty($val)) {
                        $prop->setValue($val);
                    }
                    $prop->setIndex(0);
                    $prop->setPageDefinitionId($defId);
                    $prop->setDataType($datatype);
                    if ($fileField) {
                        $prop->ignoreIfNull(true);
                    }
                    $this->setPropertyForPage($pageId, $prop);
                }
            }
             
            $page->id = $pageId;
            $page->ident = $pageVals['ident'];
             
        } else {
            // assume it's a pre existing page, ready for update
            // Validate data for it's field.
             
             
            $db->where(array('id','=', $page->id));
            $db->where(array('ident','=', $page->ident));
             
            unset($pageVals['id']);
            unset($pageVals['ident']);
             
            $db->update('content', $pageVals);
            $db->clearCache();
            $db->flushResult();
             
            $pageId = $page->id;

            // Store the data
             
            foreach ($values as $field => $val) {

                $defId = $properties[$field]->getDefinitionId();
                $fieldType = $properties[$field]->getFieldType();
                $fileField = false;
                if ($fieldType instanceof IFieldType) {
                    $datatype = $fieldType->getPropertyDataType();
                } else {
                    $datatype = "string";
                }

                if (!$fieldType instanceof IFileFieldType) {
                     
                    $db->where(array('fkContentId', '=', $pageId));
                    $db->where(array('fkContentDefinitionId', '=', $defId));
                     
                    $db->delete('property');
                    $db->clearCache();
                     
                } else {
                    $fileField = true;
                }

                if (is_array($val)) {
                    foreach ($val as $index => $part) {

                        $prop = new ContentProperty();
                        if (!empty($part)) {
                            $prop->setValue($part);
                        }
                        $prop->setIndex($index);
                        $prop->setPageDefinitionId($defId);
                        $prop->setDataType($datatype);

                        if ($fileField) {
                            $prop->ignoreIfNull(true);
                        }

                        $this->setPropertyForPage($pageId, $prop);
                    }
                } else {
                    $prop = new ContentProperty();
                    $val = trim($val);
                    if (!empty($val)) {
                        $prop->setValue($val);
                    }
                    $prop->setIndex(0);
                    $prop->setPageDefinitionId($defId);
                    $prop->setDataType($datatype);
                    if ($fileField) {
                        $prop->ignoreIfNull(true);
                    }
                    $this->setPropertyForPage($pageId, $prop);
                }

            }
             
        }


        $db->flushResult();
        $db->clearCache();


        return $page;
    }

    public function setPropertyForPage($pageId, IContentProperty $prop)
    {
        $db = $this->db;
        if (is_null($prop->getValue())) {
            if ($prop->ignoreIfNull()) {
                return;
            }
        }
        $db->insert('property', array(
                $prop->getDataType()        => $prop->getValue(),
                'fkContentId'                  => $pageId,
                'fkContentDefinitionId'        => $prop->getPageDefinitionId(),
                'index'                     => $prop->getIndex()));
        $db->clearCache();
        $db->flushResult();
    }

    public function removePropertyForPage($pageId, IContentProperty $prop)
    {
        $db = $this->db;
        $db->where(array('fkContentId', '=', $pageId));
        $db->where(array('fkContentDefinitionId', '=', $prop->getPageDefinitionId()));
        $index = $prop->getIndex();
        if($index != null) {
            $db->where(array('index', '=', $prop->getIndex()));
        }
        $db->delete('property');
        $db->clearCache();
    }


    public function getRootPage()
    {
        $rootPageRef = new PageReference($this);
        $db = $this->db;
        $childrenPages = $db->query("SELECT * FROM content WHERE parentId = ? ORDER BY peerOrderWeight AND type = ?", 0, 'PageData');

        $children = new ArrayList();
        if (is_array($childrenPages)) {
            foreach ($childrenPages as $child) {
                $children->add($this->getPageReference($child->id));
            }
        }

        $rootPageRef->setChildren($children);

        $db->flushResult();
        $db->clearCache();

        return $rootPageRef;
    }

    /**
     * (non-PHPdoc)
     * @see \smll\cms\framework\content\utils\interfaces\IPageDataRepository::setPageParent()
     */
    public function setPageParent($id, $parentId)
    {
        $db = $this->db;
        if ($id instanceof Guid) {
            $db->query('UPDATE content SET parentId = ? WHERE ident = ? AND type = ?', $parentId, $id, 'PageData');
        } else if(is_numeric($id)){
            $db->query('UPDATE content SET parentId = ? WHERE id = ? AND type = ?', $parentId, $id, 'PageData');
        }
    }

    /**
     * (non-PHPdoc)
     * @see \smll\cms\framework\content\utils\interfaces\IPageDataRepository::setPeerOrderWeight()
     */
    public function setPeerOrderWeight($id, $order)
    {
        $db = $this->db;
        if ($id instanceof Guid) {
            $db->query('UPDATE content SET peerOrderWeight = ? WHERE ident = ? AND type = ?', $order, $id, 'PageData');
        } else if (is_numeric($id)){
            $db->query('UPDATE content SET peerOrderWeight = ? WHERE id = ? AND type = ?', $order, $id, 'PageData');
        }
    }


    public function getPageReference($id)
    {

        $pageRef = new PageReference();
        $db = $this->db;
        $db->flushResult();
        
        if ($id instanceof Guid) {
            $page = $db->query("SELECT * FROM content WHERE ident = ? AND type = ?", $id->getString(), 'PageData');
        } else if (is_numeric($id)) {
            $page = $db->query("SELECT * FROM content WHERE id = ? AND type = ?", $id, 'PageData');
        }
        $page = $page[0];

        if (isset($page)) {
            $pageRef->setIdent($page->ident);
            $pageRef->setId($page->id);
            $pageRef->setTitle($page->title);
            $pageRef->isVisibleInMenu((bool)$page->visibleInMenu);
            $pageRef->setPageTypeId($page->fkContentTypeId);
            $pageRef->setAuthor($page->authorName);
             
            $pageRef->isPublished(Boolean::parseValue($page->published));
            $pageRef->setExternalUrl($page->externalUrl);
            $children = $db->query('SELECT id FROM content WHERE parentId = ? AND type = ? ORDER by peerOrderWeight', $page->id, 'PageData');
            $childPageReferences = new ArrayList();
             
            if (is_array($children)) {
                foreach ($children as $child) {
                    $childPageReferences->add($this->getPageReference($child->id));
                }
            }
             
            $pageRef->setChildren($childPageReferences);
        }

        $db->clearCache();
        $db->flushResult();

        return $pageRef;
    }


    /**
     * (non-PHPdoc)
     * @see \smll\cms\framework\content\utils\interfaces\IPageDataRepository::addPageType()
     */
    



    public function setSettings(ISettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    public function getPageData(IPageReference $page)
    {
        $data = $this->getPageRaw($page->getId());
        $type = $data['fkContentTypeId'];

        $db = $this->db;
        $result = $db->query('SELECT file FROM content_type WHERE id = ?', $type);
        if (count($result) > 0) {
            $type = new \ReflectionClass(
                    str_replace(array('/', '.php'), array('\\', ''), $result[0]->file));
             
            $pageData = $type->newInstance();
             
            foreach ($type->getProperties() as $name => $prop) {
                if (isset($data[$prop->getName()])) {
                    $prop->setValue($pageData, $data[$prop->getName()]);
                }
            }
             
            $db->flushResult();
            $db->clearCache();
             
            $pageData->setPageReference($page);
             
            return $pageData;
        } else {
            return null;
        }
    }

    public function findPageWithCriteria(IPropertyCriteriaCollection $criteriaCollection)
    {
        
        $contentCriteria = array();
        
        $where  = "";
        foreach ($criteriaCollection->getIterator() as $criteria) {
            $condition = "=";
            switch ($criteria->getCondition()) {
                case PropertyCriteria::CRITERIA_COMPARE_CONDITION_EQUALS :
                    $condition = '=';
                    break;
            }
            
            if ($criteria->getType() == PropertyCriteria::CRITERIA_PROPERTY_CONTENT_FIELD) {
               
               if($where != "") {
                   if($criteria->isRequired()) {
                       $where.= " AND ";
                   } else {
                       $where .= " OR ";
                   }
               }
                
               $where .= "c.".$criteria->getName()." ".$condition." ?";
               $contentCriteria[] = $criteria->getValue();
            } else {
               
                if($where != "") {
                    if($criteria->isRequired()) {
                        $where.= " AND ";
                    } else {
                        $where .= " OR ";
                    }
                }
                $where .= '('.'c_d.name = ? AND p.'.$criteria->getType().' = ?'.')';
                $contentCriteria[] = $criteria->getName();
                $contentCriteria[] = $criteria->getValue();
            }
        }
        
        $db = $this->db;
        
        $contentResult = $db->query("
                SELECT c.id, c.ident FROM content AS c 
                JOIN gamescom.property AS p 
                    ON (p.fkContentId = c.id) 
                JOIN gamescom.content_definition as c_d 
                    ON (p.fkContentDefinitionId = c_d.id) 
                WHERE ".$where, $contentCriteria);
        
        if(is_array($contentResult) && count($contentResult) > 0) {
            foreach ($contentResult as $index => $content) {
                $contentResult[$index] = $this->getPageReference(Guid::parse($content->ident));
            }
            
            return $contentResult;
        } else {
            return false;
        }
    }

    public function getPageRaw($id)
    {
        
        $db = $this->db;
        $result = null;

        // Fetch "page".
        if ($id instanceof Guid) {
            $result = $db->query('SELECT * FROM content WHERE ident = ?', $id);
        } else if (is_numeric($id)) {
            $result = $db->query('SELECT * FROM content WHERE id = ?', $id);
        }

        $page = (array)$result[0];

        // Fetch page_definitions

        $properties = $db->query('SELECT
                pd.name as name,
                linkGuid,
                longString,
                number,
                pageRef,
                date,
                string,
                peerOrderWeight,
                pdt.assembler as assembler
                FROM
                content_definition AS pd
                JOIN
                property AS pty ON (
                pty.fkContentDefinitionId = pd.id
        )
                LEFT JOIN
                field_definition_type as pdt ON (
                pdt.id = pd.fkFieldDefinitionTypeId
        )
                WHERE fkContentId = ? ORDER BY peerOrderWeight', $page['id']);


        $props = array();
        foreach ($properties as $property) {
            $property =(array)$property;
            $propName = $property['name'];
            unset($property['name']);
             
            $propPeerWeightOrder = $property['peerOrderWeight'];
            unset($property['peerOrderWeight']);
             
            $assembler = $property['assembler'];
            unset($property['assembler']);
             
            $hashMap = new HashMap();
             
             
             
            $field = $this->fieldFactory->buildFieldType($assembler, $hashMap);
            $datatype = $field->getPropertyDataType();
            if(!isset($props[$propName])) {
                $props[$propName] = array();
            }
            $props[$propName][] = $field->processData($property[$datatype]);
        }

        foreach ($props as $name => $prop) {
            if (count($prop) > 1) {
                $page[$name] = $prop;
            } else {
                $page[$name] = $prop[0];
            }
        }

        return $page;
    }

    public function removePage($id)
    {
        $db = $this->db;
        if ($id instanceof Guid) {
             
            $db->where(array('ident', '=', $id));
            $page = $db->get('content');
            $page = $page[0];
             
            $id = $page->id;
        }

        $db->clearCache();

        $db->where(array('id', '=', $id));
        $db->delete('content');
        $db->clearCache();

        $db->where(array('fkContentId', '=', $id));
        $db->delete('property');

    }

    public function removePageType($id)
    {
    }

    public function setPageCrudListener(ICrudListener $listener)
    {
    }

    
    
    public function publishPage($id)
    {
        $db = $this->db;
        if($id instanceof Guid) {
            $db->where(array('ident', '=', $id));
        } else {
            $db->where(array('id', '=', $id));
        }
        
        $db->where(array('type', '=', 'PageData'));
        
        $db->update('content', array('published' => 1));
    }
    
    public function unpublishPage($id)
    {
        $db = $this->db;
        if($id instanceof Guid) {
            $db->where(array('ident', '=', $id));
        } else {
            $db->where(array('id', '=', $id));
        }
        
        $db->where(array('type', '=', 'PageData'));
        
        $db->update('content', array('published' => 0));
    }
}