<?php
namespace smll\cms\controllers;
use smll\cms\models\PermissionsModel;

use smll\cms\models\PageTypesModel;

use smll\cms\framework\interfaces\IPageTypeBuilder;

use smll\framework\utils\Boolean;

use smll\cms\framework\content\PageDataField;

use smll\framework\utils\Guid;

use smll\framework\utils\interfaces\IAnnotationHandler;

use smll\cms\framework\content\interfaces\IPageData;
use smll\framework\utils\HashMap;
use smll\cms\framework\content\utils\interfaces\IContentRepository;
use smll\cms\framework\content\PageData;
use smll\framework\mvc\Controller;

/**
 * [Authorize]
 * @author ksdkrol
 *
 */
class AdminController extends Controller {
	
	protected $contentRepository = null;
	protected $pageTypeBuilder = null;
	
	public function __construct(IContentRepository $loader, IPageTypeBuilder $pageTypeBuilder) {
		$this->contentRepository = $loader;
		$this->pageTypeBuilder = $pageTypeBuilder;
	}
	
	public function index() {
		
	}
	
	public function users() {
		
		return $this->view();
	}
	
	public function roles() {
	
		return $this->view();
	}
	
	public function permissions() {
		$permissionsModel = new PermissionsModel();
		$permissionsModel->permissions = "lol";
		return $this->view($permissionsModel);
	}
	
	public function page_types() {
		
		$pageTypes = new PageTypesModel();
		$pageTypes->installed = $this->contentRepository->getPageTypes();
		
		$uninstalled = $this->pageTypeBuilder->findPageTypes();
		foreach($uninstalled->getIterator() as $index => $pageType) {
			if($this->contentRepository->getPageTypeByName($pageType->name) != null) {
				$uninstalled->remove($index);
			}
		}
		$pageTypes->uninstalled = $uninstalled;
		
		return $this->view($pageTypes);
	}
	
	public function rebuild_page_type($type) {
		
		$pageType = $this->contentRepository->getPageTypeByName($type);
		
		$this->pageTypeBuilder->rebuildPageType($pageType);
		
		return $this->redirectToAction('page-types');
	}
	
	public function build_page_type($type) {
	
		$rClass = new \ReflectionClass($this->pageTypeBuilder->findPageType($type));
		
		$this->pageTypeBuilder->buildPageType($rClass->newInstance());
	
		
		return $this->redirectToAction('page-types');
	}
	
}