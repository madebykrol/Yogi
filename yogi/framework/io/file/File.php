<?php
namespace yogi\framework\io\file;
class File {
	private $filename;
	private $filepath;
	
	public function __construct($file) {
		$this->filepath = $file;
	}
	
	public function readFileStream() {
		$fileContent = file_get_contents($this->filepath);

		return $fileContent;
	}
	
	public function readBytes($bytes, $offset = 0) {
		
	}
	
	public function writeFile() {}
	
	public function getPath() {}
	public function getAbsolutePath() {
		return $this->filepath;
	}
	
	public function rename($newName) {}
	public function move($newPath) {}
	
	public function exists() {}
	public function isFile() {}
	public function isDir() {}
	
	public function getSize() {
		return filesize($this->filepath);
	}
	public function getMime() {
		return mime_content_type($this->filepath);
	}
	
	public function __toString() {
		return $this->filepath;
	}
	
	
	
}