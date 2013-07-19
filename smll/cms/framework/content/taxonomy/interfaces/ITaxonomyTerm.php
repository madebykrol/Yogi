<?php
namespace smll\cms\framework\content\taxonomy\interfaces;

interface ITaxonomyTerm {
	
	public function getId();
	public function getTitle();
	public function getDescription();
	public function getParent();
	public function getShort();
	
	public function setId($id);
	public function setTitle($title);
	public function setDescription($description);
	public function setParent($parent);
	public function setShort($short);
}