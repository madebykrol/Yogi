<?php
namespace smll\framework\mvc\filter\interfaces;
use smll\framework\mvc\filter\interfaces\IActionFilter;
use smll\framework\mvc\filter\interfaces\IAuthorizationFilter;


interface IFilterConfig {
	
	public function addActionFilter(IActionFilter $filter);
	public function getActionFilters();
	
	public function addAuthorizationFilter(IAuthorizationFilter $filter);
	public function getAuthorizationFilters();
	
}