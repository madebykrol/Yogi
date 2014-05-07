<?php
namespace smll\framework\mvc\interfaces;
interface IActionResult {
	public function render();
	/**
	 * @return HashMap
	 */
	public function getHeaders();
	
	public function useView($boolean = null);
}