<?php
namespace yogi\framework\io\db;

use yogi\framework\io\db\interfaces\IDataStoreManager;
use yogi\framework\io\db\interfaces\IDal;
use yogi\framework\settings\interfaces\ISettingsRepository;
use yogi\framework\io\db\DataStore;
use yogi\framework\io\db\DBField;
use \ReflectionClass;
use yogi\framework\utils\ArrayList;
use yogi\framework\io\db\interfaces\IDBFieldFactory;

class DataStoreManager implements IDataStoreManager {

	/**
	 * Database abstraction layer.
	 * @var IDal
	 */
	private $_dal;
	
	/**
	 * Field factory
	 * @var IDBFieldFactory
	 */
	private $_dbFieldFactory;
	
	public function __construct(ISettingsRepository $settings, IDBFieldFactory $dbFieldFactory) {
		$connectionStrings = $settings->get('connectionStrings');
		$this->_dal = new PDODal($connectionStrings['Default']['connectionString']);
		$this->_dbFieldFactory = $dbFieldFactory;
	}
	
	public function createStore($className) {
		$class = new \ReflectionClass($className);
		// Check if store exists
		if($this->_dal->tableExists($class->getShortName())) {
			// Check if it needs to be updated.
			
			// get all fields.
			$columns = new ArrayList($this->_dal->getColumns($class->getShortName()));
			foreach($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
				// match them against table columns
				if(($column = $columns->find($property->getName(), function ($search, $entry) {
					return (strtolower($entry->Field) == strtolower($search));
				})) == null) {
					
					$dbColumn = $this->_dbFieldFactory->fromObjProperty($property);
					//print $property->getName()."Does not exist";
				} else {
					// Else check if we need to change the field.
					$dbColumn = $this->_dbFieldFactory->fromDbColumn($column);
				}
 			}
			
			
		} else {
			// Create table
			$this->_dal->createTable($class->getShortName(), array());
		}
	
		// 
		return new DataStore($class, $this->_dal);
	}
	
	public function truncateStore($className) {
		
	}
}