<?php
namespace yogi\framework\io\db;

use yogi\framework\io\db\interfaces\IDataStore;
use yogi\framework\io\db\CriteriaCollection;


class DataStore implements IDataStore {
	
	public function __construct(\ReflectionClass $class)
	{
		
	}
	
	public function find($id) {}
	public function findAll() {}
	public function findBy(CriteriaCollection $criteria, $orderBy, $take, $skip) {}
	public function save($object) {}
	public function remove($object) {}
	
}