<?php
namespace smll\cms\framework\content\utils;
use smll\cms\framework\ui\interfaces\IFieldTypeFactory;

use smll\cms\framework\ui\FieldTypeFactory;

use smll\cms\framework\content\PageProperty;

use smll\cms\framework\content\interfaces\IPageProperty;

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
	private $fieldFactory;
		
	public function __construct(ISettingsRepository $settings, IFieldTypeFactory $fieldFactory) {
		$this->settings = $settings;
		
		$connectionStrings = $this->settings->get('connectionStrings');
		$this->connectionString = $connectionStrings['Default']['connectionString'];
		$this->db = new DB($this->connectionString);
		
		$this->fieldFactory = $fieldFactory;
	}
	
	
	public function addPage(IPageData $page) {
		
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
					foreach($val as $index => $part) {
						$db->insert('property', array($datatype => $part, 'fkPageId' => $pageId, 'fkPageDefinitionId' => $defId, 'index' => $index));
					}
				} else {
					$db->insert('property', array($datatype => $val, 'fkPageId' => $pageId, 'fkPageDefinitionId' => $defId));
				}
			}
			
			$page->id = $pageId;
			
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
				$fileField = false;
				if($fieldType instanceof IFieldType) {
					$datatype = $fieldType->getPropertyDataType();
				} else {
					$datatype = "string";
				}
				
				if(!$fieldType instanceof IFileFieldType) {
					
					$db->where(array('fkPageId', '=', $pageId));
					$db->where(array('fkPageDefinitionId', '=', $defId));
					
					$db->delete('property');
					$db->clearCache();
					
				} else {
					$fileField = true;
				}
				
				if(is_array($val)) {
					foreach($val as $index => $part) {
						
						$prop = new PageProperty();
						if(!empty($part)) {
							$prop->setValue($part);
						}
						$prop->setIndex($index);
						$prop->setPageDefinitionId($defId);
						$prop->setDataType($datatype);
						
						if($fileField) {
							$prop->ignoreIfNull(true);
						}
						
						$this->setPropertyForPage($pageId, $prop);
					}
				} else {
					$prop = new PageProperty();
					if(!empty($val)) {
						$prop->setValue($val);
					}
					$prop->setIndex(0);
					$prop->setPageDefinitionId($defId);
					$prop->setDataType($datatype);
					if($fileField) {
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
	
	public function setPropertyForPage($pageId, IPageProperty $prop) {
		$db = $this->db;
		if(is_null($prop->getValue())) {
			if($prop->ignoreIfNull()) {
				return;
			}
		}
		$db->insert('property', array(
				$prop->getDataType() 	=> $prop->getValue(), 
				'fkPageId' 						=> $pageId, 
				'fkPageDefinitionId' 	=> $prop->getPageDefinitionId(), 
				'index' 							=> $prop->getIndex()));
		$db->clearCache();
		$db->flushResult();
	}
	
	public function removePropertyForPage($pageId, IPageProperty $prop) {
		$db = $this->db;
		$db->where(array('fkPageId', '=', $pageId));
		$db->where(array('fkPageDefinitionId', '=', $prop->getPageDefinitionId()));
		$index = $prop->getIndex();
		if($index != null) {
			$db->where(array('index', '=', $prop->getIndex()));
		}
		$db->delete('property');
		$db->clearCache();
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
		
		$pageRef = new PageReference();
		$db = $this->db;
		$db->flushResult();
		
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
			$pageRef->setPageTypeId($page->fkPageTypeId);
			$pageRef->setAuthor($page->authorName);
			
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
		
		$db->clearCache();
		$db->flushResult();
		
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
			'fkPageTypeId' 						=> $pageTypeId,
			'fkPageDefinitionTypeId' 	=> $field->getDefinitionTypeId(),
			'name' 										=> $field->getFieldName(),
			'searchable' 							=> $field->isSearchable(),
			'required' 								=> $field->isRequired(),
			'weightOrder' 						=> $field->getWeightOrder(),
			'longStringSettings' 			=> $field->getLongStringSettings(),
			'displayName'							=> $field->getDisplayName(),
			'tab' 										=> $field->getTab()
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
		$db->clearCache();
		$db->flushResult();
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
	
	public function getPageData(IPageReference $page) {
		$data = $this->getPageRaw($page->getId());
		$type = $data['fkPageTypeId'];
		
		$db = $this->db;
		$result = $db->query('SELECT file FROM page_type WHERE id = ?', $type);
		if(count($result) > 0) {
			$type = new \ReflectionClass(
					str_replace(array('/', '.php'), array('\\', ''), $result[0]->file));
			
			$pageData = $type->newInstance();
			
			foreach($type->getProperties() as $name => $prop) {
				if(isset($data[$prop->getName()])) {
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
	
	public function findPageWithCriteria(IPageReference $page, IPropertyCriteriaCollection $criteriaCollection) {
		
		$startPageId = $page->getId();
		
		$query = "SELECT * FROM page ";
		
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
			
			$hashMap = new HashMap();
			
			
			
			$field = $this->fieldFactory->buildFieldType($assembler, $hashMap);
			$datatype = $field->getPropertyDataType();
			if(!isset($props[$propName])) {
				$props[$propName] = array();
			}
			$props[$propName][] = $field->processData($property[$datatype]);
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
		$db = $this->db;
		
		if($id instanceof Guid) {
			
			$db->where(array('ident', '=', $id));
			$page = $db->get('page');
			$page = $page[0];
			
			$id = $page->id;
		} 
		
		$db->clearCache();
		
		$db->where(array('id', '=', $id));
		$db->delete('page');
		$db->clearCache();
		
		$db->where(array('fkPageId', '=', $id));
		$db->delete('property');
		
	}
	
	public function removePageType($id) {
		
	}
	
	public function setPageCrudListener(ICrudListener $listener) {
		
	}
	
	public function setFieldRenderer($pageType, $pageDefinitionTypeId, $renderer) {
		$db = $this->db;
		
		$db->query('DELETE FROM page_definition_renderer WHERE fkPageTypeId = ? AND fkPageDefinitionId = ?', $pageType, $pageDefinitionTypeId);
		
		$db->insert('page_definition_renderer', array('fkPageTypeId' => $pageType, 'fkPageDefinitionId' => $pageDefinitionTypeId, 'renderer' => $renderer));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\cms\framework\content\utils\interfaces\IContentRepository::getFieldRenderer()
	 */
	public function getFieldRenderer($pageType, $pageDefinitionTypeId) {
		$db = $this->db;
		
		$renderers = $db->query('SELECT renderer FROM page_definition_renderer WHERE fkPageTypeId = ? AND fkPageDefinitionId = ?', $pageType, $pageDefinitionTypeId);
		
		if(is_array($renderers) && count($renderers) > 0) {
			$rClass = new ReflectionClass($renderers[0]->renderer);
			return $rClass->newInstance();
		}
		
		return null;
	}
	
	
	
} 