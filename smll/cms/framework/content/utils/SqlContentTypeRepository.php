<?php
namespace smll\cms\framework\content\utils;

use smll\cms\framework\content\PageDataField;

use smll\framework\utils\Guid;

use smll\framework\utils\ArrayList;

use smll\framework\io\db\DB;

use smll\cms\framework\ui\interfaces\IFieldTypeFactory;

use smll\framework\settings\interfaces\ISettingsRepository;

use smll\cms\framework\content\interfaces\IPageDataField;

use smll\cms\framework\content\utils\interfaces\IContentTypeRepository;

class SqlContentTypeRepository implements IContentTypeRepository
{
    private $settings;
    private $connectionString;
    private $db;
    private $fieldFactory;
    
    public function __construct(ISettingsRepository $settings,
            IFieldTypeFactory $fieldFactory)
    {
        $this->settings = $settings;
    
        $connectionStrings = $this->settings->get('connectionStrings');
        $this->connectionString = $connectionStrings['Default']['connectionString'];
        $this->db = new DB($this->connectionString);
    
        $this->fieldFactory = $fieldFactory;
    }
    
    public function getContentTypes($dataType = 'PageData')
    {
        $db = $this->db;
        $pageTypes = new ArrayList();
        $installedPageTypes = $db->query('SELECT * FROM content_type WHERE type = ?', $dataType);
        if (is_array($installedPageTypes)) {
            foreach ($installedPageTypes as $content_type) {
                $pageTypes->add($content_type);
            }
            $db->flushResult();
            $db->clearCache();
        }
        return $pageTypes;
    }
    
    public function getAllContentTypes()
    {
        
    }
    
    public function setFieldRenderer($pageType, $pageDefinitionTypeId, $renderer)
    {
        $db = $this->db;
    
        $db->query('DELETE FROM content_definition_renderer WHERE fkContentTypeId = ? AND fkContentDefinitionId = ?', $pageType, $pageDefinitionTypeId);
    
        $db->insert('content_definition_renderer', array('fkContentTypeId' => $pageType, 'fkContentDefinitionId' => $pageDefinitionTypeId, 'renderer' => $renderer));
    }
    
    /**
     * (non-PHPdoc)
     * @see \smll\cms\framework\content\utils\interfaces\IPageDataRepository::getFieldRenderer()
     */
    public function getFieldRenderer($pageType, $pageDefinitionTypeId)
    {
        $db = $this->db;
    
        $renderers = $db->query('SELECT renderer FROM content_definition_renderer WHERE fkContentTypeId = ? AND fkContentDefinitionId = ?', $pageType, $pageDefinitionTypeId);
    
        if (is_array($renderers) && count($renderers) > 0) {
            $rClass = new ReflectionClass($renderers[0]->renderer);
            return $rClass->newInstance();
        }
    
        return null;
    }
    
    /**
     * (non-PHPdoc)
     * @see \smll\cms\framework\content\utils\interfaces\IPageDataRepository::getPageType()
     */
    public function getContentTypeByName($type)
    {
    
        $class = $this->getContentTypeNamespaceClass($type);
        if ($class != null) {
             
            $class = new \ReflectionClass($class);
    
    
            $instance = null;
            if ($class->hasConstant("__construct")) {
                $instance = $class->newInstance();
            } else {
                $instance = $class->newInstance();
            }
             
            return $instance;
        } else {
            return null;
        }
    
    }
    
    public function addContentType($type, $file, $controller, $name,
            $permissions, $description = null, Guid $guid = null, $dataType = 'PageData')
    {
         
        $db = $this->db;
    
        if (!isset($guid)) {
            $guid = Guid::createNew();
        }
    
        $pageTypes = $db->query('SELECT * FROM content_type WHERE typeGuid = ?', $guid);
        $db->flushResult();
        $db->clearCache();
    
        $pdId = false;
    
        if (is_array($pageTypes) && count($pageTypes) > 0) {
             
            $pdId = $pageTypes[0]->id;
             
            // Update
            $vals = array(
                    'controller' => $controller,
                    'file' => $file,
                    'name' => $type,
                    'description' => $description,
                    'displayName' => $name,
                    'type' => $dataType
            );
             
            $db->where(array('typeGuid', '=', $guid));
            $db->update('content_type', $vals);
            $db->clearCache();
             
            $db->where(array('fkContentTypeId', '=', $pdId));
            $db->delete('content_type_permission');
            $db->clearCache();
             
             
        } else {
            // new
             
            $vals = array(
                    'controller' => $controller,
                    'file' => $file,
                    'name' => $type,
                    'description' => $description,
                    'displayName' => $name,
                    'typeGuid' => $guid,
                    'type' => $dataType
            );
             
            $db->insert('content_type', $vals);
            $pdId = $db->getLastInsertId();
        }
    
        foreach ($permissions as $event => $roles) {
            $roles = explode("|", $roles);
    
            foreach ($roles as $role) {
                $permVal = array(
                        'fkContentTypeId' => $pdId,
                        'role' => $role,
                        'event' => $event,
                );
                 
                $db->insert('content_type_permission', $permVal);
            }
        }
    
        $db->flushResult();
        $db->clearCache();
    
        return $pdId;
    }
    
    public function getContentDefinitionByName($def, $pageTypeId)
    {
        $db = $this->db;
        $type = $db->query('SELECT * FROM content_definition WHERE name = ? AND fkContentTypeId = ?', $def, $pageTypeId);
        $db->clearCache();
        $db->flushResult();
        if (is_array($type) && count($type) > 0) {
            return $type[0];
        } else {
            return null;
        }
    }
    
    public function getContentDefinitionTypeByName($defType)
    {
        $db = $this->db;
    
        $type = $db->query('SELECT * FROM field_definition_type WHERE name = ?', $defType);
        $db->clearCache();
        $db->flushResult();
        if (count($type) > 0) {
            return $type[0];
        } else {
            throw new Exception("Could not find defintion by name ".$defType);
        }
    }
    
    public function getContentTypeId($type)
    {
    
        $db = $this->db;
        $type = $db->query('SELECT id FROM content_type WHERE name = ?', $type);
        $id = $type[0]->id;
        $db->flushResult();
        $db->clearCache();
        return $id;
    
    }
    
    public function getContentTypeNamespaceClass($type)
    {
        $db = $this->db;
        $db->where(array('name', '=', $type));
        $db->get('content_type');
        $result = $db->getResult();
        
        $db->flushResult();
        $db->clearCache();
    
        $pageType = $result[0];
        if (!isset($pageType)) {
            return null;
        }
    
        return str_replace(array('/', '.php'), array('\\', ''), $pageType->file);
    }
    
    public function getInstalledContentTypes()
    {
        $db = $this->db;
        $pageTypes = new ArrayList();
        $installedPageTypes = $db->query('SELECT * FROM content_type WHERE type = ?', 'PageData');
        if (is_array($installedPageTypes)) {
            foreach ($installedPageTypes as $content_type) {
                $pageTypes->add($content_type);
            }
            $db->flushResult();
            $db->clearCache();
        }
        return $pageTypes;
    }
    

    public function getContentDataFields($typeId)
    {
        $db = $this->db;
        // Get Datatype..
        $properties = $db->query('
                SELECT
                pd.id as pd_id,
                pdt.id as pdt_id,
                pd.name as pd_name,
                pdt.name as pdt_name,
                searchable,
                longstringSettings,
                weightOrder,
                required,
                displayName,
                tab,
                type_name,
                assembler
                FROM content_definition as pd
                INNER JOIN field_definition_type as pdt
                ON (pd.fkFieldDefinitionTypeId = pdt.id) WHERE pd.fkContentTypeId = ?', $typeId);
    
    
        $props = array();
    
        foreach ($properties as $prop) {
            $field = new PageDataField();
             
            $r = new \ReflectionClass($prop->assembler);
            $fieldType = $r->newInstance();
            $field->setFieldType($fieldType);
    
            $field->setFieldName($prop->pd_name);
            $field->setDisplayName($prop->displayName);
            $field->setLongStringSettings($prop->longstringSettings);
            $field->setDefinitionTypeId($prop->pdt_id);
            $field->setDefinitionId($prop->pd_id);
            $field->getWeightOrder($prop->weightOrder);
            $field->isRequired((bool)$prop->required);
            $field->isSearchable((bool)$prop->searchable);
            $props[$prop->pd_name] = $field;
        }
    
        $db->flushResult();
        $db->clearCache();
    
        return $props;
    }
    
    
    public function addContentTypeField($pageTypeId, IPageDataField $field)
    {
    
        $db = $this->db;
    
        $id = null;
    
        $vals = array(
                'fkContentTypeId' 						=> $pageTypeId,
                'fkFieldDefinitionTypeId' 	=> $field->getDefinitionTypeId(),
                'name' 										=> $field->getFieldName(),
                'searchable' 							=> $field->isSearchable(),
                'required' 								=> $field->isRequired(),
                'weightOrder' 						=> $field->getWeightOrder(),
                'longStringSettings' 			=> $field->getLongStringSettings(),
                'displayName'							=> $field->getDisplayName(),
                'tab' 										=> $field->getTab()
        );
    
        if ($field->getDefinitionId() != null) {
            // Update field
            $db->where(array('id', '=', $field->getDefinitionId()));
            if ($db->update('content_definition', $vals)) {
                $id = $field->getDefinitionId();
            }
        } else {
            // Add field to page
            $db->insert('content_definition', $vals);
            $id = $db->getLastInsertId();
        }
    
        $db->flushResult();
        $db->clearCache();
    
        return  $id;
    
    }
}