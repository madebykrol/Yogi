<?php
/**
 *
* @author mbk
*
*/
class AutoLoader {

	protected $registeredClassPaths = array(

		// Application
		'HttpApplication' 		=> 'framework/',
		'SettingsRepository' 	=> 'framework/settings/',
		
		// Exceptions
		'EmptyResultException'	=> 'framework/exceptions/',
		'MembershipUserExistsException' => 'framework/exceptions/',
		
			
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
		'IFilterRepository' 			=> 'framework/mvc/filter/',
		'FilterRepository' 				=> 'framework/mvc/filter/',
		'FilterConfig'						=> 'framework/mvc/filter/',
		'IFilterConfig'						=> 'framework/mvc/filter/',
		'FilterAttribute'					=> 'framework/mvc/filter/',
		'IActionFilter'						=> 'framework/mvc/filter/',
		'IAuthorizationFilter'		=> 'framework/mvc/filter/',
		'IResultFilter'						=> 'framework/mvc/filter/',
		'AuthorizationFilter'			=> 'framework/mvc/filter/',
			
		'AuthorizationContext'  	=> 'framework/mvc/filter/',
		'ControllerContext'				=> 'framework/mvc/filter/',
		'IContext'								=> 'framework/mvc/filter/',
			
		'IModelState'							=> 'framework/mvc/interface/',
		'ModelState'							=> 'framework/mvc/',
			
		'IModelBinder'						=> 'framework/mvc/interface/',
		'ModelBinder'							=> 'framework/mvc/',
			
		'DataAnnotations'					=> 'framework/mvc/',
			
		// Controllers
		'MapController' 					=> 'controllers/',
		'ControllerFactory' 			=> 'framework/utils/',
			
		// Intefaces
		'IController' 						=> 'framework/mvc/interface/',
		'IRequest' 								=> 'framework/io/interface/',
		'IModel' 									=> 'framework/mvc/interface/',
		'IViewResult' 						=> 'framework/mvc/interface/',
		'IDependencyContainer' 		=> 'framework/di/interface/',
		'IDIService' 							=> 'framework/di/interface/',
		'IList'										=> 'framework/utils/interface/',
		'IService'								=> 'framework/di/interface/',
		'IRouter' 								=> 'framework/route/',
		'IApplication' 						=> 'framework/',
		'ISettingsRepository'			=> 'framework/settings/',
		'IControllerFactory'			=> 'framework/utils/interface/',
		'ISettingsLoader'					=> 'framework/settings/',
		'IAnnotationHandler'			=> 'framework/utils/interface/',
		'ITicket'									=> 'framework/security/interface/',
		
		
		// Utils
		'ArrayList' 							=> 'framework/utils/',
		'HashMap'									=> 'framework/utils/',
		'Regexp'									=> 'framework/utils/',
		'String' 									=> 'framework/utils/',
		'Boolean'									=> 'framework/utils/',
		'JsonConverter' 					=> 'framework/utils/',
		'AnnotationHandler'				=> 'framework/utils/',
		'Guid'										=> 'framework/utils/',
		'IFormFieldHandler'				=> 'framework/utils/handlers/',
		'FormFieldHandler'				=> 'framework/utils/handlers/',
		
		
		// IOC 
		'ContainerBuilder' 				=> 'framework/di/',
		'DIService'								=> 'framework/di/',
		'Definition' 							=> 'framework/di/',
		'Service'									=> 'framework/di/',
		'NullArgument' 						=> 'framework/di/',
		'IContainerModule'				=> 'framework/di/interface/',
		'DefaultContainerModule'	=> 'modules/',
		
		// Settings

		'XmlSettingsLoader' 			=> 'framework/io/',		
		'ViewFactory'							=> 'framework/ViewFactory',
		
		// Exceptions
		'ClassNotFoundException' 	=> 'framework/exceptions/',
		'IndexNotInMapException' 	=> 'framework/exceptions/',
		'CannotSerializeServiceException' => 'framework/exceptions/',
			
		// HTTP
		'ISession'								=> 'framework/http/interface/',
		'IHeaderRepository' 			=> 'framework/http/interface/',
		'Session'									=> 'framework/http/',
		'Headers'									=> 'framework/http/',
			
		// UnitTests
		'UnitTest'								=> 'framework/unittest/',
			
		// Security 
		'IMembership'							=> 'framework/security/interface/',
		'IMembershipProvider'			=> 'framework/security/interface/',
		'IRoleProvider'						=> 'framework/security/interface/',
		'SqlMembershipProvider'		=> 'framework/security/',
		'MembershipUser'					=> 'framework/security/',
		'SqlRoleProvider'					=> 'framework/security/',
			
		'IIdentity'								=> 'framework/security/',
		'IPrincipal'							=> 'framework/security/',
			
		'Identity'								=> 'framework/security/',
		'Principal'								=> 'framework/security/',
			
		'FormAuthentication'			=> 'framework/security/authentication/',
		'SessionFormAuthentication'			=> 'framework/security/authentication/',
		'IAuthenticationProvider'	=> 'framework/security/interface/',
			
		'ICryptographer'					=> 'framework/security/interface/',
		'Crypt'										=> 'framework/security/',
		
		'AuthenticationTicket'		=> 'framework/security/authentication/',
		
	);

	protected $paths = array(
		'smll/framework/lib/',
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
		
		$class = explode("\\", $class);
		
		if(count($class) > 1) {
			// we have a name space use.
		} else {
			// Normal case class 
			$class = $class[0];
		}
		
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
				
				$dir = new Dir('smll/lib');
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