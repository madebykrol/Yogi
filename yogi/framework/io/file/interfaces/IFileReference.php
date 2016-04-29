<?php
namespace yogi\framework\io\file\interfaces;

use yogi\framework\utils\Guid;
/**
 * IFileReference provides information about a file stored in a Datastore 
 * Some place else.
 * 
 * A reference to a file on disk or remote is generated and commonly presisted in
 * a database.
 * @author Kristoffer "mbk" Olsson
 *
 */
interface IFileReference {
	
	public function setCreated($created);
	
	public function getCreated();
	
	public function setUpdated($updated);
	
	public function getUpdated();
	
	/**
	 * Get a complete set of information regarding the file
	 */
	public function getFileInfo();
	
	/**
	 * Set the name of the file
	 * @param unknown $filename
	 */
	public function setFilename($filename);
	
	/**
	 * Get the name of the file
	 */
	public function getFilename();
	
	/**
	 * Set file size
	 * @param unknown $filesize
	 */
	public function setFilesize($filesize);
	
	/**
	 * Get the file size
	 */
	public function getFilesize();
	
	/**
	 * Pass a Guid as a identifier for the file
	 * @param Guid $ident
	 */
	public function setReference(Guid $reference);
	
	/**
	 * Return the identifier Guid for the file
	 * @return Guid
	 */
	public function getReference();
	
	/**
	 * Set the file mime
	 * @param unknown $mime
	 */
	public function setMime($mime);
	
	/**
	 * Get the file mime
	 */
	public function getMime();
	
	/**
	 * Set the id
	 * id is not to be confused with the ident!
	 * A id is a automatically generated auto increment value, created by the datastore
	 * @param numeric $id
	 */
	public function setId($id);
	
	/**
	 * id is not to be confused with the ident!
	 * A id is a automatically generated auto increment value, created by the datastore
	 */
	public function getId();
	
	/**
	 * Get the file referenced by this reference
	 */
	public function getFile();
	
	public function getPath();
	
	public function setPath($path);
}