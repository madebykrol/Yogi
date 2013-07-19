<?php
namespace smll\cms\controllers;
use smll\cms\framework\content\interfaces\IPageData;
use smll\framework\utils\HashMap;
use smll\cms\framework\content\utils\interfaces\IContentRepository;
use smll\cms\framework\content\PageData;
use smll\framework\mvc\Controller;

/**
 * [Authorize]
 * 
 * @author ksdkrol
 *
 */
class ContentController extends Controller {
	
	protected $contentRepository = null;
	
	
	public function __construct(IContentRepository $loader) {
		$this->contentRepository = $loader;
	}
	
	/**
	 * [InRole(Role=Editor)]
	 * @return \smll\framework\mvc\ViewResult
	 */
	public function index() {
		return $this->view();
	}
	
	/**
	 * [InRole(Role=Editor)]
	 * @param unknown $id
	 * @return \smll\framework\mvc\ViewResult
	 */
	public function edit($id) {
		$pageReference = $this->contentRepository->getPageReference($id);
		$page = $this->contentRepository->getPageData($pageReference);
		return $this->view($page);
	}
	
	/**
	 * [InRole(Role=Editor)]
	 * @param unknown $id
	 * @return \smll\framework\mvc\ViewResult
	 */
	public function remove($id) {
		$this->contentRepository->removePage($id);
		return $this->redirectToAction('edit', 'Cms');
	}
	
	/**
	 * [InRole(Role=Editor)]
	 * @param IPageData $page
	 * @return \smll\framework\mvc\ViewResult
	 */
	public function post_edit(IPageData $page) {
		if($this->modelState->isValid()) {
			$this->contentRepository->addPage($page);
			
			return $this->redirectToAction('display', 'Content', new HashMap(array('id' => $page->id)));
		}
		
		return $this->view($page);
	}
	
	public function display($id) {
		
		$pageReference = $this->contentRepository->getPageReference($id);
		$page = $this->contentRepository->getPageData($pageReference);
		
		
		return $this->view($page);
	}
	
	/**
	 * [InRole(Role=Editor)]
	 * @param unknown $type
	 * @return \smll\framework\mvc\ViewResult
	 */
	public function create($type) {
		// get PageData from data type;
		$type = $this->contentRepository->getPageTypeByName($type);
		
		$type->authorName = $this->getPrincipal()->getIdentity()->getName();
		
		return $this->view($type);
	}
	
	/**
	 * [InRole(Role=Editor)]
	 * @param IPageData $type
	 * @return \smll\framework\mvc\ViewResult
	 */
	public function post_create(IPageData $type) {
		if($this->modelState->isValid()) {
			$page = $this->contentRepository->addPage($type);
			if($page !== FALSE) {
				$this->redirectToAction('display', null, new HashMap(array('id' => $page->id)));
			}
		
		}
		return $this->view($type);
	}
	
	/**
	 * [InRole(Role=Editor)]
	 * @return string
	 */
	public function recentContent() {
		
		return "...";
	}
	
}