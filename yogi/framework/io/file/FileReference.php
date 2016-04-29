<?php
namespace yogi\framework\io\file;

use yogi\framework\io\file\interfaces\IFileReference;

use yogi\framework\utils\Guid;

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
	private $reference;
	private $id;
	private $mime;
	private $created;
	private $updated;
	
	public function setCreated($created) {
		$this->created = $created;
	}
	
	public function getCreated() {
		return $this->created;
	}
	
	public function setUpdated($updated) {
		$this->updated = $updated;
	}
	
	public function getUpdated() {
		return $this->updated;
	}
	
	
	public function getFileInfo() {
		return array("Filename" => '', 'Filesize' => '', 'Mime' => '', 'Reference' => '', 'Created' => '', 'Updated' => '');
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
	
	public function setReference(Guid $reference) {
		$this->reference = $reference;
	}
	
	public function getReference() {
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
	
	/**
	 * (non-PHPdoc)
	 * @see \smll\framework\io\file\interfaces\IFileReference::getFile()
	 */
	public function getFile() {
		return new File($this->filename);
	}
	
	public function setPath($path) {
		$this->path = $path;
	}
	
	public function getPath() {
		return $this->path;
	}
	
	public function __toString() {
		return $this->ident->getString();
	}
	
}