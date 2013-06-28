<?php
namespace smll\framework\io\interfaces;
interface IRequest {
	/**
	 * getPath returns an array of strings representing the path the user is browsing
	 * eg /my/page/1 => array('my', 'page', '1');
	 * @return array
	 */
	public function getPath();
	
	/**
	 * Set path
	 */
	public function setPath(array $path);
	public function getAccept();
	public function getQueryString($var);
	public function getPostData();
	public function getGetData();
	public function getApplicationRoot();
	
}