<?php
class ApiController extends Controller {
	public function index() {
		
		return JsonConverter::serializeObject((object)array('Poop' => 'scoop'));
	}
}