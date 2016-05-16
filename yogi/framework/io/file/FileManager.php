<?php
namespace yogi\framework\io\file;

use yogi\framework\io\file\interfaces\IFileManager;
use yogi\framework\utils\Guid;
use yogi\framework\settings\interfaces\ISettingsRepository;
use yogi\framework\io\db\PDODal;

class FileManager implements IFileManager {
	
	private $datastore;
	
	public function __construct(ISettingsRepository $settingsRepo) {
		$connectionStrings = $settingsRepo->get('connectionStrings');
		$this->datastore = new PDODal($connectionStrings['Default']['connectionString']);
	}
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\io\file\interfaces\IFileManager::getFileReference()
	 */
	public function getFileReference(Guid $reference) {
		$this->datastore->where(array('reference', '=', $reference->getString()));
		$file = $this->datastore->get('file');
		
		$reference = new FileReference();
		$reference->setReference(Guid::parse($file[0]->reference));
		$reference->setPath($file[0]->path);
		$reference->setMime($file[0]->mime);
		$reference->setFilesize($file[0]->filesize);
		$reference->setId($file[0]->id);
		$reference->setCreated($file[0]->created);
		
		$reference->setFilename($file[0]->filename);
		
		return $reference;
	}
	public function createFileReference() {
		
	}
}