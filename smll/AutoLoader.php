<?php
/**
 *
* @author mbk
*
*/
class AutoLoader {

	protected $registeredClassPaths = array(

		// Application
		'HttpApplication' => 'framework/',
		'Settings' => 'framework/settings/',
		
		
		// Router
		'Router' => 'framework/',
		
			
		// Helpers
		'Html' => 'framework/helpers/',

		// IO
		'Request' => 'framework/io/',
			
		// Controllers
		'MapController' => 'controllers/',
		'ControllerFactory' => 'framework/utils/',
			
		// Intefaces
		'IController' 					=> 'controllers/interface/',
		'IRequest' 							=> 'framework/io/interface/',
		'IModel' 								=> 'framework/mvc/interfaces/',
		'IView' 								=> 'framework/mvc/interfaces/',
		'IDependencyContainer' 	=> 'framework/di/interface/',
		'IDIService' 						=> 'framework/di/interface/',
		'IList'									=> 'framework/utils/interface/',
		'IService'							=> 'framework/di/interface/',
		'IRouter' 							=> 'framework/',
		'IApplication' 					=> 'framework/',
		'ISettings'							=> 'framework/settings/',
		'IControllerFactory'		=> 'framework/utils/interface/',
		
		// Utils
		'ArrayList' => 'framework/utils/',
		'HashMap'	=> 'framework/utils/',
			
		// IOC 
		'ContainerBuilder' => 'framework/di/',
		'DIService'	=> 'framework/di/',
		'Definition' => 'framework/di/',
		'Service'	=> 'framework/di/',
		'NullArgument' => 'framework/di/',
			
		// Exceptions
		'ClassNotFoundException' => 'framework/exceptions/',
		'IndexNotInMapException' => 'framework/exceptions/',
			
		'MockSettings' => 'src/',
		
	);

	protected $paths = array();

	/**
	 * Auto load class from one of $this->paths
	 * @param string $class
	*/
	public /* void */ function load ($class) {

		$trail = '';
		$found = false;

		if (isset($this->registeredClassPaths[$class])) {
			include($this->registeredClassPaths[$class].$class.".php");
			return true;
		}

		throw new ClassNotFoundException("Class not found exception $class");
	}

	
	public function register() {
		spl_autoload_register(array($this, 'load'));
	}
}

$autoloader = new Autoloader();
$autoloader->register();