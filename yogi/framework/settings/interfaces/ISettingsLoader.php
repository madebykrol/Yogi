<?php
namespace yogi\framework\settings\interfaces;
interface ISettingsLoader {
	/**
	 * return HashMap
	 */
	public function getSettings();
}