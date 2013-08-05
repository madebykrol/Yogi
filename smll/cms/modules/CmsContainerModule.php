<?php
namespace smll\cms\modules;
use smll\framework\di\interfaces\IContainerModule;
use smll\framework\utils\HashMap;
use smll\framework\di\Definition;
use smll\framework\route\RouterConfig;
use smll\framework\di\Service;

class CmsContainerModule implements IContainerModule
{

    private $reg;

    public function __construct()
    {
        $this->reg = new HashMap();
        $this->init();
    }

    public function init()
    {

        $this->register('smll\framework\io\file\FileUploadManager',
                'smll\framework\io\file\interfaces\IFileUploadManager')
                ->addArgument($_FILES)
                ->inRequestScope();
        
        $this->register(
                'smll\framework\io\BrowserContext',
                'smll\framework\io\interfaces\IBrowserContext');
         
        $this->register(
                'smll\cms\framework\content\taxonomy\SqlTaxonomyRepository',
                'smll\cms\framework\content\taxonomy\interfaces\ITaxonomyRepository')
                ->inRequestScope();
         
        $this->register(
                'smll\cms\framework\content\files\SqlFileRepository',
                'smll\cms\framework\content\files\interfaces\IFileRepository')
                ->inRequestScope();

        $this->register('smll\cms\framework\ui\FieldTypeFactory',
                'smll\cms\framework\ui\interfaces\IFieldTypeFactory')
                ->inRequestScope();
        
        $this->register(
                'smll\cms\framework\content\utils\SqlContentRepository',
                'smll\cms\framework\content\utils\interfaces\IContentRepository')
                ->inRequestScope();

        $this->register('smll\framework\utils\AnnotationHandler',
                'smll\framework\utils\interfaces\IAnnotationHandler');
         
        $this->register('smll\framework\security\SqlMembershipProvider',
                'smll\framework\security\interfaces\IMembershipProvider')
                ->inRequestScope();
         
        $this->register('smll\framework\security\SqlRoleProvider',
                'smll\framework\security\interfaces\IRoleProvider');
         
        $this->register('smll\cms\framework\content\utils\SqlPageDataRepository',
                'smll\cms\framework\content\utils\interfaces\IPageDataRepository')
                ->inRequestScope();
        
        $this->register('smll\cms\framework\content\utils\SqlContentTypeRepository',
                'smll\cms\framework\content\utils\interfaces\IContentTypeRepository')
                ->inRequestScope();
         
        $this->register('smll\cms\framework\security\SqlContentPermissionHandler',
                'smll\cms\framework\security\interfaces\IContentPermissionHandler')
                ->inRequestScope();
         
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
                'smll\cms\framework\mvc\CmsModelBinder',
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
                'smll\cms\framework\ContentTypeBuilder',
                'smll\cms\framework\interfaces\IContentTypeBuilder'
        );
         
        $this->register(
                'smll\cms\framework\route\Router',
                'smll\framework\route\interfaces\IRouter')
                ->set('RouterConfig', new RouterConfig())
                ->addMethodCall('init');
         
        $this->register(
                'src\Application',
                'smll\framework\IApplication')
                ->addArgument(null)
                ->addArgument(null)
                ->set('ModelBinder', new Service('smll\framework\mvc\interfaces\IModelBinder'));
         
    }

    public function getRegister()
    {
        return $this->reg;
    }

    private function register($class, $for)
    {
        return $this->registerWithIdent($for, $class, $for);
    }

    private function registerWithIdent($ident, $class, $for)
    {
        $definition = new Definition($class);
        $this->reg->add($ident, $definition);
        return $definition;
    }
}