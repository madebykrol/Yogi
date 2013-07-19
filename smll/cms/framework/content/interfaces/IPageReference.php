<?php
namespace smll\cms\framework\content\interfaces;
use smll\framework\utils\ArrayList;

use smll\framework\utils\HashMap;

interface IPageReference {
	
	
	/**
	 * @return PageDataCollection
	 */
	public function getChildren();
	public function setChildren(ArrayList $children);
	
	public function getIdent();
	public function setIdent($ident);
	
	public function setId($id);
	public function getId();
	
	public function setTitle($title);
	public function getTitle();
	
	public function isVisibleInMenu($boolean = null);
	
	public function getExternalUrl();
	public function setExternalUrl($url);
	
	public function getPageTypeId();
	public function setPageTypeId($id);
}