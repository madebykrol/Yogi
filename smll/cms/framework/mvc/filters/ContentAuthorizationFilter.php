<?php
namespace smll\cms\framework\mvc\filters;

use smll\cms\framework\security\interfaces\IContentPermissionHandler;
use smll\framework\exceptions\AccessDeniedException;
use smll\framework\utils\Guid;
use smll\cms\framework\PageController;
use smll\framework\utils\interfaces\IAnnotationHandler;
use smll\cms\controllers\ContentController;
use smll\cms\framework\content\utils\interfaces\IContentRepository;
use smll\framework\mvc\filter\FilterAttribute;
use smll\framework\mvc\filter\interfaces\IAuthorizationFilter;
use smll\framework\security\interfaces\IMembershipProvider;
use smll\framework\mvc\filter\AuthorizationContext;
use smll\framework\mvc\ViewResult;
use smll\framework\mvc\interfaces\IViewResult;
use smll\framework\utils\HashMap;

class ContentAuthorizationFilter extends FilterAttribute implements IAuthorizationFilter
{

    /**
     * [Inject(IMembershipProvider)]
     * @var IMembershipProvider
     */
    private $membership;

    private $contentRepository;

    private $permissionHandler;

    public function setMembership(IMembershipProvider $membership)
    {
        $this->membership = $membership;
    }

    public function setContentRepository(IContentRepository $contentRepo)
    {
        $this->contentRepository = $contentRepo;
    }

    public function setContentPermissionHandler(
            IContentPermissionHandler $permissionHandler)
    {
        $this->permissionHandler = $permissionHandler;
    }

    public function onAuthorization(AuthorizationContext $context)
    {

        $identity = $context->getController()->getPrincipal()->getIdentity();
         
        $userRoles = array();
        if ($identity->isAuthenticated()) {
            $userRoles[] = 'User';
        } else {
            $userRoles[] = 'Anonymous';
        }
         
        if ($identity->isAuthenticated() 
                && $context->getController()->getPrincipal()->getRoles() != null) {
            foreach (
                    $context->getController()->getPrincipal()->getRoles()->getIterator()
                    as $role) {
                $userRoles[] = $role;
            }
        }

        $hasPermission = false;
        $pagePermissions = array();
        $event = "View";

        if ($context->getController() instanceof ContentController) {
            // Assume the user is either trying to edit a page some way.
            if ($context->getParameters() != null) {

                $event = $context->getAction()->getName();

                $pageTypeId = null;
                if ($event == self::CMS_ACTION_CREATE) {
                     
                    $pageTypeId = $this->contentRepository->getPageTypeId($context->getParameters()->get('type'));
                     
                    $event = self::PERMISSION_EVENT_CREATE;
                } else {
                    if ($event == self::CMS_ACTION_VIEW) {
                        $event = self::PERMISSION_EVENT_VIEW;
                    }

                    $pageId = $context->getParameters()->get('id');
                    $pageRef = null;
                    if (($guid = Guid::parse($pageId)) != null) {
                        $pageId = $guid;
                    }
                    $pageRef = $this->contentRepository->getPageReference($pageId);

                    $pageTypeId = $pageRef->getPageTypeId();
                }

                $event = ucfirst($event);
                $pagePermissions = $this->permissionHandler->getRolesForPageType($pageTypeId, $event);

            }
             
        }

        if ($context->getController() instanceof PageController) {
            $pageId = $context->getParameters()->get('ident');
            $pageRef = null;
            if (($guid = Guid::parse($pageId)) != null) {
                $pageId = $guid;
            }
             
            $event = ucfirst(self::PERMISSION_EVENT_VIEW);
             
            $pageRef = $this->contentRepository->getPageReference($pageId);
            if ($pageRef->getAuthor() == $context->getController()->getPrincipal()->getIdentity()->getName()) {

            }
            $pagePermissions = $this->permissionHandler->getRolesForPageType($pageRef->getPageTypeId(), $event);
        }



        if ($context->getController() instanceof ContentController
                || $context->getController() instanceof PageController)  {

            if (is_array($pagePermissions)) {
                foreach ($pagePermissions as $permission) {
                    if ($permission->event == $event
                            && in_array($permission->role, $userRoles)) {
                        $hasPermission = true;
                         
                        break;
                    }
                }
                 
                if (!$hasPermission) {
                    throw new AccessDeniedException();
                }
            }
        }
        return;
    }

    public function redirect (AuthorizationContext $context, $location) {
        $result = new ViewResult();
         
        $headers = new HashMap();
        $headers->add("Location", $location);
         
        $result->setHeaders($headers);
         
        $context->setResult($result);
    }


    const CMS_ACTION_VIEW         = 'display';
    const CMS_ACTION_CREATE       = 'create';

    const PERMISSION_EVENT_VIEW   = 'view';
    const PERMISSION_EVENT_CREATE = 'create';
}