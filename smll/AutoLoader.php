<?php
/**
 *
* @author mbk
*
*/
class AutoLoader {

	protected $registeredClassPaths = array(

		// Application
		'HttpApplication' 	=> 'framework/',
		'Settings' 					=> 'framework/settings/',
		
		
		// Router
		'Router' 				=> 'framework/route/',
		'IRouterConfig'	=> 'framework/route/interface/',
		'RouterConfig' 	=> 'framework/route/',
		'Route'					=> 'framework/route/',
		'IRoute'				=> 'framework/route/interface/',
		
			
		// Helpers
		'Html' 		=> 'framework/helpers/',
		'Styles'	=> 'framework/helpers/',
		'Scripts'	=> 'framework/helpers/',
			
		// Html
		'FormField' => 'framework/html/',

		// IO
		'Request' 	=> 'framework/io/',
		'Dir'				=> 'framework/io/file/',
		'DB'				=> 'framework/io/db/',
		'DBField'		=> 'framework/io/db/',
		'DBResult'	=> 'framework/io/db/',
			
		// MVC 
		'Controller' 	=> 'framework/mvc/',
		'Action'			=> 'framework/mvc/',
		'ViewResult'	=> 'framework/mvc/',
		'IActionFilterRepository' => 'framework/mvc/filter/',
		'ActionFilterRepository' 	=> 'framework/mvc/filter/',
		'ActionFilterConfig'			=> 'framework/mvc/filter/',
		'IActionFilterConfig'			=> 'framework/mvc/filter/',
		'DefaultActionFilter'			=> 'framework/mvc/filter/',
		'IActionFilter'						=> 'framework/mvc/filter/',
			
		'IModelState'							=> 'framework/mvc/interface/',
		'ModelState'							=> 'framework/mvc/',
			
		'IModelBinder'						=> 'framework/mvc/interface/',
		'ModelBinder'							=> 'framework/mvc/',
			
		'DataAnnotations'					=> 'framework/mvc/',
			
		// Controllers
		'MapController' => 'controllers/',
		'ControllerFactory' => 'framework/utils/',
			
		// Intefaces
		'IController' 					=> 'framework/mvc/interface/',
		'IRequest' 							=> 'framework/io/interface/',
		'IModel' 								=> 'framework/mvc/interface/',
		'IViewResult' 					=> 'framework/mvc/interface/',
		'IDependencyContainer' 	=> 'framework/di/interface/',
		'IDIService' 						=> 'framework/di/interface/',
		'IList'									=> 'framework/utils/interface/',
		'IService'							=> 'framework/di/interface/',
		'IRouter' 							=> 'framework/route/',
		'IApplication' 					=> 'framework/',
		'ISettings'							=> 'framework/settings/',
		'IControllerFactory'		=> 'framework/utils/interface/',
		'ISettingsLoader'				=> 'framework/settings/',
		'IAnnotationHandler'		=> 'framework/utils/interface/',
		
		
		// Utils
		'ArrayList' 		=> 'framework/utils/',
		'HashMap'				=> 'framework/utils/',
		'Regexp'				=> 'framework/utils/',
		'String' 				=> 'framework/utils/',
		'Boolean'				=> 'framework/utils/',
		'JsonConverter' => 'framework/utils/',
		'AnnotationHandler'	=> 'framework/utils/',
		
		// IOC 
		'ContainerBuilder' 	=> 'framework/di/',
		'DIService'					=> 'framework/di/',
		'Definition' 				=> 'framework/di/',
		'Service'						=> 'framework/di/',
		'NullArgument' 			=> 'framework/di/',
		
		// Settings

		'XmlSettingsLoader' => 'framework/io/',
			
		'ViewFactory'				=> 'framework/ViewFactory',
		
		// Exceptions
		'ClassNotFoundException' => 'framework/exceptions/',
		'IndexNotInMapException' => 'framework/exceptions/',
		'CannotSerializeServiceException' => 'framework/exceptions/',
			
		// HTTP
		'ISession'				=> 'framework/http/interface/',
		'Session'					=> 'framework/http/',
			
		// UnitTests
	
		'UnitTest'				=> 'framework/unittest/',
		
		
	);

	protected $paths = array(
		'src/controllers/',
		'src/models/',
		'src/business/',
		'src/'
	);

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
		} else {
			$dir = new Directory();
			if(preg_match('/Controller/', $class)){
				if(is_file('src/controllers/'.$class.".php")) {
					include('src/controllers/'.$class.".php");
					//print "controller";
					return true;
				}
			} else {
				
				$dir = new Dir('src');
				$result = new ArrayList();
				$dir->searchRecursive($class.".php", $result);
				foreach($result->getIterator() as $classPath) {
					include($classPath);
					return true;
				} 
			}
		}

		throw new ClassNotFoundException("Class not found exception $class");
	}
	
	public function register() {
		spl_autoload_register(array($this, 'load'));
	}
}

$autoloader = new Autoloader();
$autoloader->register();