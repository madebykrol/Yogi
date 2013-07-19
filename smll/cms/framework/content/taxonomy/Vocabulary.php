<?php
namespace smll\cms\framework\content\taxonomy;

use smll\framework\utils\HashMap;

use smll\cms\framework\content\taxonomy\interfaces\IVocabulary;

class Vocabulary implements IVocabulary {
	
	private $name;
	private $decription;
	private $id;
	private $terms;
	
	public function getName() {
		return $this->name;
	}
	public function getDescription() {
		return $this->description;
	}
	public function getId() {
		return $this->id;
	}
	public function getTerms() {
		return $this->terms;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	public function setDescription($description) {
		$this->description = $description;
	}
	public function setId($id) {
		$this->id = $id;
	}
	public function setTerms(HashMap $terms) {
		$this->terms = $terms;
	}
}