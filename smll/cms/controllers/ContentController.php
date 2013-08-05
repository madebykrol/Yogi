<?php
namespace smll\cms\controllers;

use smll\cms\framework\content\interfaces\IContent;

use smll\cms\framework\interfaces\IContentTypeBuilder;

use smll\cms\framework\content\utils\interfaces\IContentTypeRepository;

use smll\cms\models\ContentTypesViewModel;

use smll\framework\mvc\Controller;

class ContentController extends Controller
{
    /**
     * 
     * @var IContentTypeRepository
     */
    private $contentTypeRepository = null;
    
    private $builder;
    
    public function __construct(IContentTypeRepository $typeRepository,
            IContentTypeBuilder $builder)
    {
        $this->contentTypeRepository = $typeRepository;
        $this->builder = $builder;
    }
    
    /**
     * [InRole(Role=Editor|Administrator)]
     * @param unknown $type
     * @return \smll\framework\mvc\ViewResult
     */
    public function create($type)
    {
        $this->contentTypeRepository->getContentTypeByName($type);
        return $this->view($this->contentTypeRepository->getContentTypeByName($type));
    }
    
    /**
     * [InRole(Role=Editor|Administrator)]
     * @param IContent $type
     * @return \smll\framework\mvc\ViewResult
     */
    public function post_create(IContent $type)
    {
        print_r($type);
        return $this->view($type);
    }
    
    public function index() {
        
        $contentTypesViewModel = new ContentTypesViewModel();
        $contentTypesViewModel->contentTypes = $this->contentTypeRepository->getContentTypes('ContentData');
        
        
        
        return $this->view($contentTypesViewModel);
    }
}