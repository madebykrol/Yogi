<?php
namespace yogi\framework\io\db\interfaces;

interface IDataStoreManager {
	public function createStore($class);
	public function truncateStore($class);
}