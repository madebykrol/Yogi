<?php
interface IApplication {
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