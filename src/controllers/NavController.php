<?php
namespace src\controllers;
use smll\cms\framework\content\PageReference;

use smll\cms\framework\content\interfaces\IPageReference;

use smll\cms\framework\ui\MenuItem;

use smll\cms\framework\content\utils\interfaces\IContentRepository;

use smll\cms\framework\ui\MenuTree;

use smll\framework\mvc\Controller;

class NavController extends Controller {
	
	private $contentRepository;
	
	public function __construct(IContentRepository $repo) {
		$this->contentRepository = $repo;
	}
	
	public function topNav() {
		$model = (object)array(
				'authenticated' => $this->user->getIdentity()->isAuthenticated(),
				'isAdmin' => $this->user->isInRole('Administrator'),
				'isEditor' => $this->user->isInRole('Editor'),
				'nav'	=> null,
		);
		
		$tree = new MenuTree();
		$active = null;
		$rootPage = $this->contentRepository->getRootPage();
		foreach($rootPage->getChildren()->getIterator() as $id => $page) {
			if($page->isVisibleInMenu()) {
				
				$item = new MenuItem();
				$item->setTitle($page->getTitle());
				$item->setLink($page->getExternalUrl());
				
				if($active == $page->getId()) {
					$item->isActive(true); 	
				}
				
				$tree->addItem($item);
			}
		}

		$model->nav = $tree;
		
		return $this->view($model);
	}
	
	/**
	 * [OutputCache(CacheDuration=20, VaryByParam=id)]
	 * @param IPageReference $currentPage
	 */
	public function leftNav(IPageReference $currentPage) {
		$children = $currentPage->getChildren();
		
		return $this->view($children);
	}
	
	public function breadCrumb(IPageReference $currentPage) {
		
	}
}