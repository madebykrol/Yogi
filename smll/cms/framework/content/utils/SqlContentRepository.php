<?php
namespace smll\cms\framework\content\utils;

use smll\cms\framework\content\PropertyCriteria;

use smll\cms\framework\content\interfaces\IContent;

use smll\cms\framework\content\utils\interfaces\IContentRepository;
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

class SqlContentRepository implements IContentRepository
{
    
    private $db;
    private $connectionString;
    private $settings;
    private $contentTypeRepository;
    private $fieldFactory;
    
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
    
    public function addContent(IContent $content)
    {
        $db = $this->db;
        
        $reflectionPage = new ReflectionClass($content);
        $typeId = $this->contentTypeRepository->getContentTypeId($reflectionPage->getShortName());
        // Get Datatype..
        $properties = $this->contentTypeRepository->getContentDataFields($typeId);
        
        $values = array();
        foreach ($reflectionPage->getProperties(\ReflectionProperty::IS_PUBLIC)
                as $prop) {
             
            $value = $prop->getValue($content);
             
            $values[$prop->getName()] = $value;
        }
        
        $contentVals = array();
        $contentVals['type'] = 'ContentData';
        
         
        $contentVals['published'] = true;
       
        
        $contentVals['visibleInMenu'] = (bool)false;
        
        if (isset($values['id'])) {
            $contentVals['id'] = $values['id'];
            unset($values['id']);
        }
        
        if (isset($values['ident'])) {
            $contentVals['ident'] = $values['ident'];
            unset($values['ident']);
        }
        
        if (isset($values['authorName'])) {
            $contentVals['authorName'] = $values['authorName'];
            unset($values['authorName']);
        }
        
        if (isset($values['title'])) {
            $contentVals['title'] = $values['title'];
            unset($values['title']);
        }
        
        
        $contentVals['parentId'] = null;
        unset($values['parentId']);
        
        if (isset($values['peerOrderWeight'])) {
            $contentVals['peerOrderWeight'] = $values['peerOrderWeight'];
            unset($values['peerOrderWeight']);
        }
        
        $contentVals['editDate'] = date('Y-m-d H:i:s');
        
        $contentVals['fkContentTypeId'] = $typeId;
        
        // See if page already exists or if it's a completly new page
        if ((!isset($content->id) || $content->id == "") && (!isset($content->ident) || $content->ident == "")) {
            // Assume it's a new page.
        
            $contentVals['id'] = null;
            $contentVals['ident'] = Guid::createNew();
            $contentVals['creationDate'] = date('Y-m-d H:i:s');
             
             
            // Validate data for it's field.
             
            $db->insert('content', $contentVals);
            $contentId = $db->getLastInsertId();
             
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
        
                        $this->setPropertyForContent($contentId, $prop);
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
                    $this->setPropertyForContent($contentId, $prop);
                }
            }
             
            $content->id = $contentId;
            $content->ident = $contentVals['ident'];
             
        } else {
            // assume it's a pre existing page, ready for update
            // Validate data for it's field.
             
             
            $db->where(array('id','=', $content->id));
            $db->where(array('ident','=', $content->ident));
             
            unset($contentVals['id']);
            unset($contentVals['ident']);
             
            $db->update('content', $contentVals);
            $db->clearCache();
            $db->flushResult();
             
            $contentId = $content->id;
        
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
                     
                    $db->where(array('fkContentId', '=', $contentId));
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
        
                        $this->setPropertyForContent($contentId, $prop);
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
                    $this->setPropertyForContent($contentId, $prop);
                }
        
            }
             
        }
        
        
        $db->flushResult();
        $db->clearCache();
        
        return $content;
    }
    
    public function getContentRaw(Guid $id)
    {
    
        $db = $this->db;
        $result = null;
    
        // Fetch "page".
        if ($id instanceof Guid) {
            $result = $db->query('SELECT * FROM content WHERE ident = ?', $id);
        } else if (is_numeric($id)) {
            $result = $db->query('SELECT * FROM content WHERE id = ?', $id);
        }
    
        $content = (array)$result[0];
    
        // Fetch page_definitions
        $properties = $db->query('SELECT
                pd.name as name,
                linkGuid,
                longString,
                number,
                contentRef,
                date,
                string,
                boolean,
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
                WHERE fkContentId = ? ORDER BY peerOrderWeight', $content['id']);
    
    
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
            
            if( $propName == "id") {
                $props[$propName][] = $result[0]->id;
            } else if ($propName == "ident") {
                $props[$propName][] = $result[0]->ident;
            } else {
                $props[$propName][] = $field->processData($property[$datatype]);
            }
        }
    
        foreach ($props as $name => $prop) {
            if (count($prop) > 1) {
                $content[$name] = $prop;
            } else {
                $content[$name] = $prop[0];
            }
        }
    
        return $content;
    }
    
    public function getContent(Guid $content)
    {
        $data = $this->getContentRaw($content);
        $type = $data['fkContentTypeId'];
        
        $db = $this->db;
        $result = $db->query('SELECT file FROM content_type WHERE id = ?', $type);
        if (count($result) > 0) {
            $type = new \ReflectionClass(
                    str_replace(array('/', '.php'), array('\\', ''), $result[0]->file));
             
            $contentData = $type->newInstance();
             
            foreach ($type->getProperties() as $name => $prop) {
                if (isset($data[$prop->getName()])) {
                    $prop->setValue($contentData, $data[$prop->getName()]);
                }
            }
             
            $db->flushResult();
            $db->clearCache();
             
            return $contentData;
        } else {
            return null;
        }
    }
    
    
    public function setPropertyForContent($contentId, IContentProperty $prop)
    {
        $db = $this->db;
        if (is_null($prop->getValue())) {
            if ($prop->ignoreIfNull()) {
                return;
            }
        }
        $db->insert('property', array(
                $prop->getDataType()        => $prop->getValue(),
                'fkContentId'               => $contentId,
                'fkContentDefinitionId'     => $prop->getPageDefinitionId(),
                'index'                     => $prop->getIndex()));
        $db->clearCache();
        $db->flushResult();
    }
    
    public function removePropertyForContent($contentId, IContentProperty $prop)
    {
        $db = $this->db;
        
        $db->where(array('fkContentId', '=', $contentId));
        $db->where(array('fkContentDefinitionId', '=', $prop->getPageDefinitionId()));
        $db->where(array('index', '=', $prop->getIndex()));
        
        $db->delete('property');
        
        $db->clearCache();
        $db->flushResult();
    }
    
    public function updatePropertyForContent($contentId, IContentProperty $prop)
    {
        $db = $this->db;
    
        $db->where(array('fkContentId', '=', $contentId));
        $db->where(array('fkContentDefinitionId', '=', $prop->getPageDefinitionId()));
        $db->where(array('index', '=', $prop->getIndex()));
    
        $db->update('property', array($prop->getDataType() => $prop->getValue()));
    
        $db->clearCache();
        $db->flushResult();
    }
    
    public function findContentWithCriteria(IPropertyCriteriaCollection $criteriaCollection)
    {
        $contentCriteria = array();
        
        $where = "";
        foreach ($criteriaCollection->getIterator() as $criteria) {
            $condition = "=";
            $value = $criteria->getValue();
            
            switch ($criteria->getCondition()) {
                case PropertyCriteria::CRITERIA_COMPARE_CONDITION_EQUALS :
                    $condition = '=';
                    break;
                case PropertyCriteria::CRITERIA_COMPARE_CONDITION_IN : 
                    $condition = 'in';
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
                
               $where .= "c.".$criteria->getName()." ".$condition." ".$this->getValuePlaceHolder($criteria->getValue());
               $value = 
               $contentCriteria[] = $criteria->getValue();
            } else if ($criteria->getType() == PropertyCriteria::CRITERIA_PROPERTY_CONTENT_TYPE_NAME) {
                if($where != "") {
                    if($criteria->isRequired()) {
                        $where.= " AND ";
                    } else {
                        $where .= " OR ";
                    }
                }
                
                $where .= "c_t.name"." ".$condition." ".$this->getValuePlaceHolder($criteria->getValue());
                $contentCriteria[] = $criteria->getValue();
            } else {
               
                if($where != "") {
                    if($criteria->isRequired()) {
                        $where.= " AND ";
                    } else {
                        $where .= " OR ";
                    }
                }
                $where .= '('.'c_d.name = ? AND p.'.$criteria->getType().' = '.$this->getValuePlaceHolder($criteria->getValue()).')';
                $contentCriteria[] = $criteria->getName();
                $contentCriteria[] = $criteria->getValue();
            }
        }
        
        $db = $this->db;
        
        $method = new \ReflectionMethod('smll\framework\io\db\DB', 'query');
        
        
        $query = array();
        $query[] = "SELECT DISTINCT(c.id), c.ident FROM content AS c 
                JOIN gamescom.property AS p 
                    ON (p.fkContentId = c.id) 
                JOIN gamescom.content_definition as c_d 
                    ON (p.fkContentDefinitionId = c_d.id)
                JOIN content_type AS c_t
                    ON (c.fkContentTypeId = c_t.id)
                WHERE ".$where." AND c.type = 'ContentData'";
        
        foreach($contentCriteria as $criteria) {
            $query[] = $criteria;
        }
        
        $contentResult = $method->invokeArgs($db, $query);
        
        if(is_array($contentResult) && count($contentResult) > 0) {
            foreach ($contentResult as $index => $content) {
                $contentResult[$index] = $this->getContent(Guid::parse($content->ident));
            }
            
            return new ContentDataCollection($contentResult);
        } else {
            return false;
        }
    }
    
    
    private function getValuePlaceHolder($value) {
        $placeholder = "";
        if(is_array($value)) {
            $placeholder .= "(";
            
            for($i = 0; $i < count($value); $i++) {
                $placeholder .= "?";
                if($i < (count($value)-1)) {
                    $placeholder .=", ";
                }
            }
            $placeholder .= ")";
        } else {
            $placeholder = "?";
        }
        
        return $placeholder;
    }
}