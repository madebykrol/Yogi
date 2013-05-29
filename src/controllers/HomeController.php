<?php
class HomeController extends Controller {
	
	private $repository;
	
	public function __construct(IContentRepository $repo) {
		$this->repository = $repo;
	}
	
	/**
	 * @return ViewResult
	 */
	public function index() {
		$this->viewBag['title'] = "My smll site";
		return $this->view(array("lol", "Derp", "Snerp"));
	}
	
	
	public function about() {
		$this->viewBag['title'] = "About | My smll site";
		return $this->view(array("lol", "Derp", "Snerp"));
	}
	
}