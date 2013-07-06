<?php
namespace smll\cms\framework\ui\interfaces;
interface IMenuTree {
	public function addItem(IMenuItem $item);
	public function removeItem($item);
	public function getItems();
}