<?php
interface IViewResult {
	
	public function setViewEngine(IViewEngine $engine);
	
	/**
	 * @return string
	 */
	public function render();
	
	/**
	 * @return HashMap
	 */
	public function getHeaders();
	public function setHeader($field, $value);
	public function setHeaders(HashMap $headers);
	
	public function setModel($model);
	public function getModel();
	public function setViewBag($bag);
	public function getViewBag();
	public function setViewFile($file);
	public function getViewFile();
}