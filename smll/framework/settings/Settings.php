<?php
class Settings implements ISettings {
	
	protected $loader;
	
	public function __construct(ISettingsLoader $loader) {
		$this->loader = $loader;
	}
	
	public function load() {
		
	}
	
	public function getAppSetting($setting) {
		return $this->settings->get($setting);
	}
	
	public function getAppSettings() {
		return $this->settings;
	}
}