<?php
namespace yogi\framework\mvc\filter\interfaces;
use yogi\framework\mvc\filter\interfaces\IFilterConfig;

/**
 * 
 * @author Kristoffer "mbk" Olsson
 * @deprecated
 *
 */
interface IFilterRepository {
	public function addFilterConfig(IFilterConfig $config);
	
	/**
	 * @return IFilterConfig
	 */
	public function getFilterConfig();
}