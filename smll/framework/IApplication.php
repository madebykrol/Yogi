<?php
namespace smll\framework;
interface IApplication {
	
	public function install();
	public function run();
	
	/**
	 * @return IController
	 */
	public function &getCurrentExecutingController();
	public function getApplicationRoot();
	
	
	/**
	 * @return IDependencyContainer
	 */
	public function getContainer();
}