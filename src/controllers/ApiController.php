<?php
namespace src\controllers;

use yogi\framework\mvc\ServiceController;
use yogi\framework\mvc\JsonResult;
use yogi\framework\io\db\interfaces\IServiceDataStore;

class ApiController extends ServiceController {
	
	/**
	 * @var IServiceDataStore
	 */
	private $dataStore;
	
	public function __construct(IServiceDataStore $dataStore) {
		$this->dataStore = $dataStore;
	}
	
	public function endpoint($id = null) {
		
		return new JsonResult($id);
	}
	
	public function index() {
		
		$object = (object)array('Version' => 'Api-v.1');
		
		return new JsonResult($object);
	}
}  