<?php
interface IActionFilterConfig {
	public function addFilter(IActionFilter $filter);
	public function getFilters();
}