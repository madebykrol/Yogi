<?php
namespace yogi\framework\io\file;
use yogi\framework\utils\Boolean;

use yogi\framework\settings\interfaces\ISettingsRepository;
use yogi\framework\io\interfaces\IFileSaveListener;
use yogi\framework\utils\ArrayList;
use yogi\framework\io\file\interfaces\IFileUploadManager;
use yogi\framework\io\file\interfaces\IFileManager;
/**
 * A Basic file upload manager
 * It requests a setter injection of ISettingsRepository to get information about 
 * where on the disk the files should be stored.
 * 
 * Once the file has passed through a series of validation checks it's then 
 * moved to the destination 
 * @author ksdkrol
 *
 */
class FileUploadManager implements IFileUploadManager {
	private $files;
	
	/**
	 * [Inject(yogi\framework\settings\interfaces\ISettingsRepository)]
	 */
	private $settingsRepository;
	
	/**
	 * [Inject(yogi\framework\io\file\interfaces\IFileManager)]
	 */
	private $fileManager;
	
	public function __construct($files) {
		$this->files = $files;
	}
	
	public function setSettingsRepository(ISettingsRepository $settings) {
		$this->settingsRepository = $settings;
	}
	
	public function setFileManager(IFileManager $manager) {
		$this->fileManager = $manager;
	}
	
	public function getUploadedFiles($fieldName) {
		return $this->files[$fieldName];
	}
	
	public function hasFilesInPipe($fieldName) {
		if(isset($this->files[$fieldName])) {
			return true;
		} 
		
		return false;
	}
	
	public function processFile($fieldName, $fileIndex = 0) {
		
		$appPoolSettings = $this->settingsRepository->get('applicationPool');
		$appPoolPath = $appPoolSettings['Default']['path'];
		
		$targetDir = $appPoolPath.$fieldName.'/';
		
		$file = new File();

		$temp = $this->files[$fieldName]['tmp_name'][$fileIndex];
		$target = $this->files[$fieldName]['name'][$fileIndex];
		$file = new File($this->validateAndMoveFile($temp, $target, $targetDir));
		return $file;
	}
	
	private function validateAndMoveFile($temp, $target, $targetDir) {
		$appPoolSettings = $this->settingsRepository->get('applicationPool');
		$appPoolPath = $appPoolSettings['Default']['path'];
		
		$appSettings = $this->settingsRepository->get('web');
		
		$allowedFileTypes = $appSettings['formUpload']['allowedFileTypes'];
		
		if(!is_dir($targetDir)) {
			mkdir($targetDir);
		}
		
		$fileParts = explode(".", $target);
		$fileExtension = ".".$fileParts[count($fileParts)-1];
		$filePassed = false;
		foreach($allowedFileTypes as $filetype) {
			$checkExt = $filetype['extension']; // The allowed extension
			$tmpOrgExt = $fileExtension; // The extension of the uploaded file
				
			// If we are not going to check for case sensitive extensions
			// we need to flatten the extensions to lowercase
			if(!Boolean::parseValue($filetype['caseSensitive'])) {
				$checkExt = strtolower($checkExt);
				$tmpOrgExt = strtolower($tmpOrgExt);
			}
				
			if($checkExt == $tmpOrgExt) {
				$filePassed = true;
				// First check passed.
				// Going to to check mime_type
				if(isset($filetype['mime'])) {
					// Check the mimetype
					$mimes = explode(",", $filetype['mime']);
					if(!in_array(mime_content_type($temp), $mimes)) {
						$filePassed = false;
					} 
				}
			}
		}
		
		if($filePassed) {
			if(move_uploaded_file($temp, $targetDir.$target)) {
				return $targetDir.$target;
			}
		} else {
			throw new \Exception('File '.$this->files[$fieldName]['name'].' could not be uploaded');
		}
		
	}
	
}