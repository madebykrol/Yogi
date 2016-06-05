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
		foreach ($this->_dal->getWhere(strtolower($this->_class->getShortName()), array('id', '=', $id)) as $row) {
			print_r($this->createObject($row));
		}
	}
	
	public function findAll() {}
	public function findBy(CriteriaCollection $criteria, $orderBy, $take, $skip) {}
	public function save($object) {}
	public function remove($object) {}
	
	private function createObject(\stdClass $dbData) {
		$instance = $this->_class->newInstance();
		
		$fields =(array)$dbData;
		
		foreach ($this->_class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
			$lowerName = $property->getName();
			if (isset($fields[$lowerName])) {
				// we know that we have a field with data

				
				// If this field is a "class"
				$varDeclaration = $this->getVarDeclaration($property);
				
				$property->setValue($instance, $fields[$lowerName]);
			}
		}
		
		return $instance;
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