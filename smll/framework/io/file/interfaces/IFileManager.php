<?php
namespace smll\framework\io\file\interfaces;

use smll\framework\utils\Guid;

interface IFileManager {
	/**
	 * @return IFileReference
	 * @param Guid $reference
	 */
	public function getFileReference(Guid $reference);
	
	
	public function createFileReference();
}