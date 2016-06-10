<?php
namespace yogi\framework\io\db;

use yogi\framework\io\db\interfaces\IDataStore;
use yogi\framework\io\db\CriteriaCollection;
use yogi\framework\io\db\interfaces\IDal;


class DataStore implements IDataStore {
	
	/**
	 * @var \ReflectionClass
	 */
	private $_class;
	
	/**
	 * @var IDal
	 */
	private $_dal;
	
	public function __construct(\ReflectionClass $class, IDal $dal)
	{
		$this->_class = $class;
		$this->_dal = $dal;
	}
	
	public function find($id) {
		$this->_dal->flushResult(true);
		return $this->createObjectsFromArray($this->_dal->getWhere(strtolower(ucfirst($this->_class->getShortName())), array(array('id', '=', $id))));
	}
	
	public function findAll() {
		$this->_dal->flushResult(true);
		return $this->createObjectsFromArray($this->_dal->get(strtolower(ucfirst($this->_class->getShortName()))));
	}
	
	public function findBy(CriteriaCollection $criteria, $orderBy, $take, $skip) {
		
	}
	
	public function save($object, $class = null) {
		// Set class to _class if parameter is null, this enables recursive calls for createObject
		// recursive calls will occur when we create relation mapping.
		if($class == null) {
			$class = $this->_class;
		}
		$dataSet = array();
		foreach($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
			$fieldName = $property->getName();
			$fieldValue = $property->getValue($object);
			$varDeclaration = $this->getVarDeclaration($property);

			if(!in_array($varDeclaration, array("boolean", "integer", "float", "string"))) {
				if($varDeclaration != "object") {
						
					// check if there is a one to many relation.
					$varDecArr = explode("|", $varDeclaration);
					$varDeclaration  = $varDecArr[0];
					if(isset($varDecArr[1])) {
						$varDeclaration = $varDecArr[1];
					}
			
					if($varDecArr[0] == "array") {
						// Multiple value that we need to map
						$t1 = ucfirst(strtolower($class->getShortName()));
						$t2 = ucfirst(strtolower($lowerName));
						$joinTableName = $t1.$t2;
						
						
					} else {
						// single value, just go along with our beeswax	
						$dataSet[strtolower($fieldName)] = $fieldValue;
					}
				}
			} else {
				$dataSet[strtolower($fieldName)] = $fieldValue;
			}
		}
		
		$this->_dal->insert($class->getShortName(), $dataSet);
		
	}
	public function remove($object) {}
	
	private function createObject(\stdClass $dbData, $class = null) {
		
		// Set class to _class if parameter is null, this enables recursive calls for createObject 
		// recursive calls will occur when we create relation mapping.
		if($class == null) {
			$class = $this->_class;
		}
		
		// Get a new instance of our class
		$instance = $class->newInstance();
		
		// All database fields with data.
		$fields =(array)$dbData;
		
		// For each properties in class.
		foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
			
			// Begin to build our property mapping
			$lowerName = $property->getName();
			
			// If this field is a "class"
			$varDeclaration = $this->getVarDeclaration($property);
			
			// Check @var for data type
			if(!in_array($varDeclaration, array("boolean", "integer", "float", "string"))) {
				if($varDeclaration != "object") {
					
					// check if there is a one to many relation.
					$varDecArr = explode("|", $varDeclaration);
					$varDeclaration  = $varDecArr[0];
					if(isset($varDecArr[1])) {
						$varDeclaration = $varDecArr[1];
					}

					if($varDecArr[0] == "array") {
						$data = $this->getJoinedData(ucfirst(strtolower($class->getShortName())), ucfirst(strtolower($lowerName)), $varDeclaration);
						$property->setValue($instance, $data);
					} else {

						if (isset($fields[$lowerName])) {
							// we know that we have a field with data
							$reflectionClass = new ReflectionClass($varDecArr[0]);
							$store = new DataStore($reflectionClass, $this->_dal);
							$data = $store->find($fields[$lowerName]);
							$property->setValue($instance, $data[0]);
						}
					}
				}
			} else {
				$property->setValue($instance, $fields[$lowerName]);
			}
		}
		
		return $instance;
	}
	
	private function getJoinedData($t1, $t2, $class) {

		$joinTableName = $t1.$t2;
		// Join by jointable. 
		return $this->createObjectsFromArray($this->_dal->query("SELECT t2.* FROM ".$t1." AS t1 LEFT JOIN ".$joinTableName." AS t1t2 ON t1.id = t1t2.fk".$t1."
RIGHT JOIN ".$t2." AS t2 ON t2.id = t1t2.fk".$t2." WHERE t1.id=1"), new \ReflectionClass($class));
	}
	
	private function createObjectsFromArray(array $data, \ReflectionClass $class = null) {
		$arr = array();
	
		foreach ($data as $row) {
			$arr[] = $this->createObject($row, $class);
		}
	
		return $arr;
	}
	
	private function getVarDeclaration(\ReflectionProperty $prop) {
		
		if (preg_match('/@var\s+([^\s]+)/', $prop->getDocComment(), $matches)) {
			if(count($matches) > 1) {
				return $matches[1];
			}
		}
		
		return null;
	}
	
}