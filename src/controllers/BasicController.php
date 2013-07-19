<?php
namespace src\controllers;
use smll\cms\framework\content\PageReference;

use smll\cms\framework\PageController;
use src\content\pages\BasicPagePage;
/**
 * @author ksdkrol
 * [ContentType(BasicPage)]
 */
class BasicController extends PageController {
	
	/**
	 * @return ViewResult
	 */
	public function index(PageReference $currentPage) {
		$this->viewBag['title'] = $currentPage->getTitle();
    return $this->view($currentPage);
	}
	
}