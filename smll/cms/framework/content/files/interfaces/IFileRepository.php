<?php
namespace smll\cms\framework\content\files\interfaces;
use smll\framework\utils\Guid;

use smll\framework\io\file\File;

use smll\framework\io\file\interfaces\IFileReference;

interface IFileRepository {
	
	public function getFileReference($file);
	public function setFileReference(IFileReference $fileReference);
	public function createFileReference(File $file, Guid $ident = null);
	public function removeFileReference($ident);
	
	
}