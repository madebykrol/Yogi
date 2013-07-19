<?php
namespace smll\framework\io\file;

use smll\framework\io\file\interfaces\IFileReference;

use smll\framework\utils\Guid;

/**
 * File references are used to keep some information regarding a file close at hand,
 * A file referene is commonly stored by a Data store and accessed through it's
 * Guid
 * A file reference can refer to a file on disk or remote on another server.
 * through HTTP or HTTPS
 * @author Kristoffer "mbk" Olsson
 *
 */
class FileReference implements IFileReference {
	
	private $filename;
	private $path;
	private $filesize;
	private $ident;
	private $id;
	private $mime;
	
	
	public function getFileInfo() {
		return array("Filename" => '', 'Filesize' => '', 'Mime' => '', 'Ident' => '');
	}
	
	public function setFilename($filename) {
		$this->filename = $filename;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\io\file\interfaces\IFileReference::getFilename()
	 */
	public function getFilename() {
		return $this->filename;
	}
	
	public function setFilesize($filesize) {
		$this->filesize = $filesize;
	}
	
	public function getFilesize() {
		return $this->filesize;
	}
	
	public function setIdent(Guid $ident) {
		$this->ident = $ident;
	}
	
	public function getIdent() {
		return $this->ident;
	}
	
	public function setMime($mime) {
		$this->mime = $mime;
	}
	
	public function getMime() {
		return $this->mime;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function __toString() {
		return $this->ident->getString();
	}
	
}