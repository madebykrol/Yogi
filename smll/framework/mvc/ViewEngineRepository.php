<?php
namespace smll\framework\mvc;
use smll\framework\mvc\interfaces\IViewEngineRepository;
use smll\framework\utils\ArrayList;
use smll\framework\mvc\interfaces\IViewEngine;

class ViewEngineRepository implements IViewEngineRepository {
	
	/**
	 * @var ArrayList;
	 */
	private $engines;
	
	public function __construct() {
		$this->engines = new ArrayList();
	}
	
	public function getEngines() {
		return $this->engines;
	}
	public function clearEngines() {
		$this->engines = new ArrayList();
	}
	public function addEngine(IViewEngine $engine) {
		$this->engines->add($engine);
	}
}