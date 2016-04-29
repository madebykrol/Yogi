<?php
namespace yogi\framework\io\file\interfaces;

use yogi\framework\utils\Guid;

interface IFileManager {
	/**
	 * @return IFileReference
	 * @param Guid $reference
	 */
	public function getFileReference(Guid $reference);
	
	
	public function createFileReference();
}