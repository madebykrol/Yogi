<?php
namespace smll\cms\framework\content\fieldtype\interfaces;

use smll\cms\framework\content\files\interfaces\IFileRepository;

use smll\cms\framework\content\utils\interfaces\IContentRepository;

use smll\framework\io\file\interfaces\IFileUploadManager;

interface IFileFieldType extends IFieldType
{
    public function setFileUploadManager(IFileUploadManager $manager);
    public function setFileRepository(IFileRepository $repository);
}