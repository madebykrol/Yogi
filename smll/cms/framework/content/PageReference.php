<?php
namespace smll\cms\framework\content;
use smll\framework\utils\ArrayList;

use smll\cms\framework\content\utils\interfaces\IContentRepository;

use smll\cms\framework\content\interfaces\IPageData;

use smll\framework\utils\HashMap;

use smll\cms\framework\content\interfaces\IPageReference;

class PageReference implements IPageReference {
	
	private $children;
	private $pageData;
	private $id;
	private $ident;
	private $title;
	private $visibleInMenu;
	private $externalUrl;
	private $author;
	private $parent;
	private $pageTypeId;
	
	
	public function __construct() {
		$this->children = new ArrayList();

	}
	
	public function getPageTypeId() {
		return $this->pageTypeId;
	}
	
	public function setPageTypeId($id) {
		$this->pageTypeId = $id;
	}

	
	public function setPageData(IPageData $data) {
		$this->pageData = $data;
	}
	
	public function hasChildren() {
		return ($this->children->getLength()>0); 
	}
	
	public function getChildren() {
		return $this->children;
	}
	
	public function setChildren(ArrayList $children) {
		$this->children = $children;
	}
	
	public function getIdent() {
		return $this->ident;
	}
	public function setIdent($ident) {
		$this->ident = $ident;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function isVisibleInMenu($boolean = null) {
		if(is_bool($boolean)) {
			$this->visibleInMenu = $boolean;
		}
		return $this->visibleInMenu;
	}
	
	public function getExternalUrl() {
		return $this->externalUrl;
	}
	public function setExternalUrl($url) {
		$this->externalUrl = $url;
	}
	
	public function getAuthor() {
		return $this->author;
	}
	
	public function setAuthor($author) {
		$this->author = $author;
	}
}