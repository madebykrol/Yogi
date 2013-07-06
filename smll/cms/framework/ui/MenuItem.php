<?php
namespace smll\cms\framework\ui;
use smll\framework\utils\ArrayList;

use smll\cms\framework\ui\interfaces\IMenuItem;

class MenuItem implements IMenuItem {
	
	protected $title;
	protected $link;
	protected $id;
	
	protected $children;
	
	protected $isActive = false;
	protected $activeTrail = false;
	
	public function __construct() {
		$this->children = new ArrayList();
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	public function setLink($link) {
		$this->link = $link;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getLink() { 
		return $this->link;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function addChild(IMenuItem $item) {
		$this->children->add($item);
	}
	public function removeChild($item) {
		$this->children->remove($item);
	}
	public function getChildren() {
		return $this->children;
	}
	public function hasChildren() {
		return ($this->children->getLength() > 0);
	}
	
	public function isActive($boolean = null) {
		if(is_bool($boolean)) {
			$this->isActive = $boolean;
		}
		
		return $this->isActive;
	}
	
	public function activeTrail($boolean = null) {
		if(is_bool($boolean)) {
			$this->activeTrail = $boolean;
		}
		
		return $this->activeTrail;
	}
}