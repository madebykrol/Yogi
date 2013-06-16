<?php
class DefaultContainerModule implements IContainerModule {
	
	private $reg; 
	
	public function __construct() {
		$this->reg = new HashMap();
		$this->init();
	}
	
	public function init() {
		 $this->register('AnnotationHandler', 'IAnnotationHandler');
		 $this->register('SqlMembershipProvider', 'IMembershipProvider')
		 ->inRequestScope();
		 $this->register('SqlRoleProvider', 'IRoleProvider');
		 $this->register('Session', 'ISession')->addArgument(array());
		 $this->register('FormAuthentication', 'IAuthenticationProvider');
		 $this->register('Crypt', 'ICryptographer');
		 $this->register('ModelState', 'IModelState')->inRequestScope();
		 $this->register('XmlSettingsLoader', 'ISettingsLoader')
		 ->addArgument("Manifest.xml")
		 ->inRequestScope();
		 $this->register('SettingsRepository', 'ISettingsRepository')
		 ->addMethodCall('load')
		 ->inRequestScope();
		 
		 $this->register('Headers', 'IHeaderRepository')
		 ->inRequestScope();
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