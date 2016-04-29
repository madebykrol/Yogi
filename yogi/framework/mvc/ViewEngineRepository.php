<?php
namespace yogi\framework\mvc;
use yogi\framework\mvc\interfaces\IViewEngineRepository;
use yogi\framework\utils\ArrayList;
use yogi\framework\mvc\interfaces\IViewEngine;

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