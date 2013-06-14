<?php
interface IFilterConfig {
	public function addActionFilter(IActionFilter $filter);
	public function getActionFilters();
	
	public function addAuthorizationFilter(IAuthorizationFilter $filter);
	public function getAuthorizationFilters();
}