<?php
namespace src\controllers;
use smll\cms\framework\content\PageReference;

use smll\cms\framework\PageController;
use src\content\pages\BasicPagePage;
use smll\cms\framework\content\utils\interfaces\IContentRepository;
/**
 * @author ksdkrol
 * [ContentType(BasicPage)]
 */
class BasicController extends PageController {
	
	private $contentRepository = null;
	
	public function __construct(IContentRepository $contentRepository) {
		$this->contentRepository = $contentRepository;
	}
	
	/**
	 * @return ViewResult
	 */
	public function index(PageReference $currentPage) {
		$this->viewBag['title'] = $currentPage->getTitle();
		return $this->view($this->contentRepository->getPageData($currentPage));
	}
	
}