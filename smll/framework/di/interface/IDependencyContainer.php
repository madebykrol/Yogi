<?php
interface IDependencyContainer {
	public function setParameter($ident, $value);
	
	public function setParameterForIdent($ident, $value);
	/**
	 * @return Defintion
	 * @param unknown $ident
	 * @param unknown $class
	 * @param unknown $interface
	 */
	public function registerWithIdent($ident, $class, $interface);
	
	/**
	 * @return Defintion
	 * @param unknown $class
	 * @param unknown $interface
	 */
	public function register($class, $interface);
	
	public function &get($iterface);
}