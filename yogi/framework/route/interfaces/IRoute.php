<?php
namespace yogi\framework\route\interfaces;
interface IRoute {
	public function getName();
	
	public function getUrl();
	
	public function getDefaults();
}