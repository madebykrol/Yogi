<?php
namespace yogi\framework;
use yogi\framework\di\interfaces\IDependencyContainer;
use yogi\framework\mvc\interfaces\IViewEngineRepository;
use yogi\framework\utils\HashMap;
interface IApplication {
	
	public function run();
	public function close();
	
	/**
	 * @return IController
	 */
	public function &getCurrentExecutingController();
	public function getApplicationRoot();
	
	
	/**
	 * @return IDependencyContainer
	 */
	public function getContainer();
	
	/**
	 * 
	 * @param IDependencyContainer $container
	 */
	public function setContainer(IDependencyContainer $container);
	
	/**
	 * 
	 * @param IViewEngineRepository $repository
	 */
	public function setViewEngines(IViewEngineRepository $repository);
	
	public function processAction($controller, $actionName, HashMap $parameters = null);
	
	public function scheduler();
	
	public function install();
	
}