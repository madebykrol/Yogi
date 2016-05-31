<?php
namespace yogi\framework\io\db;

use yogi\framework\io\db\interfaces\IDataStoreManager;
use yogi\framework\io\db\interfaces\IDal;
use yogi\framework\settings\interfaces\ISettingsRepository;
use yogi\framework\io\db\DataStore;
use \ReflectionClass;
use yogi\framework\utils\ArrayList;

class DataStoreManager implements IDataStoreManager {

	/**
	 * Database abstraction layer.
	 * @var IDal
	 */
	private $_dal;
	
	public function __construct(ISettingsRepository $settings) {
		$connectionStrings = $settings->get('connectionStrings');
		$this->_dal = new PDODal($connectionStrings['Default']['connectionString']);
	}
	
	public function createStore(string $className) {
		$class = new \ReflectionClass($className);
		// Check if store exists
		if($this->_dal->tableExists($class->getShortName())) {
			// Check if it needs to be updated.
			
			// get all fields.
			$columns = new ArrayList($this->_dal->getColumns($class->getShortName()));
			
			foreach($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
				// match them against table columns
				if(!$columns->find($property->getName(), function ($search, $entry) {
					return (strtolower($entry->Field) == strtolower($search));
				})) {
					print $property->getName()."Does not exist";
				}
 			}
			
			
		} else {
			// Create table
			$this->_dal->createTable($class->getShortName(), array());
		}
	
		// 
		return new DataStore($class);
	}
	
	public function truncateStore(string $className) {
		
	}
}