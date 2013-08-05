<?php
namespace smll\cms\controllers;

use smll\cms\framework\content\utils\interfaces\IContentTypeRepository;

use smll\framework\utils\Guid;

use smll\cms\framework\content\interfaces\IPageData;
use smll\framework\utils\HashMap;
use smll\cms\framework\content\utils\interfaces\IPageDataRepository;
use smll\cms\framework\content\PageData;
use smll\framework\mvc\Controller;

/**
 * [Authorize]
 *
 * @author ksdkrol
 *
 */
class PagesController extends Controller
{

    protected $pageDataRepository = null;
    protected $contentTypeRepository = null;

    public function __construct(IPageDataRepository $loader, 
            IContentTypeRepository $typeRepo)
    {
        $this->pageDataRepository = $loader;
        $this->contentTypeRepository = $typeRepo;
    }

    /**
     * [InRole(Role=Editor|Administrator)]
     * @return \smll\framework\mvc\ViewResult
     */
    public function index()
    {
        return $this->view();
    }

    /**
     * [InRole(Role=Editor|Administrator)]
     * @param unknown $id
     * @return \smll\framework\mvc\ViewResult
     */
    public function edit($id) 
    {
        
        $pageReference = $this->pageDataRepository->getPageReference(Guid::parse($id));
        $page = $this->pageDataRepository->getPageData($pageReference);
        
        $this->viewBag['title'] = 'Edit - '.$page->title;
        
        return $this->view($page);
    }

    /**
     * [InRole(Role=Editor|Administrator)]
     * @param unknown $id
     * @return \smll\framework\mvc\ViewResult
     */
    public function remove($id)
    {
        $this->pageDataRepository->removePage($id);
        return $this->redirectToAction('edit', 'Cms');
    }

    /**
     * [InRole(Role=Editor|Administrator)]
     * @param IPageData $page
     * @return \smll\framework\mvc\ViewResult
     */
    public function post_edit(IPageData $page)
    {
        if ($this->modelState->isValid()) {
            $this->pageDataRepository->addPage($page);
             
            return $this->redirectToAction('display', 'Pages', new HashMap(array('id' => $page->ident)));
        }

        return $this->view($page);
    }

    public function display($id)
    {
        $pageReference = $this->pageDataRepository->getPageReference(Guid::parse($id));
        $page = $this->pageDataRepository->getPageData($pageReference);
        
        $this->viewBag['title'] = 'View - '.$page->title;
        
        return $this->view($page);
    }

    /**
     * [InRole(Role=Editor|Administrator)]
     * @param unknown $type
     * @return \smll\framework\mvc\ViewResult
     */
    public function create($type)
    {
        // get PageData from data type;
        $type = $this->contentTypeRepository->getContentTypeByName($type);
        $type->authorName = $this->getPrincipal()->getIdentity()->getName();
        
        return $this->view($type);
    }

    /**
     * [InRole(Role=Editor|Administrator)]
     * @param IPageData $type
     * @return \smll\framework\mvc\ViewResult
     */
    public function post_create(IPageData $type)
    {
        
        if ($this->modelState->isValid()) {
            $page = $this->pageDataRepository->addPage($type);

            if ($page !== FALSE) {
                $this->redirectToAction('display', null, new HashMap(array('id' => $page->ident)));
            }

        }
        return $this->view($type);
    }

    /**
     * [InRole(Role=Editor|Administrator)]
     * @return string
     */
    public function recentContent()
    {
        return "...";
    }
    /**
     * [InRole(Role=Editor|Administrator)]
     * @param unknown $id
     */
    public function publish($id)
    {
        $this->pageDataRepository->publishPage($id);
    }
    
    /**
     * [InRole(Role=Editor|Administrator)]
     * @param unknown $id
     */
    public function unpublish($id)
    {
        $this->pageDataRepository->unpublishPage($id);
    }

}