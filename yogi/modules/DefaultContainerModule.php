<?php
namespace yogi\modules;
use yogi\framework\di\interfaces\IContainerModule;
use yogi\framework\utils\HashMap;
use yogi\framework\di\Definition;
use yogi\framework\route\RouterConfig;
use yogi\framework\di\Service;

class DefaultContainerModule implements IContainerModule {
	
	private $reg; 
	
	public function __construct() {
		$this->reg = new HashMap();
		$this->init();
	}
	
	public function init() {
		
		 $this->register('yogi\framework\io\file\FileUploadManager',
				'yogi\framework\io\file\interfaces\IFileUploadManager')
				->addArgument($_FILES);
		 
		 $this->register('yogi\framework\security\SqlMembershipProvider', 
		 		'yogi\framework\security\interfaces\IMembershipProvider')
		 	->inRequestScope();
		 
		 $this->register('yogi\framework\security\SqlRoleProvider', 
		 		'yogi\framework\security\interfaces\IRoleProvider');
		 
		 $this->register('yogi\framework\http\Session', 
		 		'yogi\framework\http\interfaces\ISession')
		 	->addArgument(array());
		 
		 $this->register('yogi\framework\security\authentication\FormAuthentication', 
		 		'yogi\framework\security\interfaces\IAuthenticationProvider');
		 
		 $this->register('yogi\framework\security\Crypt', 
		 		'yogi\framework\security\interfaces\ICryptographer');
		 
		 $this->register('yogi\framework\mvc\ModelState', 
		 		'yogi\framework\mvc\interfaces\IModelState')
		 	->inRequestScope();
		 
		 $this->register('yogi\framework\io\XmlSettingsLoader', 
		 		'yogi\framework\settings\interfaces\ISettingsLoader')
		 	->addArgument("Manifest.xml")
		 	->inRequestScope();
		 
		 $this->register('yogi\framework\settings\SettingsRepository', 
		 		'yogi\framework\settings\interfaces\ISettingsRepository')
		 	->addMethodCall('load')
		 	->inRequestScope();
		 
		 $this->register('yogi\framework\http\Headers', 
		 		'yogi\framework\http\interfaces\IHeaderRepository')
		 ->inRequestScope();
		 
		 $this->register(
		 		'yogi\framework\utils\AnnotationHandler',
		 		'yogi\framework\utils\interfaces\IAnnotationHandler')
		 		->inRequestScope();
		 $this->register(
		 		'yogi\framework\utils\handlers\FormFieldHandler',
		 		'yogi\framework\utils\handlers\interfaces\IFormFieldHandler');
		 $this->register(
		 		'yogi\framework\mvc\ModelBinder',
		 		'yogi\framework\mvc\interfaces\IModelBinder')
		 		->inRequestScope();
		 
		 $this->register(
		 		'yogi\framework\mvc\filter\FilterConfig',
		 		'yogi\framework\mvc\filter\interfaces\IFilterConfig')
		 		->inRequestScope();
		 
		 $this->register(
		 		'yogi\framework\io\Request',
		 		'yogi\framework\io\interfaces\IRequest');
		 
		 $this->register(
		 		'yogi\framework\mvc\ViewEngineRepository',
		 		'yogi\framework\mvc\interfaces\IViewEngineRepository');
		 
		 
		 $this->register(
		 		'yogi\framework\route\Router',
		 		'yogi\framework\route\interfaces\IRouter')
		 		->set('RouterConfig', new RouterConfig())
		 		->addMethodCall('init');
		 
		 $this->register(
		 		'yogi\framework\io\file\FileManager',
		 		'yogi\framework\io\file\interfaces\IFileManager'
		 );
		 
		 $this->register(
		 		'src\Application',
		 		'yogi\framework\IApplication')
		 		->addArgument(null)
		 		->addArgument(null)
		 		->set('ModelBinder', new Service('yogi\framework\mvc\interfaces\IModelBinder'));
		 
	}
	
	public function getRegister() {
		return $this->reg;
	}
	
	private function register($class, $for) {
		return $this->registerWithIdent($for, $class, $for);
	}
	
	private function registerWithIdent($ident, $class, $for) {
		$definition = new Definition($class);
		$this->reg->add($ident, $definition);
	
		return $definition;
	}
}