<?php
namespace src\controllers;

use smll\framework\mvc\ServiceController;
use smll\framework\mvc\JsonResult;
use smll\framework\io\db\interfaces\IServiceDataStore;

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