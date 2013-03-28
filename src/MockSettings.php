<?php
class MockSettings implements ISettings {
	protected $loader = null;
	protected $settings = null;
	public function load(ISettingsLoader $loader) {
		$this->settings = new HashMap();
		$this->settings->add("derp", "");
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ISettings::getAppSettings()
	 */
	public function getAppSettings() {
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ISettings::getAppSetting()
	 */
	public function getAppSetting($setting) {
		
	}
}