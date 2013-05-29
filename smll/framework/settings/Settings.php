<?php
class Settings implements ISettings {
	
	private $loader;
	private $settings;
	
	public function __construct(ISettingsLoader $loader) {
		$this->loader = $loader;
	}
	
	public function load() {
		$this->settings = $this->loader->getSettings();
	}
	
	public function getAppSetting($setting) {
		return $this->settings->get($setting);
	}
	
	public function getAppSettings() {
		return $this->settings;
	}
}