<?php
namespace smll\cms\controllers;

use smll\cms\framework\content\utils\interfaces\IPageDataRepository;

use smll\framework\mvc\Controller;

class BrowserController extends Controller
{

    /**
     * @var IPageDataRepository
     */
    private $pageDataRepository;

    public function __construct(IPageDataRepository $pageDataRepository)
    {
        $this->pageDataRepository = $pageDataRepository;
    }

    public function page($type = null)
    {
        $this->pageDataRepository->getRootPage();
        return $this->view();
    }
}