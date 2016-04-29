<?php
namespace yogi\framework\di\interfaces;
/**
 * Dependency injection service interface
 * Implementations of this interface is used to bind a service at runtime
 * as a parameter to another service in the container.
 * 
 * @author Kristoffer "mbk" Olsson
 */
interface IService {
	public function getServiceReference();
}