<?php
namespace smll\cms\controllers;

use smll\cms\framework\content\utils\interfaces\IContentRepository;

use smll\framework\mvc\Controller;

class BrowserController extends Controller
{

    /**
     * @var IContentRepository
     */
    private $contentRepository;

    public function __construct(IContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    public function page($type = null)
    {
        $this->contentRepository->getRootPage();
        return $this->view();
    }
}