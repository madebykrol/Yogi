<?php
namespace smll\cms\controllers;

use smll\cms\framework\content\interfaces\IContent;

use smll\framework\mvc\Controller;

class BlocksController extends Controller
{
    public function index()
    {
        return $this->view();
    }
    
    
    /**
     * [InRole(Role=Editor|Administrator)]
     * @param unknown $type
     * @return \smll\framework\mvc\ViewResult
     */
    public function create($type)
    {
        // get PageData from data type;
        $type = $this->pageDataRepository->getPageTypeByName($type);
        $type->authorName = $this->getPrincipal()->getIdentity()->getName();
    
        return $this->view($type);
    }
    
    /**
     * [InRole(Role=Editor|Administrator)]
     * @param IContent $type
     * @return \smll\framework\mvc\ViewResult
     */
    public function post_create(IContent $type)
    {
    
        if ($this->modelState->isValid()) {
            $page = $this->pageDataRepository->addPage($type);
    
            if ($page !== FALSE) {
                $this->redirectToAction('display', null, new HashMap(array('id' => $page->ident)));
            }
    
        }
        return $this->view($type);
    }
}