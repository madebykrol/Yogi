<?php
namespace smll\modules;
use smll\framework\di\interfaces\IContainerModule;
use smll\framework\utils\HashMap;
use smll\framework\di\Definition;
use smll\framework\route\RouterConfig;
use smll\framework\di\Service;

class DefaultContainerModule implements IContainerModule {
	
	private $reg; 
	
	public function __construct() {
		$this->reg = new HashMap();
		$this->init();
	}
	
	public function init() {
		
		 $this->register('smll\framework\io\file\FileUploadManager',
				'smll\framework\io\file\interfaces\IFileUploadManager')
				->addArgument($_FILES);
		 
		 $this->register('smll\framework\utils\AnnotationHandler', 
		 		'smll\framework\utils\interfaces\IAnnotationHandler');
		 
		 $this->register('smll\framework\security\SqlMembershipProvider', 
		 		'smll\framework\security\interfaces\IMembershipProvider')
		 	->inRequestScope();
		 
		 $this->register('smll\framework\security\SqlRoleProvider', 
		 		'smll\framework\security\interfaces\IRoleProvider');
		 
		 $this->register('smll\framework\http\Session', 
		 		'smll\framework\http\interfaces\ISession')
		 	->addArgument(array());
		 
		 $this->register('smll\framework\security\authentication\FormAuthentication', 
		 		'smll\framework\security\interfaces\IAuthenticationProvider');
		 
		 $this->register('smll\framework\security\Crypt', 
		 		'smll\framework\security\interfaces\ICryptographer');
		 
		 $this->register('smll\framework\mvc\ModelState', 
		 		'smll\framework\mvc\interfaces\IModelState')
		 	->inRequestScope();
		 
		 $this->register('smll\framework\io\XmlSettingsLoader', 
		 		'smll\framework\settings\interfaces\ISettingsLoader')
		 	->addArgument("Manifest.xml")
		 	->inRequestScope();
		 
		 $this->register('smll\framework\settings\SettingsRepository', 
		 		'smll\framework\settings\interfaces\ISettingsRepository')
		 	->addMethodCall('load')
		 	->inRequestScope();
		 
		 $this->register('smll\framework\http\Headers', 
		 		'smll\framework\http\interfaces\IHeaderRepository')
		 ->inRequestScope();
		 
		 $this->register(
		 		'smll\framework\utils\AnnotationHandler',
		 		'smll\framework\utils\interfaces\IAnnotationHandler')
		 		->inRequestScope();
		 $this->register(
		 		'smll\framework\utils\handlers\FormFieldHandler',
		 		'smll\framework\utils\handlers\interfaces\IFormFieldHandler');
		 $this->register(
		 		'smll\framework\mvc\ModelBinder',
		 		'smll\framework\mvc\interfaces\IModelBinder')
		 		->inRequestScope();
		 
		 $this->register(
		 		'smll\framework\mvc\filter\FilterConfig',
		 		'smll\framework\mvc\filter\interfaces\IFilterConfig')
		 		->inRequestScope();
		 
		 $this->register(
		 		'smll\framework\io\Request',
		 		'smll\framework\io\interfaces\IRequest');
		 
		 $this->register(
		 		'smll\framework\mvc\ViewEngineRepository',
		 		'smll\framework\mvc\interfaces\IViewEngineRepository');
		 
		 $this->register(
		     	'smll\framework\io\db\ServiceDataStore',
		 		'smll\framework\io\db\interfaces\IServiceDataStore'
		 		);
		 
		 $this->register(
		 		'smll\framework\route\Router',
		 		'smll\framework\route\interfaces\IRouter')
		 		->set('RouterConfig', new RouterConfig())
		 		->addMethodCall('init');
		 
		 $this->register(
		 		'smll\framework\io\file\FileManager',
		 		'smll\framework\io\file\interfaces\IFileManager'
		 );
		 
		 $this->register(
		 		'src\Application',
		 		'smll\framework\IApplication')
		 		->addArgument(null)
		 		->addArgument(null)
		 		->set('ModelBinder', new Service('smll\framework\mvc\interfaces\IModelBinder'));
		 
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