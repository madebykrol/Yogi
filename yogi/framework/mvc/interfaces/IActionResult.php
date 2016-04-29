<?php
namespace yogi\framework\mvc\interfaces;
interface IActionResult {
	public function render();
	/**
	 * @return HashMap
	 */
	public function getHeaders();
	
	public function useView($boolean = null);
}