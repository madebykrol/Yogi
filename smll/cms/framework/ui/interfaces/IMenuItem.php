<?php
namespace smll\cms\framework\ui\interfaces;
use smll\cms\framework\ui\interfaces\IMenuItem;

interface IMenuItem {
		public function setTitle($link);
		public function setLink($link);
		
		public function getLink();
		public function getTitle();
		
		public function getId();
		public function setId($id);
		
		public function addChild(IMenuItem $item);
		public function removeChild($item);
		public function getChildren();
		public function hasChildren();
		
		public function isActive($boolean = null);
		public function activeTrail($boolean = null);
}