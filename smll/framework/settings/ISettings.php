<?php
interface ISettings {
	/**
	 * @return Object
	 * @param string $setting
	 */
	public function getAppSetting($setting);
	
	/**
	 * @return HashMap
	 */
	public function getAppSettings();
	public function load(ISettingsLoader $loader);
}