<?php
namespace smll;
class SmllClassLoader {
	
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
		/**
		$classPath = explode($this->namespaceSeparator, $className);
		$className = array_pop($classPath);
		$filePath = join(DIRECTORY_SEPARATOR, $classPath).DIRECTORY_SEPARATOR;
		*/
		
		require(str_replace("_", DIRECTORY_SEPARATOR, $classPath).".php");
	}
	
}