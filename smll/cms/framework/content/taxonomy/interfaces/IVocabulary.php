<?php
namespace smll\cms\framework\content\taxonomy\interfaces;

use smll\framework\utils\HashMap;

interface IVocabulary {
	public function getName();
	public function getDescription();
	public function getId();
	public function getTerms();
	
	public function setName($name);
	public function setDescription($description);
	public function setId($id);
	public function setTerms(HashMap $terms);
}