<?php
namespace yogi\framework\io\db\interfaces;

interface IServiceDataStore {
	
	public function createStore($object);
	
	public function find($query);
	public function save($object);
	public function remove($object);
	
}