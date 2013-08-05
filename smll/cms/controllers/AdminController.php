<?php
namespace smll\cms\controllers;

use smll\cms\framework\interfaces\IContentTypeBuilder;

use smll\cms\framework\content\utils\interfaces\IContentTypeRepository;

use smll\framework\security\interfaces\IRoleProvider;

use smll\framework\utils\ArrayList;

use smll\cms\models\PermissionsModel;

use smll\cms\models\PageTypesModel;

use smll\framework\utils\Boolean;

use smll\cms\framework\content\PageDataField;

use smll\framework\utils\Guid;

use smll\framework\utils\interfaces\IAnnotationHandler;

use smll\cms\framework\content\interfaces\IPageData;
use smll\framework\utils\HashMap;
use smll\cms\framework\content\utils\interfaces\IPageDataRepository;
use smll\cms\framework\content\PageData;
use smll\framework\mvc\Controller;

/**
 * [Authorize]
 * [InRoles(Roles=Administrator)]
 * @author ksdkrol
 *
 */
class AdminController extends Controller
{

    protected $contentTypeRepository = null;
    protected $pageTypeBuilder = null;

    /**
     *
     * @var IRoleProvider
     */
    private $roleProvider;

    public function __construct(
            IContentTypeRepository $loader,
            IContentTypeBuilder $pageTypeBuilder,
            IRoleProvider $roleProvider)
    {

        $this->contentTypeRepository = $loader;
        $this->pageTypeBuilder = $pageTypeBuilder;
        $this->roleProvider = $roleProvider;
    }

    public function index()
    {

    }

    public function users()
    {

        $userModel = new ArrayList();

        $users = $this->membership->getAllUsers();


        foreach ($users->getIterator() as $user) {
            $userData = array('membershipData' => '', 'roles' => '');
            $userData['membershipData'] = $user;
             
            $userData['roles'] = array();
            $roles = $this->roleProvider->getRolesForUser($user->getProviderName());
             
            if (isset($roles)) {
                foreach ($roles as $role) {
                    $userData['roles'][] = $role;
                }
                 
            }
            $userModel->add((object)$userData);
        }





        return $this->view($userModel);
    }

    public function roles()
    {

        return $this->view();
    }

    public function permissions()
    {
        $permissionsModel = new PermissionsModel();
        $permissionsModel->permissions = "lol";
        return $this->view($permissionsModel);
    }

    public function page_types()
    {

        $pageTypes = new PageTypesModel();
        $pageTypes->installed = $this->contentTypeRepository->getContentTypes('PageData');

        $uninstalled = $this->pageTypeBuilder->findPageTypes('PageData');
        foreach ($uninstalled->getIterator() as $index => $pageType) {
            if ($this->contentTypeRepository->getContentTypeByName($pageType->name) != null) {
                $uninstalled->remove($index);
            }
        }
        $pageTypes->uninstalled = $uninstalled;

        return $this->view($pageTypes);
    }
    
    public function content_types()
    {
    
        $pageTypes = new PageTypesModel();
        $pageTypes->installed = $this->contentTypeRepository->getContentTypes('ContentData');
    
        $uninstalled = $this->pageTypeBuilder->findPageTypes('ContentData');
        foreach ($uninstalled->getIterator() as $index => $pageType) {
            if ($this->contentTypeRepository->getContentTypeByName($pageType->name) != null) {
                $uninstalled->remove($index);
            }
        }
        $pageTypes->uninstalled = $uninstalled;
    
        return $this->view($pageTypes);
    }
    
    public function block_types()
    {
    
        $pageTypes = new PageTypesModel();
        $pageTypes->installed = $this->contentTypeRepository->getContentTypes('BlockData');
    
        $uninstalled = $this->pageTypeBuilder->findPageTypes('BlockData');
        foreach ($uninstalled->getIterator() as $index => $pageType) {
            if ($this->contentTypeRepository->getContentTypeByName($pageType->name) != null) {
                $uninstalled->remove($index);
            }
        }
        $pageTypes->uninstalled = $uninstalled;
    
        return $this->view($pageTypes);
    }

    public function rebuild_content_type($type, $dataType = 'PageData') 
    {

        $pageType = $this->contentTypeRepository->getContentTypeByName($type);

        $this->pageTypeBuilder->rebuildPageType($pageType, $dataType);

        return $this->redirectAction($dataType);
    }

    public function build_content_type($type, $dataType = 'PageData') 
    {
        $rClass = new \ReflectionClass($this->pageTypeBuilder->findPageType($type, $dataType));
        $this->pageTypeBuilder->buildPageType($rClass->newInstance(), $dataType);
        return $this->redirectAction($dataType);
    }
    
    protected function redirectAction($dataType) {
        
        $action = 'page-types';
        
        switch($dataType) {
            case 'PageData' :
                $action = 'page-types';
                break;
            
            case 'BlockData' :
                $action = 'block-types';
                break;
                
            case 'ContentData' : 
                $action = 'content-types';
                break;
        }
        return $this->redirectToAction($action);
    }
    
    public function fields()
    {
        return $this->view();
    }

}