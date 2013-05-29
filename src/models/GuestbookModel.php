<?php
class GuestbookModel {
	private $entries = array();
	
	public function getEntries() {
		return $this->entries;
	}
	
	public function setEntries($entries) {
		$this->entries = $entries;
	}
	
}