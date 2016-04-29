<?php
namespace yogi\framework\mvc\interfaces;

interface IViewEngineRepository {
	public function getEngines();
	public function clearEngines();
	public function addEngine(IViewEngine $engine); 
}