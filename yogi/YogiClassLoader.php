<?php
namespace yogi;
class YogiClassLoader {
	
	private $namespaceSeparator = '\\';
	
	/**
	 * Installs this class loader on the SPL autoload stack.
	 */
	public function register()
	{
		spl_autoload_register(array($this, 'loadClass'));
	}
	
	/**
	 * Uninstalls this class loader from the SPL autoloader stack.
	 */
	public function unregister()
	{
		spl_autoload_unregister(array($this, 'loadClass'));
	}
	
	
	public function loadClass($className) {
		$classPath = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $className);
		require(str_replace("_", DIRECTORY_SEPARATOR, $classPath).".php");
	}
	
}