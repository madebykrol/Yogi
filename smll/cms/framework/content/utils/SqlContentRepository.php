<?php
namespace smll\cms\framework\content\utils;
use smll\framework\io\file\FileReference;

use smll\framework\io\file\interfaces\IFileReference;

use smll\framework\utils\ArrayList;

use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

use smll\cms\framework\content\PageDataField;

use smll\framework\utils\HashMap;

use smll\cms\framework\content\interfaces\IPageDataField;

use smll\cms\framework\content\utils\interfaces\IContentRepository;
use smll\cms\framework\content\utils\interfaces\ICrudListener;
use smll\framework\settings\interfaces\ISettingsRepository;
use smll\cms\framework\content\interfaces\IPageData;
use smll\cms\framework\content\PageReference;
use smll\framework\utils\Guid;
use smll\framework\io\db\DB;
use \ReflectionClass;


class SqlContentRepository implements IContentRepository {

	private $settings;
	private $connectionString;
	private $db;
		
	public function __construct(ISettingsRepository $settings) {
		$this->settings = $settings;
		
		$connectionStrings = $this->settings->get('connectionStrings');
		$this->connectionString = $connectionStrings['Default']['connectionString'];
		$this->db = new DB($this->connectionString);
	}
	
	
	public function addPage(IPageData $page) {
		
		//print_r($page);
		$db = $this->db;
		
		$reflectionPage = new ReflectionClass($page);
		$typeId = $this->getPageTypeId($reflectionPage->getShortName());
		// Get Datatype..
		$properties = $this->getPageDataFields($typeId);
		
		$values = array();
		foreach(
				$reflectionPage->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
			
			$value = $prop->getValue($page);
			
			$values[$prop->getName()] = $value;
		}
		
		$pageVals = array();
		
			
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
		
		if($pageVals['externalUrl'] == "") {
			$pageVals['externalUrl'] = 'pages/'.$pageVals['ident'];
		}
		
		$pageVals['peerOrderWeight'] = $values['peerOrderWeight'];
		unset($values['peerOrderWeight']);

		$pageVals['editDate'] = date('Y-m-d H:i:s');
		
		$pageVals['fkPageTypeId'] = $typeId;
		
		// See if page already exists or if it's a completly new page
		if((!isset($page->id) || $page->id == "") && (!isset($page->ident) || $page->ident == "")) {
			// Assume it's a new page.

			$pageVals['id'] = null;
			$pageVals['ident'] = Guid::createNew();
			$pageVals['creationDate'] = date('Y-m-d H:i:s');
			
			
			// Validate data for it's field.
			
			$db->insert('page', $pageVals);
			$pageId = $db->getLastInsertId();
			
			// Store the data
			
			
			
			
			foreach($values as $field => $val) {
				
				$defId = $properties[$field]->getDefinitionId();
				$fieldType = $properties[$field]->getFieldType();
				
				if($fieldType instanceof IFieldType) {
					$datatype = $fieldType->getPropertyDataType();
				}
					
				if(is_array($val)) {
					foreach($val as $part) {
						$db->insert('property', array($datatype => $part, 'fkPageId' => $pageId, 'fkPageDefinitionId' => $defId));
					}
				} else {
					$db->insert('property', array($datatype => $val, 'fkPageId' => $pageId, 'fkPageDefinitionId' => $defId));
				}
			}
			
			$page = $this->getPage($pageId);
			
		} else {
			// assume it's a pre existing page, ready for update
			// Validate data for it's field.
			
			
			$db->where(array('id','=', $page->id));
			$db->where(array('ident','=', $page->ident));
			
			unset($pageVals['id']);
			unset($pageVals['ident']);
			
			$db->update('page', $pageVals);
			$db->clearCache();
			$db->flushResult();
			
			$pageId = $page->id;
				
			// Store the data
			
			foreach($values as $field => $val) {

				$defId = $properties[$field]->getDefinitionId();
				$fieldType = $properties[$field]->getFieldType();
				
				
			
				if($fieldType instanceof IFieldType) {
					$datatype = $fieldType->getPropertyDataType();
				}
				
				
				$db->where(array('fkPageId', '=', $pageId));
				$db->where(array('fkPageDefinitionId', '=', $defId));
				
				if(is_array($val)) {
					$db->delete('property');
					foreach($val as $index => $part) {
						$db->insert('property', array($datatype => $part, 'fkPageId' => $pageId, 'fkPageDefinitionId' => $defId));
					}
					
				} else {
					$db->update('property', array($datatype => $val, 'fkPageId' => $pageId, 'fkPageDefinitionId' => $defId));
				}
				$db->flushResult();
				$db->clearCache();
				
			}
			
		}
		
		
		$db->flushResult();
		$db->clearCache();
		
		
		return $page;
	}
	
	public function getRootPage() {
		$rootPageRef = new PageReference($this);
		$db = $this->db;
		$childrenPages = $db->query("SELECT * FROM page WHERE parentId = ? ORDER BY peerOrderWeight", 0);
		
		$children = new ArrayList();
		if(is_array($childrenPages)) {
			foreach($childrenPages as $child) {
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
	 * @see \smll\cms\framework\content\utils\interfaces\IContentRepository::setPageParent()
	 */
	public function setPageParent($id, $parentId) {
		$db = $this->db;
		if($id instanceof Guid) {
			$db->query('UPDATE page SET parentId = ? WHERE ident = ?', $parentId, $id);
		} else if(is_numeric($id)){
			$db->query('UPDATE page SET parentId = ? WHERE id = ?', $parentId, $id);
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\cms\framework\content\utils\interfaces\IContentRepository::setPeerOrderWeight()
	 */
	public function setPeerOrderWeight($id, $order) {
		$db = $this->db;
		if($id instanceof Guid) {
			$db->query('UPDATE page SET peerOrderWeight = ? WHERE ident = ?', $order, $id);
		} else if(is_numeric($id)){
			$db->query('UPDATE page SET peerOrderWeight = ? WHERE id = ?', $order, $id);
		}
	}
	
	
	public function getPageReference($id) {
		
		$pageRef = new PageReference($this);
		$db = $this->db;
		if($id instanceof Guid) {
			$page = $db->query("SELECT * FROM page WHERE ident = ?", $id);
		} else if(is_numeric($id)) {
			$page = $db->query("SELECT * FROM page WHERE id = ?", $id);
		}
		$page = $page[0];
		
		if(isset($page)) {
			$pageRef->setIdent($page->ident);
			$pageRef->setId($page->id);
			$pageRef->setTitle($page->title);
			$pageRef->isVisibleInMenu((bool)$page->visibleInMenu);
			
			$pageRef->setExternalUrl($page->externalUrl);
			$children = $db->query('SELECT id FROM page WHERE parentId = ? ORDER by peerOrderWeight', $page->id);
			$childPageReferences = new ArrayList();
			
			if(is_array($children)) {
				foreach($children as $child) {
					$childPageReferences->add($this->getPageReference($child->id));
				}
			}
			
			$pageRef->setChildren($childPageReferences);
		}
		return $pageRef;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\cms\framework\content\utils\interfaces\IContentRepository::addPageType()
	 */
	public function addPageType($type, $file, $controller, $name, $permissions, $description = null, Guid $guid = null) {
			
		$db = $this->db;
	
		if(!isset($guid)) {
			$guid = Guid::createNew();
		}
		
		$pageTypes = $db->query('SELECT * FROM page_type WHERE typeGuid = ?', $guid);
		$db->flushResult();
		$db->clearCache();
		
		$pdId = false;
		
		if(is_array($pageTypes) && count($pageTypes) > 0) {
			
			$pdId = $pageTypes[0]->id;
			
			// Update
			$vals = array(
					'controller' => $controller,
					'file' => $file,
					'name' => $type,
					'description' => $description,
					'displayName' => $name
					);
			
			$db->where(array('typeGuid', '=', $guid));
			$db->update('page_type', $vals);
			$db->clearCache();
			
			$db->where(array('fkPageTypeId', '=', $pdId));
			$db->delete('page_type_permission');
			$db->clearCache();
			
			
		} else {
			// new
			
			$vals = array(
					'controller' => $controller,
					'file' => $file,
					'name' => $type,
					'description' => $description,
					'displayName' => $name,
					'typeGuid' => $guid
			);
			
			$db->insert('page_type', $vals);
			$pdId = $db->getLastInsertId();
		}
		
		foreach($permissions as $event => $roles) {
			$roles = explode("|", $roles);
		
			foreach($roles as $role) {
				$permVal = array(
						'fkPageTypeId' => $pdId,
						'role' => $role,
						'event' => $event,
				);
					
				$db->insert('page_type_permission', $permVal);
			}
		}
		
		$db->flushResult();
		$db->clearCache();
		
		return $pdId;
	}
	
	public function getPageDataFields($typeId) {
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
			 	FROM page_definition as pd
				INNER JOIN page_definition_type as pdt
				ON (pd.fkPageDefinitionTypeId = pdt.id) WHERE pd.fkPageTypeId = ?', $typeId);
		
		
		$props = array();
		
		foreach($properties as $prop) {
			$field = new PageDataField();
			
			$r = new ReflectionClass($prop->assembler);
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
	
	
	public function addPageTypeField($pageTypeId, IPageDataField $field) {
		
		$db = $this->db;
		
		
		$id = null;
		
		$vals = array(
				'fkPageTypeId' => $pageTypeId,
				'fkPageDefinitionTypeId' => $field->getDefinitionTypeId(),
				'name' => $field->getFieldName(),
				'searchable' => $field->isSearchable(),
				'required' => $field->isRequired(),
				'weightOrder' => $field->getWeightOrder(),
				'longStringSettings' => $field->getLongStringSettings(),
				'displayName'	=> $field->getDisplayName(),
				'tab' => $field->getTab()
				);		
		
		if($field->getDefinitionId() != null) {
			// Update field
			$db->where(array('id', '=', $field->getDefinitionId()));
			if($db->update('page_definition', $vals)) {
				$id = $field->getDefinitionId();
			}
		} else {
			// Add field to page
			$db->insert('page_definition', $vals);
			$id = $db->getLastInsertId();
		}
		
		$db->flushResult();
		$db->clearCache();
		
		return  $id;
		
	}
	
	
	public function setSettings(ISettingsRepository $settings) {
		$this->settings = $settings;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\cms\framework\content\utils\interfaces\IContentRepository::getPageType()
	 */
	public function getPageTypeByName($type) {
		
		$class = $this->getPageTypeNamespaceClass($type);
		if($class != null) {
			
			$class = new ReflectionClass($class);
		
		
			$instance = null;
			if($class->hasConstant("__construct")) {
				$instance = $class->newInstance();
			} else {
				$instance = $class->newInstance();
			}
			
			return $instance;
		} else {
			return null;
		}
		
	}
	
	public function getPageDefinitionByName($def, $pageTypeId) {
		$db = $this->db;
		$type = $db->query('SELECT * FROM page_definition WHERE name = ? AND fkPageTypeId = ?', $def, $pageTypeId);
		if(is_array($type) && count($type) > 0) {
			return $type[0];
		} else {
			return null;
		}
	}
	
	public function getPageDefinitionTypeByName($defType) {
		$db = $this->db;
		$type = $db->query('SELECT * FROM page_definition_type WHERE name = ?', $defType);
		$db->clearCache();
		$db->flushResult();
		if(count($type) > 0) {
			return $type[0];
		} else {
			throw new Exception("Could not find defintion by name ".$defType);
		}
	}
	
	public function getPageTypeId($type) {
	
		$db = $this->db;
		$type = $db->query('SELECT id FROM page_type WHERE name = ?', $type);
		$id = $type[0]->id;
		$db->flushResult();
		$db->clearCache();
		return $id;
	
	}
	
	public function getPageTypes() {
		$db = $this->db;
		$pageTypes = new ArrayList();
		
		foreach($db->query('SELECT * FROM page_type') as $page_type) {
			$pageTypes->add($page_type);
		}
		
		$db->flushResult();
		$db->clearCache();
		
		return $pageTypes;
	}
	
	public function getPageTypeNamespaceClass($type) {
		
		$db = $this->db;
		$db->where(array('name', '=', $type));
		$db->get('page_type');
		$result = $db->getResult();
		
		$db->flushResult();
		$db->clearCache();
		
		$pageType = $result[0];
		if(!isset($pageType)) {
			return null;
		} 
		
		return str_replace(array('/', '.php'), array('\\', ''), $pageType->file);
	}
	
	public function getPage($id) {
		$data = $this->getPageRaw($id);
		$type = $data['fkPageTypeId'];
		
		$db = $this->db;
		$result = $db->query('SELECT file FROM page_type WHERE id = ?', $type);
		$type = new \ReflectionClass(
				str_replace(array('/', '.php'), array('\\', ''), $result[0]->file));
		
		$page = $type->newInstance();
		
		foreach($type->getProperties() as $name => $prop) {
			if(isset($data[$prop->getName()])) {
				$prop->setValue($page, $data[$prop->getName()]);
			}
		}
		
		$db->flushResult();
		$db->clearCache();
		
		return $page;
	}	
	
	public function findPageWithCriteria() {
		$properties = $db->query('SELECT
				pd.name as name,
				linkGuid,
				longString,
				number,
				pageRef,
				date,
				string,
				title
			FROM
				page_definition AS pd
			JOIN page as p ON (
					pd.fkPageId = p.id
				)
			JOIN
				property AS pty ON (
					pty.fkPageDefinitionId = pd.id
				)
			LEFT JOIN
				page_definition_type as pdt ON (
					pdt.id = pd.fkPageDefinitionTypeId
				)
			WHERE fkPageId = ? ORDER BY peerOrderWeight', $page['id']);
		
	}
	
	public function getPageRaw($id) {
		
		
		$db = $this->db;
		$result = null;
		
		// Fetch "page".
		if($id instanceof Guid) {
			$result = $db->query('SELECT * FROM page WHERE ident = ?', $id);
		} else if(is_numeric($id)) {
			$result = $db->query('SELECT * FROM page WHERE id = ?', $id);
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
				page_definition AS pd 
			JOIN 
				property AS pty ON (
					pty.fkPageDefinitionId = pd.id
				) 
			LEFT JOIN 
				page_definition_type as pdt ON (
					pdt.id = pd.fkPageDefinitionTypeId
				)  
			WHERE fkPageId = ? ORDER BY peerOrderWeight', $page['id']);
		
		
		$props = array();
		
		foreach($properties as $property) {
			$property =(array)$property;
			$propName = $property['name'];
			unset($property['name']);
			
			$propPeerWeightOrder = $property['peerOrderWeight'];
			unset($property['peerOrderWeight']);
			
			$assembler = $property['assembler'];
			unset($property['assembler']);
			
			foreach($property as $prop => $val) {
				if($val != "") {
					if(!isset($props[$propName])) {
						$props[$propName] = array();
					}
					$props[$propName][] = $val;
					break;
				}
			}
		}
		
		foreach($props as $name => $prop) {
			if(count($prop) > 1) {
				$page[$name] = $prop;
			} else {
				$page[$name] = $prop[0];
			}
		}
		
		return $page;
	}
	
	public function removePage($id) {
		
	}
	
	public function removePageType($id) {
		
	}
	
	public function setPageCrudListener(ICrudListener $listener) {
		
	}
	
	public function getFileReference($ident) {
		$db = $this->db;
		$db->where(array('ident', '=', $ident));
		$ref = $db->get('file_reference');
		
		$reference = new FileReference();
		$reference->setIdent(Guid::parse($ref[0]->ident));
		$reference->setId($ref[0]->id);
		$reference->setFilename($ref[0]->filename);
		$reference->setFilesize($ref[0]->size);
		$reference->setMime($ref[0]->mime);
		
		return $reference;
	}
	
	public function setFileReference(IFileReference $ref) {
		
		$db = $this->db;
		
		$values = array(
			'ident' => $ref->getIdent(),
			'filename' => $ref->getFilename(),
			'size' => $ref->getFilesize(),
			'mime' => $ref->getMime()
		);
		
		$db->insert('file_reference', $values);
		
		return $ref;
	}
	
	public function removeFileReference($ident) {
		
	}
}