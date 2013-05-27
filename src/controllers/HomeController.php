<?php
class HomeController extends Controller {
	
	private $repository;
	
	
	public function __construct(IContentRepository $repo) {
		$this->repository = $repo;
	}
	
	public function index() {
		return $this->view(array("lol", "Derp", "Snerp"));
	}
	
}