<?php
namespace smll\framework\mvc\filter;
use smll\framework\mvc\filter\interfaces\IFilterConfig;
use smll\framework\utils\ArrayList;
use smll\framework\mvc\filter\interfaces\IActionFilter;
use smll\framework\mvc\filter\interfaces\IAuthorizationFilter;
class FilterConfig implements IFilterConfig {
	
	private $actionFilters;
	private $authorizationFilters;
	
	public function __construct() {
		$this->actionFilters = new ArrayList();
		$this->authorizationFilters = new ArrayList();
	}
	
	public function addActionFilter(IActionFilter $filter) {
		$this->actionFilters->add($filter);
	}
	
	public function addAuthorizationFilter(IAuthorizationFilter $filter) {
		$this->authorizationFilters->add($filter);
	}
	
	public function getAuthorizationFilters() {
		return $this->authorizationFilters;
	}
	
	public function getActionFilters() {
		return $this->actionFilters;
	}
}