<?php
namespace smll\cms\framework\content\fieldtype\interfaces;

use smll\framework\io\file\interfaces\IFileUploadManager;

interface IFileFieldType extends IFieldType {
	public function setFileUploadManager(IFileUploadManager $manager);
}