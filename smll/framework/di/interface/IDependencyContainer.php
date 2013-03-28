<?php
interface IDependencyContainer {
	public function setParameter($ident, $value);
	
	public function setParameterForIdent($ident, $value);
	
	public function registerWithIdent($ident, $class, $interface);
	
	public function register($class, $interface);
	
	public function get($iterface);
}