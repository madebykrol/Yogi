<?php
namespace src\models;
class NewsListModel {
	
	protected $items;
	
	public function setNewsItems($array) {
		$this->items = $array;
	}
	
	public function getItems() {
		return $this->items;
	}
	
}