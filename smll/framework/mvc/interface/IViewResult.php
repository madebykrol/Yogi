<?php
interface IViewResult {
	public function render();
	/**
	 * @return HashMap
	 */
	public function getHeaders();
}