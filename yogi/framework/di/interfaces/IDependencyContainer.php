<?php
namespace yogi\framework\di\interfaces;

/**
 * Dependency container interface.
 * 
 * @author Kristoffer "mbk" Olsson
 * @version 1.0 stable
 *
 */
interface IDependencyContainer {
	
	/**
	 * Set parameter for a registered service.
	 * @param string $ident
	 * @param mixed $value
	 */
	public function setParameter($ident, $value);
	
	/**
	 * Register a class as a service to a identifier instead of a interface.
	 * @return Defintion
	 * @param string $ident
	 * @param string $class
	 * @param string $interface
	 */
	public function registerWithIdent($ident, $class, $interface);
	
	/**
	 * Register class as a service to a interface.
	 * @return Defintion
	 * @param string $class
	 * @param string $interface
	 */
	public function register($class, $interface);
	
	/**
	 * Get a service instance for interface 
	 * @param unknown $iterface
	 * @return mixed object
	 */
	public function &get($iterface);
	
	/**
	 * Load a container module 
	 * @param IContainerModule $module
	 */
	public function loadModule(IContainerModule $module);
}