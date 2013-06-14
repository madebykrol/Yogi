<?php
interface IFilterRepository {
	public function addFilterConfig(IFilterConfig $config);
	
	/**
	 * @return IFilterConfig
	 */
	public function getFilterConfig();
}