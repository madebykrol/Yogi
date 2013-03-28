<?php
class Settings implements ISettings {
	public function load(ISettingsLoader $loader) {
		$this->settings = $loader->getSettings();
	}
	
	public function getAppSetting($setting) {
		return $this->settings->get($setting);
	}
	
	public function getAppSettings() {
		return $this->settings;
	}
}