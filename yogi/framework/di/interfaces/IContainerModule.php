<?php
namespace yogi\framework\di\interfaces;
/**
 * Container module interface
 * @author Kristoffer "mbk" Olsson
 * @version 1.0 stable
 *
 */
interface IContainerModule {
	/**
	 * Get registry containing all registered services that this module
	 * has.
	 */
	public function getRegister();
}