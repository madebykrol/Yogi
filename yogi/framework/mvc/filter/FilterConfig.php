<?php
namespace yogi\framework\mvc\filter;
use yogi\framework\mvc\filter\interfaces\IFilterConfig;
use yogi\framework\utils\ArrayList;
use yogi\framework\mvc\filter\interfaces\IActionFilter;
use yogi\framework\mvc\filter\interfaces\IAuthorizationFilter;
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