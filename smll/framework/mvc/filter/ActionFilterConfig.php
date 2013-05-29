<?php
class ActionFilterConfig implements IActionFilterConfig {
	
	private $filters;
	
	public function __construct() {
		$this->filters = new ArrayList();
	}
	public function addFilter(IActionFilter $filter) {
		$this->filters->add($filter);
	}
	
	public function getFilters() {
		return $this->filters;
	}
}