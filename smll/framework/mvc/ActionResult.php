<?php
namespace smll\framework\mvc;
use smll\framework\utils\HashMap;
use smll\framework\mvc\interfaces\IActionResult;

class ActionResult implements IActionResult {
	private $viewFile;
	private $model;
	private $viewBag;
	private $headers;
	/**
	 * @var IViewEngine
	 */
	private $viewEngine;
	private $useView = false;
	
	public function __construct($model = null) {
		$this->model = $model;
		$this->headers = new HashMap();
		
		$this->init();
	}
	
	protected function init() {}
	
	public function setHeader($field, $value) {
		$this->headers->add($field, $value);
	}
	
	public function setHeaders(HashMap $headers) {
		$this->headers = $headers;
	}
	
	public function getHeaders() {
		return $this->headers;
	}
	
	public function setModel($model) {
		$this->model = $model;
	}
	public function getModel(){
		return $this->model;
	}
	
	public function setViewFile($file){
		$this->viewFile = $file;
	}
	
	public function getViewFile(){
		return $this->viewFile;
	}
	
	public function setViewBag($viewBag) {
		$this->viewBag = $viewBag;
	}
	
	public function getViewBag() {
		return $this->viewBag;
	}
	
	public function render() {
		return $this->model;
	}
	
	public function renderSection() {
	
	}
	
	public function renderContent() {
	
	}	
	
	public function useView($boolean = null) {
		if(is_bool($boolean)) {
			$this->useView = $boolean;
		}
		return $this->useView;
	}
	
	
}