<?php
namespace yogi\framework\io\db\interfaces;

interface IDataStoreManager {
	public function createStore(string $class);
	public function truncateStore(string $class);
}