<?php
namespace yogi\framework\io\file\interfaces;

use yogi\framework\io\interfaces\IFileSaveListener;

/**
 * IFileUploadManagers handle posted files, such as profile pictures
 * on a message board, or attachments to messages.
 * @author Kristoffer "mbk" Olsson
 *
 */
interface IFileUploadManager {
	
	/**
	 * Get a list of uploaded files in the current request for a specific 
	 * input field name
	 * @param unknown $fieldName
	 */
	public function getUploadedFiles($fieldName);
	
	/**
	 * Process the uploaded files by the specified field.
	 * @param unknown $fieldName
	 */
	public function processFile($fieldName, $fileIndex = 0);
	
	/**
	 * Check if the manager has files in it's process pipe
	 * @param unknown $fieldName
	 */
	public function hasFilesInPipe($fieldName);
}