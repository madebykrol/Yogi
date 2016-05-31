<?php
namespace yogi\framework\io\db\interfaces;
use yogi\framework\io\db\CriteriaCollection;

interface IDataStore {
	
	public function find($id);
	public function findAll();
	public function findBy(CriteriaCollection $criteria, $orderBy, $take, $skip);
	public function save($object);
	public function remove($object);
	
}