<?php
namespace yogi\framework\mvc;

class JsonResult extends ActionResult {
	
	public function __construct($model = null) {
		parent::__construct($model);
	}
	
	protected function init() {
		
		$headers = $this->getHeaders();
		$headers->add("Content-type", "application/json; charset=utf-8");
		
	}
	
	public function render() {
		return json_encode($this->getModel());
	}
	
}